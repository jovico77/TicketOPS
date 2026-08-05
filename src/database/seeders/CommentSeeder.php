<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@ticketops.local')->firstOrFail();

        $ticket1 = Ticket::where('ticket_number', 'TCK-2026-000001')->firstOrFail();
        $ticket2 = Ticket::where('ticket_number', 'TCK-2026-000002')->firstOrFail();
        $ticket3 = Ticket::where('ticket_number', 'TCK-2026-000003')->firstOrFail();

        $comments = [
            [
                'ticket_id' => $ticket1->id,
                'user_id' => $user->id,
                'message' => 'I am experiencing issues with Outlook. It crashes immediately after opening.',
            ],
            [
                'ticket_id' => $ticket2->id,
                'user_id' => $user->id,
                'message' => 'I cannot connect to the VPN. It shows an authentication error.',
            ],
            [
                'ticket_id' => $ticket3->id,
                'user_id' => $user->id,
                'message' => 'I need a password reset for my account. I forgot my current password.',
            ],

            [
                'ticket_id' => $ticket3->id,
                'user_id' => $user->id,
                'message' => 'Internal note: User forgot their password and requested a reset. Please verify their identity before proceeding.',
                'is_private' => true,
            ]
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }
    }
}
