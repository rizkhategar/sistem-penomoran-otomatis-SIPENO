<?php

namespace App\Notifications;

use App\Models\LetterSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubmissionApproved extends Notification
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
            'letter_number' => $this->submission->letter_number,
            'letter_type' => $this->submission->letterType->name,
            'message' => 'Pengajuan surat ' . $this->submission->letterType->name . ' telah disetujui. Nomor surat: ' . $this->submission->letter_number,
        ];
    }
}
