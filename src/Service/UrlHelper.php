<?php

namespace App\Service;

use App\Entity\Site;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class UrlHelper
{
    /**
     * @var ArrayCollection
     */
    private $oldUrls;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UrlHelper constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->oldUrls = new ArrayCollection();
        $this->em      = $em;
    }

    /**
     * @param Site $site
     */
    public function setOldUrls(Site $site)
    {
        foreach ($site->getUrls() as $url) {
            $this->oldUrls->add($url);
        }
    }

    /**
     * @param Site $site
     */
    public function checkData(Site $site)
    {
        $this->checkRemoved($site);
    }

    /**
     * @param Site $site
     */
    private function checkRemoved(Site $site)
    {
        foreach ($this->oldUrls as $url) {
            if (false === $site->getUrls()->contains($url)) {
                $site->getUrls()->removeElement($url);
                $this->em->remove($url);
            }
        }
    }
}