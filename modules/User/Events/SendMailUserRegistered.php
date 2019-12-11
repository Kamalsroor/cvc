<?php
namespace Modules\User\Events;

// use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
class SendMailUserRegistered
{
    use Queueable, SerializesModels;
    public $user;
    public $type;

    public function __construct($user , $type)
    {
        $this->user = $user;
        $this->type = $type;
    }


    // /**
    //  * Build the message.
    //  *
    //  * @return $this
    //  */
    // public function build()
    // {
    //     return $this->view('emails')->with([
    //         'email' => $this->user->email,
    //     ]);
    // }
}
