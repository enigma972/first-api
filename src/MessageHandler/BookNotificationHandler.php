<?php
namespace App\MessageHandler;

use Symfony\Component\Mime\Email;
use App\Message\BookNotificationMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BookNotificationHandler implements MessageHandlerInterface
{
    private $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function __invoke(BookNotificationMessage $message)
    {
        $email = (new Email())
            ->from('no-reply@first-api.dev')
            ->to('admin@first-api.dev')
            ->priority(Email::PRIORITY_NORMAL)
            ->subject('A new book has been added')
            ->html(sprintf('The book #%d has been added.', $message->getBookId()));

        $this->mailer->send($email);
    }
}
