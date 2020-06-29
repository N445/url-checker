<?php

namespace App\Service\Sender;

use App\Event\RapportSendEvent;
use App\Repository\RapportRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RapportSender
{
    /**
     * @var RapportRepository
     */
    private $rapportRepository;

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

        $this->rapportRepository = $rapportRepository;
        $this->eventDispatcher   = $eventDispatcher;
    }

    public function sendRapport()
    {
        $this->eventDispatcher->dispatch(new RapportSendEvent($this->rapportRepository->getUnsendRapport()), RapportSendEvent::NAME);
    }
}