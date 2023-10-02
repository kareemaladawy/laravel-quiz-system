<?php

namespace App\Http\Controllers;

use App\Models\Quiz;

class HomeController extends Controller
{
    public function index()
    {
        $query = Quiz::whereHas('questions')
            ->withCount('questions')
            ->when(auth()->guest() || !auth()->user()->is_admin, function ($query) {
                return $query->where('published', 1);
            })
            ->get();

        $public_quizzes = $query->where('public', 1);
        $registered_only_quizzes = $query->where('public', 0);

        return view('home', compact('public_quizzes', 'registered_only_quizzes'));
    }

    public function show(Quiz $quiz)
    {
        return view('front.quizzes.show', compact('quiz'));
    }
}
