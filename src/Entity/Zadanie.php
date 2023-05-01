<?php

namespace App\Entity;

use App\Repository\ZadanieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZadanieRepository::class)]
class Zadanie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nazwa = null;

    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $opis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'zadania')]
    private ?Pracownik $pracownik_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOpis(): ?string
    {
        return $this->opis;
    }

    public function setOpis(?string $opis): self
    {
        $this->opis = $opis;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPracownikId(): ?Pracownik
    {
        return $this->pracownik_id;
    }

    public function setPracownikId(?Pracownik $pracownik_id): self
    {
        $this->pracownik_id = $pracownik_id;

        return $this;
    }
}
