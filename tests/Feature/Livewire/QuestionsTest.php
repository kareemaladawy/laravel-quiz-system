<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Question\QuestionForm;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_question(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuestionForm::class)
            ->set('question.text', 'Q1 text')
            ->set('options.0.text', 'Op1 text')
            ->call('save')
            ->assertHasNoErrors(['question.text', 'question.code_snippet', 'question.answer_explanation', 'question.more_info_link', 'options', 'options.*.text'])
            ->assertRedirect(route('questions'));

        $this->assertDatabaseHas('questions', ['text' => 'Q1 text']);
    }

    public function test_question_and_option_text_is_required(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuestionForm::class)
            ->set('question.text', '')
            ->call('save')
            ->assertHasErrors(['question.text' => 'required', 'options' => 'required']);
    }

    public function test_admin_can_edit_question(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $question = Question::factory()->has(Option::factory(), 'options')->create();

        Livewire::test(QuestionForm::class, [$question])
            ->set('question.text', 'Updated Q')
            ->call('save')
            ->assertHasNoErrors(['question.text', 'question.code_snippet', 'question.answer_explanation', 'question.more_info_link', 'options', 'options.*.text']);
    }
}
