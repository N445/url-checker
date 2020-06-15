<?php

namespace App\Service\Sender;

use App\Event\RapportSendEvent;
use App\Repository\RapportRepository;
use App\Utils\Rapport\ErrorLevel;
use Doctrine\ORM\EntityManagerInterface;
use N445\EasyDiscord\Model\Embed;
use N445\EasyDiscord\Model\Field;
use N445\EasyDiscord\Model\Message;
use N445\EasyDiscord\Service\DiscordSender;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RapportSender
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Rapport[]
     */
    private $rapportsToSend;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * RapportSender constructor.
     * @param EntityManagerInterface   $em
     * @param RapportRepository        $rapportRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $em,
        RapportRepository $rapportRepository,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->em              = $em;
        $this->rapportsToSend  = $rapportRepository->getUnsendRapport();
        $this->eventDispatcher = $eventDispatcher;
    }

    public function sendRapport()
    {
        $this->eventDispatcher->dispatch(new RapportSendEvent($this->rapportsToSend), RapportSendEvent::NAME);
        $discord = (new DiscordSender())->addIdToken('721354431270092871', 'rUK71iWIExWutA6-S2iWVPfR9feqQroDauZdQ71aNtGJzk4U0-4uPNaSTduzF8-xXyDJ');
        $embed   = (new Embed())->setTitle('Liste des erreurs')->setDescription('rien');
        foreach ($this->rapportsToSend as $rapport) {
            $field = (new Field())
                ->setName($rapport->getUrl()->getSite()->getDomain() . $rapport->getUrl()->getUrl())
                ->setValue(ErrorLevel::getErrorCodeLabel($rapport->getErrorCode()))
            ;
            $embed->addField($field);
        }
        $message = (new Message())->setUsername('URL Checker')->addEmbed($embed);
        $discord->send($message);
    }
}