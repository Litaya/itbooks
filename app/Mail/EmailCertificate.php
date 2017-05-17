<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EmailCertificate extends Mailable
{
    use Queueable, SerializesModels;

	protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$token = $this->user->username.$this->user->email;
	    $id    = $this->user->id;
	    $token = "$id,".sha1($token);
        return $this->view('email.test')->with(['name'=>$this->user->username,'token'=>$token]);
    }
}
