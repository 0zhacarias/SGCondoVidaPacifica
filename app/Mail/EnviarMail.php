<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Inertia\Inertia;


class EnviarMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    
    public function build()
    {
      
        $data['assunto']=$this->subject('Mail from ItSolutionStuff.com');
        dd($data['assunto']);
        return Inertia::render('User/Tarefa', $data); 
        // return $this->subject('Mail from ItSolutionStuff.com')
        //             ->view('view.name');
    }
}
