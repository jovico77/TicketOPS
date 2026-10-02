<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_owner_can_post_a_public_comment(): void
    {
        $user = $this->createUserWithRole('User');
        $ticket = Ticket::factory()->create(['created_by' => $user->id]);

        $this->actingAs($user)
            ->post(route('tickets.comments.store', $ticket), [
                'message' => 'I can provide more information.',
                'user_id' => User::factory()->create()->id,
                'is_private' => true,
            ])
            ->assertRedirect(route('tickets.show', $ticket) . '#comments');

        $comment = Comment::firstOrFail();
        $this->assertSame($ticket->id, $comment->ticket_id);
        $this->assertSame($user->id, $comment->user_id);
        $this->assertSame('I can provide more information.', $comment->message);
        $this->assertFalse($comment->is_private);

        $this->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSee('I can provide more information.');
    }

    public function test_ticket_owner_cannot_comment_on_another_users_ticket(): void
    {
        $user = $this->createUserWithRole('User');
        $anotherUser = $this->createUserWithRole('User');
        $ticket = Ticket::factory()->create(['created_by' => $anotherUser->id]);

        $this->actingAs($user)
            ->post(route('tickets.comments.store', $ticket), [
                'message' => 'Unauthorized comment.',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('comments', [
            'ticket_id' => $ticket->id,
            'message' => 'Unauthorized comment.',
        ]);
    }

    public function test_support_staff_can_add_public_comments_to_any_ticket(): void
    {
        $owner = $this->createUserWithRole('User');
        $ticket = Ticket::factory()->create(['created_by' => $owner->id]);
        $technician = $this->createUserWithRole('Technician');

        $this->actingAs($technician)
            ->post(route('tickets.comments.store', $ticket), [
                'message' => 'Support is investigating this request.',
            ])
            ->assertRedirect(route('tickets.show', $ticket) . '#comments');

        $comment = Comment::firstOrFail();
        $this->assertSame($technician->id, $comment->user_id);
        $this->assertFalse($comment->is_private);

        $this->actingAs($owner)
            ->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Support is investigating this request.');
    }

    public function test_public_comment_thread_does_not_show_private_comments(): void
    {
        $owner = $this->createUserWithRole('User');
        $ticket = Ticket::factory()->create(['created_by' => $owner->id]);

        $ticket->comments()->create([
            'user_id' => $owner->id,
            'message' => 'Visible to requester.',
            'is_private' => false,
        ]);
        $ticket->comments()->create([
            'user_id' => $owner->id,
            'message' => 'Internal note should stay hidden.',
            'is_private' => true,
        ]);

        $this->actingAs($owner)
            ->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Visible to requester.')
            ->assertDontSee('Internal note should stay hidden.');
    }

    private function createUserWithRole(string $roleName): User
    {
        return User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => $roleName])->id,
        ]);
    }
}
