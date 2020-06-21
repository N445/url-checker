<?php

namespace App\Service\Import;

use App\Entity\Serveur;
use App\Entity\Site;
use App\Entity\Url;
use App\Model\Import;
use App\Repository\ServeurRepository;
use App\Repository\SiteRepository;
use App\Repository\UrlRepository;
use App\Utils\SiteProtocoles;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class Importator
{
    const SERVEUR  = 'serveur';
    const PROTOCOL = 'protocol';
    const SITE     = 'site';
    const URL      = 'url';
    const CODE     = 'code';

    /**
     * @var ServeurRepository
     */
    private $serveurRepository;

    /**
     * @var SiteRepository
     */
    private $siteRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UrlRepository
     */
    private $urlRepository;

    /**
     * @var Serveur[]
     */
    private $serveurs = [];

    /**
     * @var Site[]
     */
    private $sites = [];

    /**
     * @var Url[]
     */
    private $urls = [];


    /**
     * Importator constructor.
     * @param ServeurRepository      $serveurRepository
     * @param SiteRepository         $siteRepository
     * @param UrlRepository          $urlRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        ServeurRepository $serveurRepository,
        SiteRepository $siteRepository,
        UrlRepository $urlRepository,
        EntityManagerInterface $em
    )
    {
        $this->serveurRepository = $serveurRepository;
        $this->siteRepository    = $siteRepository;
        $this->urlRepository     = $urlRepository;
        $this->em                = $em;
    }

    public function import(Import $import)
    {
        $this->setInitialData();
        dump($this->serveurs);
        dump($this->sites);

        $csv = Reader::createFromPath($import->getFile()->getPathname(), 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset

//get 25 records starting from the 11th row
        $stmt = (new Statement())->limit(50);

        $records = $stmt->process($csv);
        $i       = 0;
        foreach ($records as $record) {
            $serveur = $this->getServeur($record);
            $site    = $this->getSite($record, $serveur);
            $this->createUrl($record, $site);
            if ($i % 200 == 0) {
                $this->em->flush();
            }
        }
        $this->em->flush();
    }

    private function setInitialData()
    {
        foreach ($this->serveurRepository->findAll() as $serveur) {
            $this->serveurs[$serveur->getIp()] = $serveur;
        }
        foreach ($this->siteRepository->findAll() as $site) {
            $this->sites[$site->getDomain()] = $site;
        }
        foreach ($this->urlRepository->findAll() as $url) {
            $this->urls[$url->getUrl()] = $url;
        }
    }

    /**
     * @param $record
     * @return Serveur
     */
    private function getServeur($record)
    {
        $serveurIp = $record[self::SERVEUR];
        if (array_key_exists($serveurIp, $this->serveurs)) {
            return $this->serveurs[$serveurIp];
        }
        $serveur                    = (new Serveur())
            ->setName($serveurIp)
            ->setIp($serveurIp)
        ;
        $this->serveurs[$serveurIp] = $serveur;
        $this->em->persist($serveur);
        return $serveur;
    }

    /**
     * @param         $record
     * @param Serveur $serveur
     * @return Site
     */
    private function getSite($record, Serveur $serveur)
    {
        $domain = $record[self::SITE];
        if (array_key_exists($domain, $this->sites)) {
            return $this->sites[$domain];
        }
        $site                 = (new Site())
            ->setProtocol($record[self::PROTOCOL] ?? SiteProtocoles::HTTP)
            ->setDomain($domain)
            ->setServeur($serveur)
        ;
        $this->sites[$domain] = $site;
        $this->em->persist($site);
        return $site;
    }

    /**
     * @param      $record
     * @param Site $site
     */
    private function createUrl($record, Site $site)
    {
        if ('/' === $record[self::URL]) {
            return;
        }
        $url = (new Url())
            ->setUrl($record[self::URL])
            ->setSite($site)
        ;
        if (!empty($record[self::CODE])) {
            $url->setCode($record[self::CODE]);
        }
        if ($this->isUrlExist($url)) {
            return;
        }
        $this->em->persist($url);
    }

    /**
     * @param Url $url
     * @return bool
     */
    private function isUrlExist(Url $url)
    {
        $site    = $url->getSite();
        $serveur = $site->getServeur();
        if (
            array_key_exists($serveur->getIp(), $this->serveurs) &&
            array_key_exists($site->getDomain(), $this->sites) &&
            array_key_exists($url->getUrl(), $this->urls)
        ) {
            return true;
        }
        return false;
    }
}