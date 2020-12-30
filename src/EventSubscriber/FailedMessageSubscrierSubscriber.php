<?php

namespace App\EventSubscriber;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;

class FailedMessageSubscrierSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function onWorkerMessageFailedEvent(WorkerMessageFailedEvent $event)
    {
        $message = get_class($event->getEnvelope()->getMessage());

        $email = (new Email())
            ->from('noreply@first-api.dev')
            ->to('admin@first-api.dev')
            ->priority(Email::PRIORITY_NORMAL)
            ->subject('A new book has been added')
            ->html(sprintf('Une erreur est survenue lors du traitement d\'une tache: #%d', $message));

        $this->mailer->send($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            WorkerMessageFailedEvent::class => 'onWorkerMessageFailedEvent',
        ];
    }
}
