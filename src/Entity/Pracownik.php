<?php

namespace App\Entity;

use App\Repository\PracownikRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PracownikRepository::class)]
class Pracownik
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nazwisko = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stanowisko = null;

    #[ORM\Column(nullable: true)]
    private ?float $wynagrodzenie = null;

    #[ORM\ManyToOne(inversedBy: 'pracownicy')]
    private ?Zespol $zespo_id = null;

    #[ORM\OneToMany(mappedBy: 'pracownik_id', targetEntity: Zadanie::class)]
    private Collection $zadania;

    public function __construct()
    {
        $this->zadania = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImie(): ?string
    {
        return $this->imie;
    }

    public function setImie(?string $imie): self
    {
        $this->imie = $imie;

        return $this;
    }

    public function getNazwisko(): ?string
    {
        return $this->nazwisko;
    }

    public function setNazwisko(?string $nazwisko): self
    {
        $this->nazwisko = $nazwisko;

        return $this;
    }

    public function getStanowisko(): ?string
    {
        return $this->stanowisko;
    }

    public function setStanowisko(?string $stanowisko): self
    {
        $this->stanowisko = $stanowisko;

        return $this;
    }

    public function getWynagrodzenie(): ?float
    {
        return $this->wynagrodzenie;
    }

    public function setWynagrodzenie(?float $wynagrodzenie): self
    {
        $this->wynagrodzenie = $wynagrodzenie;

        return $this;
    }

    public function getZespoId(): ?Zespol
    {
        return $this->zespo_id;
    }

    public function setZespoId(?Zespol $zespo_id): self
    {
        $this->zespo_id = $zespo_id;

        return $this;
    }

    /**
     * @return Collection<int, Zadanie>
     */
    public function getZadania(): Collection
    {
        return $this->zadania;
    }

    public function addZadanium(Zadanie $zadanium): self
    {
        if (!$this->zadania->contains($zadanium)) {
            $this->zadania->add($zadanium);
            $zadanium->setPracownikId($this);
        }

        return $this;
    }

    public function removeZadanium(Zadanie $zadanium): self
    {
        if ($this->zadania->removeElement($zadanium)) {
            // set the owning side to null (unless already changed)
            if ($zadanium->getPracownikId() === $this) {
                $zadanium->setPracownikId(null);
            }
        }

        return $this;
    }

    public function getImieiNazwisko(): string
    {
        return $this->getImie()." ".$this->getNazwisko();
    }

    public function __toString(): string
    {
        return $this->getImie()." ".$this->getNazwisko();
    }


}
