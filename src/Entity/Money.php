<?php

namespace App\Entity;

use App\Repository\MoneyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoneyRepository::class)]
class Money
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'money', targetEntity: Business::class, orphanRemoval: true)]
    private Collection $businesses;

    #[ORM\OneToMany(mappedBy: 'money', targetEntity: PriceListDetail::class, orphanRemoval: true)]
    private Collection $priceListDetails;

    public function __construct()
    {
        $this->businesses = new ArrayCollection();
        $this->priceListDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Business>
     */
    public function getBusinesses(): Collection
    {
        return $this->businesses;
    }

    public function addBusiness(Business $business): self
    {
        if (!$this->businesses->contains($business)) {
            $this->businesses->add($business);
            $business->setMoney($this);
        }

        return $this;
    }

    public function removeBusiness(Business $business): self
    {
        if ($this->businesses->removeElement($business)) {
            // set the owning side to null (unless already changed)
            if ($business->getMoney() === $this) {
                $business->setMoney(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PriceListDetail>
     */
    public function getPriceListDetails(): Collection
    {
        return $this->priceListDetails;
    }

    public function addPriceListDetail(PriceListDetail $priceListDetail): static
    {
        if (!$this->priceListDetails->contains($priceListDetail)) {
            $this->priceListDetails->add($priceListDetail);
            $priceListDetail->setMoney($this);
        }

        return $this;
    }

    public function removePriceListDetail(PriceListDetail $priceListDetail): static
    {
        if ($this->priceListDetails->removeElement($priceListDetail)) {
            // set the owning side to null (unless already changed)
            if ($priceListDetail->getMoney() === $this) {
                $priceListDetail->setMoney(null);
            }
        }

        return $this;
    }
}
