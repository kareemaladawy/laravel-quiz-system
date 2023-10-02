<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Quiz\QuizForm;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuizzesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_quiz(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuizForm::class)
            ->set('quiz.title', 'Q1 title')
            ->call('save')
            ->assertHasNoErrors(['quiz.title', 'quiz.slug', 'quiz.description', 'quiz.published', 'quiz.public'])
            ->assertRedirect(route('quizzes'));

        $this->assertDatabaseHas('quizzes', [
            'title' => 'Q1 title',
        ]);
    }

    public function test_quiz_title_is_required()
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(QuizForm::class)
            ->set('quiz.title', '')
            ->call('save')
            ->assertHasErrors(['quiz.title' => 'required']);
    }

    public function test_admin_can_edit_quiz()
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = Quiz::factory()->create();

        Livewire::test(QuizForm::class, [$quiz])
            ->set('quiz.title', 'new quiz')
            ->set('quiz.published', true)
            ->call('save')
            ->assertSet('quiz.published', true)
            ->assertHasNoErrors(['quiz.title', 'quiz.slug', 'quiz.description', 'quiz.published', 'quiz.public']);

        $this->assertDatabaseHas('quizzes', [
            'title' => 'new quiz',
            'published' => true,
        ]);
    }
}
