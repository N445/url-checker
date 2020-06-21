<?php

namespace App\Service\Sender;

use App\Event\RapportSendEvent;
use App\Repository\RapportRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RapportSender
{
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
     * @param RapportRepository        $rapportRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        RapportRepository $rapportRepository,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->rapportsToSend  = $rapportRepository->getUnsendRapport();
        $this->eventDispatcher = $eventDispatcher;
    }

    public function sendRapport()
    {
        $this->eventDispatcher->dispatch(new RapportSendEvent($this->rapportsToSend), RapportSendEvent::NAME);
    }
}