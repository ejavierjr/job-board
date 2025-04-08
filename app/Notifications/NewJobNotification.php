<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobNotification extends Notification
{
    use Queueable;

    public function __construct(public Job $job)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $approveUrl = route('moderator.approve', $this->job);
        $spamUrl = route('moderator.spam', $this->job);
        
        return (new MailMessage)
            ->subject('New Job Posting Requires Moderation')
            ->line('A new job has been posted by a first-time submitter.')
            ->line('Title: ' . $this->job->title)
            ->line('Description: ' . $this->job->description)
            ->line('Submitter Email: ' . $this->job->email)
            ->action('Approve Job', $approveUrl)
            ->action('Mark as Spam', $spamUrl);
    }
}
