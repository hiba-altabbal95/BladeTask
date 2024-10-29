<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendingTasksMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tasks;
    /**
     * Create a new message instance.
     */
    public function __construct( $tasks)
    {
        $this->tasks = $tasks;
    }

    public function build()
    {
        return $this->subject('pending task')
                    ->markdown('emails.pendingtasks')
                    ->with('tasks', $this->tasks);
    }
  
}
