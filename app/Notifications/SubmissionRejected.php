<?php

namespace App\Notifications;

use App\Models\LetterSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubmissionRejected extends Notification
{
    use Queueable;

    public function __construct(public LetterSubmission $submission)
    {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'submission_id' => $this->submission->id,
            'alasan' => $this->submission->alasan_penolakan,
            'letter_type' => $this->submission->letterType->name,
            'message' => 'Pengajuan surat ' . $this->submission->letterType->name . ' ditolak. Alasan: ' . $this->submission->alasan_penolakan,
        ];
    }
}
