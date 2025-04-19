<?php

namespace App\Mail;

use App\Models\Idea;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproveMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    protected $idea;

    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Idea was Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $idea = $this->idea;

        $approveDate = Carbon::parse($idea->updated_at)->format('d F Y');

        $postedBy = $idea->is_anonymous? "Anonymous" : $idea->user->name;

        return new Content(
            view: 'mail.SubmitIdea',
            with: [
                'ideaTitle' => $idea->title,
                'postedBy' => $postedBy,
                'approvedDate' => $approveDate,
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
