<?php

namespace App\Service\Sender;

use App\Entity\Rapport;
use Doctrine\ORM\EntityManagerInterface;

class RapportUpdater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RapportUpdater constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Rapport[] $rapports
     */
    public function update(array $rapports)
    {
        foreach($rapports as $rapport){
            $rapport->setSendAt(new \DateTime("NOW"))->setIsSend(true);
            $this->em->persist($rapport);
        }
        $this->em->flush();
    }
}