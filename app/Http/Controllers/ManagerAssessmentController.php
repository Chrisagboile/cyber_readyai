<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use App\Models\EmployeeQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerAssessmentController extends Controller
{
    /**
     * Display assessment creation page.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $employees = User::whereHas('role', function ($query) {
            $query->where('name', 'employee');
        })
        ->orderBy('name')
        ->get();

        return view(
            'manager.assessments.create',
            compact(
                'categories',
                'employees'
            )
        );
    }


    /**
     * Generate random questions.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:users,id'
            ],

            'category_ids' => [
                'required',
                'array',
                'min:1'
            ],

            'category_ids.*' => [
                'integer',
                'exists:categories,id'
            ],

            'question_quantity' => [
                'required',
                'integer',
                'min:1',
                'max:200'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify employee
        |--------------------------------------------------------------------------
        */

        $employee = User::findOrFail(
            $validated['employee_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Find available questions
        |--------------------------------------------------------------------------
        */

        $availableQuestions = Question::whereIn(
            'category_id',
            $validated['category_ids']
        )->count();


        if (
            $validated['question_quantity']
            > $availableQuestions
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'question_quantity' =>
                        "Only {$availableQuestions} questions are available "
                        . "in the selected categories."
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Randomly select questions
        |--------------------------------------------------------------------------
        */

        $questions = Question::whereIn(
            'category_id',
            $validated['category_ids']
        )
        ->inRandomOrder()
        ->limit(
            $validated['question_quantity']
        )
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Store selected questions
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $employee,
            $questions
        ) {

            foreach (
                $questions as $index => $question
            ) {

                EmployeeQuestion::create([
                    'employee_id' =>
                        $employee->id,

                    'question_id' =>
                        $question->id,

                    'question_order' =>
                        $index + 1,
                ]);
            }

        });


        return redirect()
            ->route(
                'manager.assessments.create'
            )
            ->with(
                'success',
                $questions->count()
                . ' questions generated successfully.'
            );
    }
}
