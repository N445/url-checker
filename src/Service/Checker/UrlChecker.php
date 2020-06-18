<?php

namespace App\Service\Checker;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Service\Rapport\RapportCreator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UrlChecker
{
    /**
     * @var Site[]
     */
    private $sites;

    /**
     * @var RapportCreator
     */
    private $rapportCreator;

    /**
     * UrlChecker constructor.
     * @param SiteRepository $siteRepository
     * @param RapportCreator $rapportCreator
     */
    public function __construct(
        SiteRepository $siteRepository,
        RapportCreator $rapportCreator
    )
    {
        $this->sites          = $siteRepository->getAll();
        $this->rapportCreator = $rapportCreator;
    }

    public function run()
    {
        foreach ($this->sites as $site) {
            $client = new Client([
                'base_uri' => sprintf('http://%s', $site->getDomain()),
                'options'  => [
                    'http_errors' => false,
                ],
            ]);
//            dump($client);
//            die;

            $this->checkUrl($client, $site);
        }
    }

    private function checkUrl(Client $client, Site $site)
    {
        foreach ($site->getUrls() as $url) {
            try {
                $one      = microtime(1);
                $response = $client->get($url->getUrl());
//                $response = $client->getAsync($url->getUrl());
                $two      = microtime(1);
                $this->rapportCreator->create($url, $response, $two - $one);
            } catch (GuzzleException $e) {
                $this->rapportCreator->create($url, null, 0, $e);
            }
        }
    }
}