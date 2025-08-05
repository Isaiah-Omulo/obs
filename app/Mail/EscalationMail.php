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

    public function __construct($subjectText, $messageText)
    {
        $this->subjectText = $subjectText;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->markdown('emails.escalation')
                    ->with([
                        'messageText' => $this->messageText,
                    ]);
    }
}
