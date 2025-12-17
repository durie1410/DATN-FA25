<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class Notifier
{
	public static function sendEmail(?string $toEmail, string $subject, string $body): void
	{
		if (!$toEmail) return;
		try {
			Mail::raw($body, function ($message) use ($toEmail, $subject) {
				$message->to($toEmail)->subject($subject);
			});
		} catch (\Throwable $e) {
			// swallow for MVP
		}
	}
}


