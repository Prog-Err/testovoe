<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    private ?string $fio = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $company = null;

    #[ORM\Column]
    private ?int $grop = null;

    #[ORM\Column]
    private ?bool $attend = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFio(): ?string
    {
        return $this->fio;
    }

    public function setFio(string $fio): static
    {
        $this->fio = $fio;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getGrop(): ?int
    {
        return $this->grop;
    }

    public function setGrop(int $grop): static
    {
        $this->grop = $grop;

        return $this;
    }

    public function isAttend(): ?bool
    {
        return $this->attend;
    }

    public function setAttend(bool $attend): static
    {
        $this->attend = $attend;

        return $this;
    }
}
