<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostIdeaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

     protected $idea;

    public function __construct($idea)
    {
        $this->idea = $idea;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Successfully Post Idea',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $idea = $this->idea;

        $postedDate = Carbon::parse($idea->updated_at)->format('d F Y');

        $postedBy = $idea->is_anonymous? "Anonymous" : $idea->user->name;

        return new Content(
            view: 'mail.PostIdea',
            with: [
                'ideaTitle' => $idea->title,
                'postedBy' => $postedBy,
                'postedDate' => $postedDate,
                'ideaContent' => $idea->content,
                'ideaUrl' => 'www.idea.com'
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
