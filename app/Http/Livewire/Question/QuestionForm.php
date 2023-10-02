<?php

namespace App\Http\Livewire\Question;

use App\Models\Question;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuestionForm extends Component
{
    public Question $question;

    public array $options = [];

    public bool $editing = false;

    protected $rules = [
        'question.text' => 'required|string',
        'question.code_snippet' => 'nullable|string',
        'question.answer_explanation' => 'nullable|string',
        'question.more_info_link' => 'nullable|url',
        'options' => 'required|array',
        'options.*.text' => 'required|string',
    ];

    public function mount(Question $question): Void
    {
        $this->question = $question;

        if ($this->question->exists) {
            $this->editing = true;

            foreach ($this->question->options as $option) {
                $this->options[] = [
                    'id' => $option->id,
                    'text' => $option->text,
                    'correct' => $option->correct,
                ];
            }
        }
    }

    public function addOption(): Void
    {
        $this->options[] = [
            'text' => '',
            'correct' => false
        ];
    }

    public function removeOption(int $index): Void
    {
        unset($this->options[$index]);
        $this->options = array_values(($this->options));
    }

    public function save()
    {
        $this->validate();

        $this->question->save();

        $this->question->options()->delete();

        foreach ($this->options as $option) {
            $this->question->options()->create($option);
        }

        return to_route('questions');
    }

    public function render(): View
    {
        return view('livewire.question.question-form');
    }
}
