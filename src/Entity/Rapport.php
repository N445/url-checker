<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RapportRepository::class)
 */
class Rapport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private $errorCode;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $responseTime;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Url::class, inversedBy="rapports")
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSend;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $error_message;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $sendAt;

    /**
     * Rapport constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime("NOW"));
        $this->setIsSend(false);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     * @return Rapport
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }


    /**
     * @return float|null
     */
    public function getResponseTime(): ?float
    {
        return $this->responseTime;
    }

    /**
     * @param float $responseTime
     * @return $this
     */
    public function setResponseTime(float $responseTime): self
    {
        $this->responseTime = $responseTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Rapport
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Url|null
     */
    public function getUrl(): ?Url
    {
        return $this->url;
    }

    /**
     * @param Url|null $url
     * @return $this
     */
    public function setUrl(?Url $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsSend(): ?bool
    {
        return $this->isSend;
    }

    /**
     * @param bool $isSend
     * @return $this
     */
    public function setIsSend(bool $isSend): self
    {
        $this->isSend = $isSend;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param mixed $error_message
     * @return Rapport
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }


    /**
     * @param \DateTime|null $sendAt
     * @return $this
     */
    public function setSendAt(?\DateTime $sendAt)
    {
        $this->sendAt = $sendAt;
        return $this;
    }
}
