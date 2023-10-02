<?php

namespace App\Http\Livewire\Front\Quizzes;

use App\Models\Question;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    public Quiz $quiz;

    public Collection $questions;

    public Question $currentQuestion;
    public int $currentQuestionIndex = 0;

    public array $answersOfQuestions = [];

    public int $startTimeInSeconds = 0;

    public function mount()
    {
        $this->startTimeInSeconds = now()->timestamp;

        $this->questions = Question::query()
            ->inRandomOrder()
            ->whereRelation('quizzes', 'id', $this->quiz->id)
            ->with('options')
            ->get();

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];

        for ($questionIndex = 0; $questionIndex < $this->questionsCount; $questionIndex++) {
            $this->answersOfQuestions[$questionIndex] = [];
        }
    }

    public function getQuestionsCountProperty(): int
    {
        return $this->questions->count();
    }

    public function nextQuestion()
    {
        $this->currentQuestionIndex++;

        if ($this->currentQuestionIndex == $this->questionsCount) {
            return $this->submit();
        }

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
    }

    public function submit()
    {
        $result = 0;

        $test = Test::create([
            'user_id' => auth()->id(),
            'quiz_id' => $this->quiz->id,
            'result' => 0,
            'ip_address' => request()->ip(),
            'time_spent' => now()->timestamp - $this->startTimeInSeconds
        ]);

        foreach ($this->answersOfQuestions as $key => $optionId) {
            if (!empty($optionId) && Option::find($optionId)->correct) {
                $result++;
                Answer::create([
                    'user_id' => auth()->id(),
                    'test_id' => $test->id,
                    'question_id' => $this->questions[$key]->id,
                    'option_id' => $optionId,
                    'correct' => 1
                ]);
            } else {
                Answer::create([
                    'user_id' => auth()->id(),
                    'test_id' => $test->id,
                    'question_id' => $this->questions[$key]->id,
                ]);
            }
        }

        $test->update([
            'result' => $result
        ]);


        return to_route('results.show', ['test' => $test]);
    }

    public function render(): View
    {
        return view('livewire.front.quizzes.show');
    }
}
