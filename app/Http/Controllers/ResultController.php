<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Answer;
use Illuminate\Contracts\View\View;

class ResultController extends Controller
{
    public function show(Test $test): View
    {
        $questions_count = $test->quiz->questions->count();

        $results = Answer::where('test_id', $test->id)
            ->with('question.options')
            ->get();

        if (!$test->quiz->public) {
            $leaderboard = Test::query()
                ->where('quiz_id', $test->quiz_id)
                ->whereHas('user')
                ->with(['user' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->orderBy('result', 'desc')
                ->orderBy('time_spent')
                ->get();

            return view('front.quizzes.result', compact('test', 'questions_count', 'results', 'leaderboard'));
        }

        return view('front.quizzes.result', compact('test', 'questions_count', 'results'));
    }
}
