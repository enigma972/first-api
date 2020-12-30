<?php

namespace App\EventSubscriber;

use App\Entity\Book;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookMailSubscriber implements EventSubscriberInterface
{
    private $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendMail(ViewEvent $event)
    {
        $book = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$book instanceof Book || Request::METHOD_POST !== $method) {
            return;
        }

        $message = (new Email())
            ->from('no-reply@first-api.dev')
            ->to('admin@first-api.dev')
            ->priority(Email::PRIORITY_NORMAL)
            ->subject('A new book has been added')
            ->html(sprintf('The book #%d has been added.', $book->getId()));

        $this->mailer->send($message);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW  =>  ['sendMail', EventPriorities::POST_WRITE],
        ];
    }
}
