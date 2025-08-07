<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EscalationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $messageText;
    public $occurrenceId;

    public function __construct($subjectText, $messageText, $occurrenceId)
    {
        $this->subjectText = $subjectText;
        $this->messageText = $messageText;
        $this->occurrenceId = $occurrenceId;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->markdown('emails.escalation');
    }
}

