<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhishingCampaign;
use App\Models\PhishingCampaignRecipient;
use App\Models\PhishingEvent;
use App\Models\PhishingTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PhishingCampaignController extends Controller
{
    private function role(): string
    {
        return auth()->user()->role?->slug ?? '';
    }

    private function canManage(): bool
    {
        return in_array($this->role(), [
            'super-admin',
            'organisation-admin',
            'manager',
        ], true);
    }

    private function campaignQuery()
    {
        $user = auth()->user();

        $query = PhishingCampaign::with([
            'creator',
            'template',
            'recipients.user',
        ])->latest();

        if ($this->role() === 'super-admin') {
            return $query;
        }

        if (! $user->organisation_id) {
            return $query->whereRaw('1 = 0');
        }

        if ($this->role() === 'manager') {
            return $query->where('created_by', $user->id);
        }

        return $query->whereHas('creator', function ($q) use ($user) {
            $q->where('organisation_id', $user->organisation_id);
        });
    }

    private function eligibleUsersQuery()
    {
        $user = auth()->user();

        $query = User::query()
            ->whereHas('role', fn ($q) => $q->where('slug', 'employee'))
            ->where('status', 'active');

        if ($this->role() !== 'super-admin') {
            $query->where('organisation_id', $user->organisation_id);
        }

        return $query;
    }

    public function index()
    {
        abort_unless($this->canManage(), 403);

        $campaigns = $this->campaignQuery()->paginate(15);

        return view('phishing.index', compact('campaigns'));
    }

    public function create()
    {
        abort_unless($this->canManage(), 403);

        $employees = $this->eligibleUsersQuery()
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('phishing.create', compact('employees'));
    }

    public function store(Request $request)
    {
        abort_unless($this->canManage(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:10000'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer', 'distinct'],
        ]);

        $eligibleIds = $this->eligibleUsersQuery()
            ->whereIn('id', $data['user_ids'])
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (count($eligibleIds) !== count(array_unique($data['user_ids']))) {
            throw ValidationException::withMessages([
                'user_ids' => 'One or more selected employees are not available for your account.',
            ]);
        }

        $campaign = DB::transaction(function () use ($data, $eligibleIds) {
            $template = PhishingTemplate::create([
                'created_by' => auth()->id(),
                'name' => $data['name'] . ' template',
                'category' => 'general',
                'subject' => $data['subject'],
                'body' => $data['body'],
                'landing_page_content' =>
                    'This was a CyberReadyAI phishing-awareness simulation. ' .
                    'In a real message, check the sender, destination and request before taking action.',
                'is_active' => true,
            ]);

            $campaign = PhishingCampaign::create([
                'template_id' => $template->id,
                'created_by' => auth()->id(),
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'status' => 'draft',
            ]);

            foreach ($eligibleIds as $employeeId) {
                $recipient = $campaign->recipients()->create([
                    'user_id' => $employeeId,
                    'status' => 'pending',
                ]);

                PhishingEvent::create([
                    'recipient_id' => $recipient->id,
                    'event_type' => 'assigned',
                    'event_data' => ['source' => 'in_app'],
                    'occurred_at' => now(),
                ]);
            }

            return $campaign;
        });

        return redirect()
            ->route('phishing.show', $campaign)
            ->with('success', 'Campaign created and assigned as a draft. Launch it when you are ready.');
    }

    public function show(PhishingCampaign $campaign)
    {
        abort_unless($this->canManage(), 403);

        $campaign = $this->campaignQuery()
            ->whereKey($campaign->id)
            ->firstOrFail();

        $recipients = $campaign->recipients;
        $total = $recipients->count();

        $stats = [
            'assigned' => $total,
            'clicked' => $recipients->whereNotNull('clicked_at')->count(),
            'visited' => $recipients->whereNotNull('landing_page_visited_at')->count(),
            'reported' => $recipients->whereNotNull('reported_at')->count(),
        ];

        return view('phishing.show', compact('campaign', 'stats'));
    }

    public function launch(PhishingCampaign $campaign)
    {
        abort_unless($this->canManage(), 403);

        $campaign = $this->campaignQuery()
            ->whereKey($campaign->id)
            ->firstOrFail();

        if ($campaign->status !== 'draft') {
            return back()->with('error', 'Only draft campaigns can be launched.');
        }

        if ($campaign->recipients()->count() === 0) {
            return back()->with('error', 'Assign at least one employee before launching.');
        }

        $campaign->update([
            'status' => 'active',
            'started_at' => now(),
        ]);

        return redirect()
            ->route('phishing.show', $campaign)
            ->with('success', 'Simulation launched in CyberReadyAI.');
    }

    public function employeeIndex()
    {
        abort_unless($this->role() === 'employee', 403);
        $recipients = PhishingCampaignRecipient::with('campaign.template')
            ->where('user_id', auth()->id())
            ->whereHas('campaign', fn ($q) => $q->where('status', 'active'))
            ->latest()
            ->paginate(10);

        return view('phishing.employee-index', compact('recipients'));
    }

    public function openSimulation(PhishingCampaignRecipient $recipient)
    {
        abort_unless($this->role() === 'employee', 403);
        abort_unless((int) $recipient->user_id === (int) auth()->id(), 403);

        abort_unless($recipient->campaign?->status === 'active', 404);

        if (! $recipient->clicked_at) {
            $recipient->update([
                'clicked_at' => now(),
                'status' => 'clicked',
            ]);

            $recipient->events()->create([
                'event_type' => 'clicked',
                'event_data' => ['source' => 'in_app'],
                'occurred_at' => now(),
            ]);
        }

        if (! $recipient->landing_page_visited_at) {
            $recipient->update(['landing_page_visited_at' => now()]);

            $recipient->events()->create([
                'event_type' => 'landing_page_visited',
                'event_data' => ['source' => 'in_app'],
                'occurred_at' => now(),
            ]);
        }

        return view('phishing.landing', compact('recipient'));
    }

    public function reportSimulation(PhishingCampaignRecipient $recipient)
    {abort_unless($this->role() === 'employee', 403);
        abort_unless((int) $recipient->user_id === (int) auth()->id(), 403);

        abort_unless($recipient->campaign?->status === 'active', 404);

        if (! $recipient->reported_at) {
            $recipient->update([
                'reported_at' => now(),
                'status' => 'reported',
            ]);

            $recipient->events()->create([
                'event_type' => 'reported',
                'event_data' => ['source' => 'in_app'],
                'occurred_at' => now(),
            ]);
        }

        return redirect()
            ->route('phishing.employee')
            ->with('success', 'Thanks for reporting this simulation. Reporting suspicious messages is a good security habit.');
    }
}
