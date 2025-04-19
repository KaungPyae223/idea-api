<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

     protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {

        return new Envelope(
            subject: 'You Have Received Comment',
        );

    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        $commentDate = Carbon::parse($this->comment->created_at)->format('d F Y');


        return new Content(
            view: 'mail.ReceiveComment',
            with: [
                'ideaTitle' => $this->comment->idea->title,
                'commentDate' => $commentDate,
                'commentAuthor' => $this->comment->user->name,
                'commentText' => $this->comment->comment,
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
