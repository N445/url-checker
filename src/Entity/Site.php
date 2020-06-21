<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use App\Utils\SiteProtocoles;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $domain;

    /**
     * @ORM\ManyToOne(targetEntity=Serveur::class, inversedBy="sites")
     */
    private $serveur;

    /**
     * @ORM\OneToMany(targetEntity=Url::class, mappedBy="site", cascade={"persist","remove"})
     */
    private $urls;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $protocol;

    /**
     * Site constructor.
     */
    public function __construct()
    {
        $this->urls      = new ArrayCollection();
        $this->createdAt = new \DateTime("NOW");
        $this->addUrl((new Url())->setUrl('/'));
        $this->setProtocol(SiteProtocoles::HTTP);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return Serveur|null
     */
    public function getServeur(): ?Serveur
    {
        return $this->serveur;
    }

    /**
     * @param Serveur|null $serveur
     * @return $this
     */
    public function setServeur(?Serveur $serveur): self
    {
        $this->serveur = $serveur;

        return $this;
    }

    /**
     * @return Collection|Url[]
     */
    public function getUrls(): Collection
    {
        return $this->urls;
    }

    /**
     * @param Url $url
     * @return $this
     */
    public function addUrl(Url $url): self
    {
        if (!$this->urls->contains($url)) {
            $this->urls[] = $url;
            $url->setSite($this);
        }

        return $this;
    }

    /**
     * @param Url $url
     * @return $this
     */
    public function removeUrl(Url $url): self
    {
        if ($this->urls->contains($url)) {
            $this->urls->removeElement($url);
            // set the owning side to null (unless already changed)
            if ($url->getSite() === $this) {
                $url->setSite(null);
            }
        }

        return $this;
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

    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }
}
