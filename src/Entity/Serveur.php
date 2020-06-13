<?php

namespace App\Entity;

use App\Repository\ServeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServeurRepository::class)
 */
class Serveur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $ip;

    /**
     * @ORM\OneToMany(targetEntity=Site::class, mappedBy="serveur")
     */
    private $sites;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Serveur constructor.
     */
    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->createdAt = new \DateTime("NOW");
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return sprintf('%s (%s)', $this->getName(), $this->getIp());
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return Collection|Site[]
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    /**
     * @param Site $site
     * @return $this
     */
    public function addSite(Site $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setServeur($this);
        }

        return $this;
    }

    /**
     * @param Site $site
     * @return $this
     */
    public function removeSite(Site $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getServeur() === $this) {
                $site->setServeur(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
