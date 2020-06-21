<?php

namespace App\Event;

use App\Service\Sender\DiscordSender;
use App\Service\Sender\RapportUpdater;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RapportSubscriber implements EventSubscriberInterface
{
    /**
     * @var DiscordSender
     */
    private $discordSender;

    /**
     * @var RapportUpdater
     */
    private $rapportUpdater;

    /**
     * RapportSubscriber constructor.
     * @param DiscordSender  $discordSender
     * @param RapportUpdater $rapportUpdater
     */
    public function __construct(
        DiscordSender $discordSender,
        RapportUpdater $rapportUpdater
    )
    {
        $this->discordSender  = $discordSender;
        $this->rapportUpdater = $rapportUpdater;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            RapportSendEvent::NAME => 'rapportSend',
        ];
    }

    /**
     * @param RapportSendEvent $event
     */
    public function rapportSend(RapportSendEvent $event)
    {
        if (count($event->getRapports()) === 0) {
            return;
        }
        $this->discordSender->send($event->getRapports());
//        $this->rapportUpdater->update($event->getRapports());
    }
}