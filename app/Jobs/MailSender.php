<?php

namespace App\Jobs;

use App\Mail\EmailConfirmationMail;
use App\Mail\InvitationMail;
use App\Mail\SendTwoFactorCode;
use App\Models\EmailConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class MailSender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    protected $type;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(string $type, array $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        switch ($this->type) {
            case 'EmailConfirmation':
                $this->emailConfirmation($this->data);
                break;

            case 'Invitation':
                $this->sendInvitation($this->data);
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * Send a confirmation email to the user.
     *
     * @param array $data The data to send
     */
    public function emailConfirmation(array $data): void
    {
        $email = EmailConfirmation::create([
            'token' => Crypt::encrypt($data['user_id']),
        ]);

        Mail::to($data['email'])->send(new EmailConfirmationMail($email->token));
    }

    /**
     * Send invitation to another user.
     *
     * @param array $data The data to send
     */
    public function sendInvitation(array $data): void
    {
        $invitation = EmailConfirmation::create([
            'token' => Crypt::encrypt($data),
        ]);

        Mail::to($data['email'])->send(new InvitationMail($invitation->token, $data['full_name']));
    }
}
