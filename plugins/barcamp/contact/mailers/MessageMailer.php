<?php namespace Barcamp\Contact\Mailers;

use Config;
use Mail;
use Barcamp\Contact\Models\Message;

class MessageMailer
{
	/**
	 * Send contact form.
     *
	 * @param Message $message
	 */
	public static function send(Message $message)
	{
		$recipient = new \stdClass();
		$recipient->email = Config::get('barcamp.contact::config.recipients.to.email');
		$recipient->name = Config::get('barcamp.contact::config.recipients.to.email');
        $recipient->bcc_email = Config::get('barcamp.contact::config.recipients.bcc.email');
        $recipient->bcc_name = Config::get('barcamp.contact::config.recipients.bcc.name');

		$template = 'barcamp.contact::mail.contact';

		$templateParameters = [
			'stranky' => Config::get('app.url'),
			'zprava' => $message,
		];

		Mail::send($template, $templateParameters, function($message) use ($recipient)
		{
			$message->to($recipient->email, $recipient->name);

            if ($recipient->bcc_email && $recipient->bcc_name) {
                $message->bcc($recipient->bcc_email, $recipient->bcc_name);
            }
		});
	}
}
