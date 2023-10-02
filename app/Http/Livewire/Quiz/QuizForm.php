<?php

namespace App\Http\Livewire\Quiz;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Support\Str;

class QuizForm extends Component
{
    public Quiz $quiz;

    public array $questions = [];

    public bool $editing = false;

    public array $listsForFields = [];

    protected $rules = [
        'quiz.title' => 'required|string',
        'quiz.slug' => 'string',
        'quiz.description' => 'nullable|string',
        'quiz.published' => 'boolean',
        'quiz.public' => 'boolean',
        'quiz.published' => 'boolean',
        'questions' => 'nullable|array',
    ];

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;

        $this->initListsForFields();

        if ($this->quiz->exists) {
            $this->editing = true;
            $this->questions = $this->quiz->questions()->pluck('id')->toArray();
        } else {
            $this->quiz->published = false;
            $this->quiz->public = false;
        }
    }

    public function updatedQuizTitle(): void
    {
        $this->quiz->slug = Str::slug($this->quiz->title);
    }

    public function save()
    {
        $this->validate();

        $this->quiz->save();

        $this->quiz->questions()->sync($this->questions);

        return to_route('quizzes');
    }

    protected function initListsForFields()
    {
        $this->listsForFields['questions'] = Question::pluck('text', 'id')->toArray();
    }

    public function render(): View
    {
        return view('livewire.quiz.quiz-form');
    }
}
