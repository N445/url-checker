<?php

namespace App\Service\Rapport;

use App\Entity\Rapport;
use App\Entity\Url;
use App\Repository\RapportRepository;
use App\Utils\Rapport\ErrorLevel;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class RapportCreator
{
    const LIMIT_RESPONSE_TIME = 5; // en seconde

    /**
     * @var RapportRepository
     */
    private $rapportRepository;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var Response|null
     */
    private $response;

    /**
     * @var float|null
     */
    private $responseTime;

    /**
     * @var GuzzleException|null
     */
    private $guzzleException;

    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * RapportCreator constructor.
     * @param RapportRepository      $rapportRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RapportRepository $rapportRepository,
        EntityManagerInterface $em
    )
    {
        $this->rapportRepository = $rapportRepository;
        $this->em                = $em;
    }

    public function create(Url $url, Response $response = null, float $responseTime = null, GuzzleException $guzzleException = null)
    {
        $this->url             = $url;
        $this->response        = $response;
        $this->responseTime    = $responseTime;
        $this->guzzleException = $guzzleException;
        if (($code = $this->getErrorCode()) === ErrorLevel::NO_ERROR_CODE) {
            return;
        }

        $rapport = (new Rapport())
            ->setUrl($url)
            ->setErrorCode($code)
            ->setErrorMessage(ErrorLevel::getErrorMessage($code))
            ->setResponseTime($this->responseTime)
        ;

        // vérifie si un rapport existe déja avec la même url le meme code depuis 2h
        dump(count($this->rapportRepository->getLastSameRapport($rapport,new \DateTime('2 hours ago'))) > 0);
        if(count($this->rapportRepository->getLastSameRapport($rapport,new \DateTime('2 hours ago'))) > 0){
            return;
        }

        $this->em->persist($rapport);
        $this->em->flush();
    }

    private function getErrorCode()
    {
        if ($this->guzzleException) {
            return ErrorLevel::NO_RESPONSE_CODE;
        }
        if ($this->url->getCode() != $this->response->getStatusCode()) {
            return ErrorLevel::BAD_CODE_CODE;
        }
        if ($this->responseTime > self::LIMIT_RESPONSE_TIME) {
            return ErrorLevel::RESPONSE_TIME_CODE;
        }
        return ErrorLevel::NO_ERROR_CODE;
    }
}