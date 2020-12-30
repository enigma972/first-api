<?php

namespace App\EventSubscriber;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Message\BookNotificationMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookMailSubscriber implements EventSubscriberInterface
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }
    
    public function sendMail(ViewEvent $event)
    {
        $book = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$book instanceof Book || Request::METHOD_POST !== $method) {
            return;
        }
        $this->messageBus->dispatch(new BookNotificationMessage($book->getId()));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW  =>  ['sendMail', EventPriorities::POST_WRITE],
        ];
    }
}
