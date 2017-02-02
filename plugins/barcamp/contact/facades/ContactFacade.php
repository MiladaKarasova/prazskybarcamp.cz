<?php namespace Barcamp\Contact\Facades;

use App;
use Barcamp\Contact\Mailers\MessageMailer;
use Lang;
use Barcamp\Contact\Models\Message;
use October\Rain\Exception\ApplicationException;

/**
 * Facade ContactFacades.
 *
 * @package Barcamp\Contact\Facades
 */
class ContactFacade
{
    /** @var Message $message */
    private $message;

    /**
     * ContactFacade constructor.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Save message and send confirmation email.
     *
     * @param array $data
     *
     * @return Message|null
     *
     * @throws ApplicationException
     */
    public function storeMessage($data)
    {
        // check reservation sent limit
        if ($this->message->isExistInLastTime()) {
            throw new ApplicationException('Odeslání je povoleno pouze jednou za 30 sekund.');
        }

        // save message
        $message = $this->message->create($data);

        // send confirmation
        MessageMailer::send($message);

        return $message;
    }
}
