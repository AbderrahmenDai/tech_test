<?php

namespace App\Entity;

use App\Repository\FunctionsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Module;

#[ORM\Entity(repositoryClass: FunctionsRepository::class)]
class Functions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'functions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Modules $modules = null;

    #[ORM\Column(length: 255)]
    private ?string $moduleAccessRule = null;

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

    public function getModules(): ?Modules
    {
        return $this->modules;
    }

    public function setModules(?Modules $modules): self
    {
        $this->modules = $modules;

        return $this;
    }

    public function getModuleAccessRule(): ?string
    {
        return $this->moduleAccessRule;
    }

    public function setModuleAccessRule(string $moduleAccessRule): self
    {
        $this->moduleAccessRule = $moduleAccessRule;

        return $this;
    }
}
