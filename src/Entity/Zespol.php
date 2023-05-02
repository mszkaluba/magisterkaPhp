<?php

namespace App\Entity;

use App\Repository\ZespolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZespolRepository::class)]
class Zespol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $maksymalna_ilosc_pracownikow = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nazwa = null;

    #[ORM\OneToOne(mappedBy: 'zespo_id', targetEntity: Pracownik::class)]
    private ?Pracownik $szef_zespolu = null;

    #[ORM\OneToMany(mappedBy: 'zespo_id', targetEntity: Pracownik::class)]
    private Collection $pracownicy;

    public function __construct()
    {
        $this->pracownicy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaksymalnaIloscPracownikow(): ?int
    {
        return $this->maksymalna_ilosc_pracownikow;
    }

    public function setMaksymalnaIloscPracownikow(?int $maksymalna_ilosc_pracownikow): self
    {
        $this->maksymalna_ilosc_pracownikow = $maksymalna_ilosc_pracownikow;

        return $this;
    }

    public function getNazwa(): ?string
    {
        return $this->nazwa;
    }

    public function setNazwa(?string $nazwa): self
    {
        $this->nazwa = $nazwa;

        return $this;
    }

    public function getSzefZespolu(): ?Pracownik
    {
        return $this->szef_zespolu;
    }

    public function setSzefZespolu(?Pracownik $szef_zespolu): self
    {
        $this->szef_zespolu = $szef_zespolu;

        return $this;
    }

    /**
     * @return Collection<int, Pracownik>
     */
    public function getPracownicy(): Collection
    {
        return $this->pracownicy;
    }

    public function addPracownicy(Pracownik $pracownicy): self
    {
        if (!$this->pracownicy->contains($pracownicy)) {
            $this->pracownicy->add($pracownicy);
            $pracownicy->setZespoId($this);
        }

        return $this;
    }

    public function removePracownicy(Pracownik $pracownicy): self
    {
        if ($this->pracownicy->removeElement($pracownicy)) {
            // set the owning side to null (unless already changed)
            if ($pracownicy->getZespoId() === $this) {
                $pracownicy->setZespoId(null);
            }
        }

        return $this;
    }
}
