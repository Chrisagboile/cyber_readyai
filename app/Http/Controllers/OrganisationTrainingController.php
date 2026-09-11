<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LearningPlan;
use App\Models\User;
use Illuminate\Http\Request;

class OrganisationTrainingController extends Controller
{
    private function organisationId(Request $request): int
    {
        $user = $request->user();

        abort_unless(
            $user
            && $user->hasRole('organisation-admin')
            && $user->organisation_id,
            403
        );

        return (int) $user->organisation_id;
    }

    public function index(Request $request)
    {
        $organisationId = $this->organisationId($request);

        /*
        |--------------------------------------------------------------------------
        | Organisation employees
        |--------------------------------------------------------------------------
        */

        $employees = User::query()
            ->where('organisation_id', $organisationId)
            ->whereHas('role', function ($query) {
                $query->where('slug', 'employee');
            })
            ->with('department')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->where('organisation_id', $organisationId)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Learning plans
        |--------------------------------------------------------------------------
        */

        $plans = LearningPlan::query()
            ->whereHas('user', function ($query) use ($organisationId) {
                $query
                    ->where('organisation_id', $organisationId)
                    ->whereHas('role', function ($roleQuery) {
                        $roleQuery->where('slug', 'employee');
                    });
            })
            ->with([
                'user.department',
                'assessmentAttempt.assessment',
            ])
            ->orderByRaw("
                CASE
                    WHEN status = 'in_progress' THEN 1
                    WHEN status = 'not_started' THEN 2
                    WHEN status = 'completed' THEN 3
                    ELSE 4
                END
            ")
            ->orderByDesc('created_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $departmentId = $request->integer('department_id');
        $employeeId = $request->integer('employee_id');
        $status = strtolower(trim((string) $request->input('status', '')));
        $priority = strtolower(trim((string) $request->input('priority', '')));
        $progress = strtolower(trim((string) $request->input('progress', '')));

        if ($departmentId) {
            $plans = $plans->filter(function ($plan) use ($departmentId) {
                return (int) optional($plan->user)->department_id === $departmentId;
            });
        }

        if ($employeeId) {
            $plans = $plans->filter(function ($plan) use ($employeeId) {
                return (int) $plan->user_id === $employeeId;
            });
        }

        if (in_array($status, [
            'not_started',
            'in_progress',
            'completed',
        ], true)) {
            $plans = $plans->filter(function ($plan) use ($status) {
                return strtolower((string) $plan->status) === $status;
            });
        }

        if (in_array($priority, [
            'low',
            'medium',
            'high',
        ], true)) {
            $plans = $plans->filter(function ($plan) use ($priority) {
                return strtolower((string) $plan->priority) === $priority;
            });
        }

        if (in_array($progress, [
            '0',
            '1_49',
            '50_99',
            '100',
        ], true)) {
            $plans = $plans->filter(function ($plan) use ($progress) {
                $value = (int) $plan->progress_percentage;

                return match ($progress) {
                    '0'     => $value === 0,
                    '1_49'  => $value >= 1 && $value <= 49,
                    '50_99' => $value >= 50 && $value <= 99,
                    '100'   => $value >= 100,
                    default => true,
                };
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Base organisation-wide statistics
        |--------------------------------------------------------------------------
        */

        $allPlans = LearningPlan::query()
            ->whereHas('user', function ($query) use ($organisationId) {
                $query
                    ->where('organisation_id', $organisationId)
                    ->whereHas('role', function ($roleQuery) {
                        $roleQuery->where('slug', 'employee');
                    });
            })
            ->with([
                'user.department',
                'assessmentAttempt.assessment',
            ])
            ->get();

        $totalPlans = $allPlans->count();

        $completedPlans = $allPlans
            ->where('status', 'completed')
            ->count();

        $inProgressPlans = $allPlans
            ->where('status', 'in_progress')
            ->count();

        $notStartedPlans = $allPlans
            ->where('status', 'not_started')
            ->count();

        $averageProgress = $totalPlans > 0
            ? round(
                $allPlans->avg(function ($plan) {
                    return (float) $plan->progress_percentage;
                }),
                1
            )
            : 0;

        $overduePlans = $allPlans
            ->filter(function ($plan) {
                return $plan->due_date
                    && $plan->status !== 'completed'
                    && $plan->due_date->isPast();
            })
            ->count();

        $highPriorityPlans = $allPlans
            ->filter(function ($plan) {
                return strtolower((string) $plan->priority) === 'high'
                    && $plan->status !== 'completed';
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Employees with plans
        |--------------------------------------------------------------------------
        */

        $employeesWithPlans = $allPlans
            ->pluck('user_id')
            ->unique()
            ->count();

        $employeesWithoutPlans = max(
            0,
            $employees->count() - $employeesWithPlans
        );

        /*
        |--------------------------------------------------------------------------
        | Department summary
        |--------------------------------------------------------------------------
        */

        $departmentTraining = $departments->map(function ($department) use ($allPlans) {
            $departmentPlans = $allPlans->filter(function ($plan) use ($department) {
                return (int) optional($plan->user)->department_id === (int) $department->id;
            });

            $planCount = $departmentPlans->count();

            $completed = $departmentPlans
                ->where('status', 'completed')
                ->count();

            $inProgress = $departmentPlans
                ->where('status', 'in_progress')
                ->count();

            $overdue = $departmentPlans
                ->filter(function ($plan) {
                    return $plan->due_date
                        && $plan->status !== 'completed'
                        && $plan->due_date->isPast();
                })
                ->count();

            $avgProgress = $planCount > 0
                ? round(
                    $departmentPlans->avg(function ($plan) {
                        return (float) $plan->progress_percentage;
                    }),
                    1
                )
                : 0;

            $employeeCount = User::query()
                ->where('organisation_id', $department->organisation_id)
                ->where('department_id', $department->id)
                ->whereHas('role', function ($query) {
                    $query->where('slug', 'employee');
                })
                ->count();

            return (object) [
                'department' => $department,
                'employees' => $employeeCount,
                'plans' => $planCount,
                'completed' => $completed,
                'in_progress' => $inProgress,
                'overdue' => $overdue,
                'average_progress' => $avgProgress,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Employee training summary
        |--------------------------------------------------------------------------
        */

        $employeeTraining = $employees->map(function ($employee) use ($allPlans) {
            $employeePlans = $allPlans->where('user_id', $employee->id);

            $planCount = $employeePlans->count();

            $completed = $employeePlans
                ->where('status', 'completed')
                ->count();

            $inProgress = $employeePlans
                ->where('status', 'in_progress')
                ->count();

            $notStarted = $employeePlans
                ->where('status', 'not_started')
                ->count();

            $overdue = $employeePlans
                ->filter(function ($plan) {
                    return $plan->due_date
                        && $plan->status !== 'completed'
                        && $plan->due_date->isPast();
                })
                ->count();

            $average = $planCount > 0
                ? round(
                    $employeePlans->avg(function ($plan) {
                        return (float) $plan->progress_percentage;
                    }),
                    1
                )
                : 0;

            return (object) [
                'employee' => $employee,
                'plans' => $planCount,
                'completed' => $completed,
                'in_progress' => $inProgress,
                'not_started' => $notStarted,
                'overdue' => $overdue,
                'average_progress' => $average,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Current attention list
        |--------------------------------------------------------------------------
        */

        $attentionEmployees = $employeeTraining
            ->filter(function ($row) {
                return $row->overdue > 0
                    || $row->in_progress > 0
                    || $row->average_progress < 50
                    || $row->plans === 0;
            })
            ->sortByDesc(function ($row) {
                return (
                    ($row->overdue * 1000)
                    + ($row->plans === 0 ? 500 : 0)
                    + ($row->in_progress * 10)
                    + (100 - $row->average_progress)
                );
            })
            ->values()
            ->take(10);

        /*
        |--------------------------------------------------------------------------
        | Selected filter labels
        |--------------------------------------------------------------------------
        */

        $selectedDepartment = $departmentId
            ? $departments->firstWhere('id', $departmentId)
            : null;

        $selectedEmployee = $employeeId
            ? $employees->firstWhere('id', $employeeId)
            : null;

        return view('training.index', [
            'plans' => $plans,
            'departments' => $departments,
            'employees' => $employees,
            'departmentTraining' => $departmentTraining,
            'employeeTraining' => $employeeTraining,
            'attentionEmployees' => $attentionEmployees,
            'totalPlans' => $totalPlans,
            'completedPlans' => $completedPlans,
            'inProgressPlans' => $inProgressPlans,
            'notStartedPlans' => $notStartedPlans,
            'averageProgress' => $averageProgress,
            'overduePlans' => $overduePlans,
            'highPriorityPlans' => $highPriorityPlans,
            'employeesWithPlans' => $employeesWithPlans,
            'employeesWithoutPlans' => $employeesWithoutPlans,
            'departmentId' => $departmentId,
            'employeeId' => $employeeId,
            'status' => $status,
            'priority' => $priority,
            'progress' => $progress,
            'selectedDepartment' => $selectedDepartment,
            'selectedEmployee' => $selectedEmployee,
        ]);
    }
}
