<?php

namespace App\Http\Livewire\Question;

use App\Models\Question;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class QuestionList extends Component
{
    public function delete(Question $question)
    {
        abort_if(!auth()->user()->is_admin, Response::HTTP_FORBIDDEN, 403);

        $question->delete();
    }

    public function render(): View
    {
        $questions = Question::latest()->paginate();

        return view('livewire.question.qusetion-list', compact('questions'));
    }
}
