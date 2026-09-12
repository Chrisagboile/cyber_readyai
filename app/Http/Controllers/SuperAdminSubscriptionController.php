<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuperAdminSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user->hasRole('super-admin'), 403);

        $query = Subscription::query()
            ->with('organisation')
            ->orderByRaw("
                CASE status
                    WHEN 'active' THEN 1
                    WHEN 'trial' THEN 2
                    WHEN 'expired' THEN 3
                    WHEN 'cancelled' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('ends_at');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('plan_name', 'like', "%{$search}%")
                    ->orWhereHas('organisation', function ($organisationQuery) use ($search) {
                        $organisationQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && in_array(
            $request->status,
            ['active', 'trial', 'expired', 'cancelled'],
            true
        )) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->paginate(10)->withQueryString();

        $totalSubscriptions = Subscription::count();

        $activeSubscriptions = Subscription::where('status', 'active')->count();

        $trialSubscriptions = Subscription::where('status', 'trial')->count();

        $expiredSubscriptions = Subscription::where('status', 'expired')->count();

        $cancelledSubscriptions = Subscription::where('status', 'cancelled')->count();

/*        $monthlyRevenue = Subscription::whereIn('status', ['active', 'trial'])
            ->where('billing_interval', 'monthly')
            ->sum('price');

        $annualRevenue = Subscription::whereIn('status', ['active', 'trial'])
            ->where('billing_interval', 'yearly')
            ->sum('price');
*/
        $monthlyRevenue = Subscription::whereIn('status', ['active', 'trial'])
            ->where('billing_interval', 'monthly')
            ->sum('price');

        $annualRevenue = Subscription::whereIn('status', ['active', 'trial'])
            ->get()
            ->sum(function ($subscription) {
                return $subscription->billing_interval === 'monthly'
                    ? (float) $subscription->price * 12
                    : (float) $subscription->price;
            });
        return view('subscriptions.index', compact(
            'subscriptions',
            'totalSubscriptions',
            'activeSubscriptions',
            'trialSubscriptions',
            'expiredSubscriptions',
            'cancelledSubscriptions',
            'monthlyRevenue',
            'annualRevenue'
        ));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->hasRole('super-admin'), 403);

        $organisations = Organisation::orderBy('name')->get();

        return view('subscriptions.create', compact('organisations'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->hasRole('super-admin'), 403);

        $validated = $request->validate([
            'organisation_id' => [
                'required',
                'integer',
                Rule::exists('organisations', 'id'),
            ],
            'plan_name' => ['required', 'string', 'max:100'],
            'status' => [
                'required',
                Rule::in(['active', 'trial', 'expired', 'cancelled']),
            ],
            'billing_interval' => [
                'required',
                Rule::in(['monthly', 'yearly']),
            ],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'max_users' => ['nullable', 'integer', 'min:1'],
            'max_assessments' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'trial_ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        Subscription::create($validated);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    public function edit(Request $request, Subscription $subscription)
    {
        abort_unless($request->user()->hasRole('super-admin'), 403);

        $subscription->load('organisation');

        $organisations = Organisation::orderBy('name')->get();

        return view('subscriptions.edit', compact(
            'subscription',
            'organisations'
        ));
    }

    public function update(
        Request $request,
        Subscription $subscription
    ) {
        abort_unless($request->user()->hasRole('super-admin'), 403);

        $validated = $request->validate([
            'organisation_id' => [
                'required',
                'integer',
                Rule::exists('organisations', 'id'),
            ],
            'plan_name' => ['required', 'string', 'max:100'],
            'status' => [
                'required',
                Rule::in(['active', 'trial', 'expired', 'cancelled']),
            ],
            'billing_interval' => [
                'required',
                Rule::in(['monthly', 'yearly']),
            ],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'max_users' => ['nullable', 'integer', 'min:1'],
            'max_assessments' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'trial_ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'cancelled_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $subscription->update($validated);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    public function cancel(
        Request $request,
        Subscription $subscription
    ) {
        abort_unless($request->user()->hasRole('super-admin'), 403);

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now()->toDateString(),
        ]);

        return back()->with(
            'success',
            'Subscription cancelled successfully.'
        );
    }
}
