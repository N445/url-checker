<?php

namespace App\Event;

use App\Entity\Rapport;
use Symfony\Contracts\EventDispatcher\Event;

class RapportSendEvent extends Event
{
    public const NAME = 'rapport.send';

    /**
     * @var Rapport[]
     */
    protected $rapports;

    /**
     * RapportSendEvent constructor.
     * @param Rapport[] $rapports
     */
    public function __construct(array $rapports)
    {
        $this->rapports = $rapports;
    }

    /**
     * @return Rapport[]
     */
    public function getRapports(): array
    {
        return $this->rapports;
    }
}