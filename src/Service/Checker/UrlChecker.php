<?php

namespace App\Service\Checker;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Service\Rapport\RapportCreator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use function GuzzleHttp\Promise\unwrap;

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
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $promises = [];


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
        $this->sites          = $siteRepository->getSites();
        $this->rapportCreator = $rapportCreator;
    }

    public function run()
    {
        $this->headers = [
            'User-Agent' => 'Url Checker/1.0',
        ];
        foreach ($this->sites as $site) {
            $client = new Client([
                'base_uri' => sprintf('%s://%s', $site->getProtocol(), $site->getDomain()),
                'options'  => [
                    'http_errors' => false,
                ],
            ]);
            $this->checkUrl($client, $site);
        }
        $results = unwrap($this->promises);
    }

    private function checkUrl(Client $client, Site $site)
    {
        foreach ($site->getUrls() as $key => $url) {
            $this->promises[] = ($client->getAsync(
                $url->getUrl(), [
                    RequestOptions::HEADERS => $this->headers,
                ]
            ))->then(
                function (Response $response) use ($url) {
                    $this->rapportCreator->create($url, $response, 0);
                },
                function ($exception) use ($url) {
                    $this->rapportCreator->create($url, null, 0, $exception);
                }
            );
        }
    }
}