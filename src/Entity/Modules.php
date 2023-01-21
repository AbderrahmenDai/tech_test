<?php

namespace App\Entity;

use App\Repository\ModulesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModulesRepository::class)]
class Modules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'modules', targetEntity: Functions::class)]
    private Collection $functions;

    #[ORM\ManyToMany(targetEntity: Access::class, inversedBy: 'modules')]
    private Collection $accesses;

    #[ORM\Column(length: 255)]
    private ?string $accessRule = null;

    public function __construct()
    {
        $this->functions = new ArrayCollection();
        $this->accesses = new ArrayCollection();
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

    /**
     * @return Collection<int, Functions>
     */
    public function getFunctions(): Collection
    {
        return $this->functions;
    }

    public function addFunction(Functions $function): self
    {
        if (!$this->functions->contains($function)) {
            $this->functions->add($function);
            $function->setModules($this);
        }

        return $this;
    }

    public function removeFunction(Functions $function): self
    {

        if ($this->functions->contains($function)) {
            $this->functions->removeElement($function);
            // set the owning side to null (unless already changed)
            if ($function->getModule() === $this) {
            $function->setModule(null);
    }
}

        return $this;
    }

    /**
     * @return Collection<int, Access>
     */
    public function getAccesses(): Collection
    {
        return $this->accesses;
    }

    public function addAccess(Access $access): self
    {
        if (!$this->accesses->contains($access)) {
            $this->accesses->add($access);
        }

        return $this;
    }

    public function removeAccess(Access $access): self
    {
        $this->accesses->removeElement($access);

        return $this;
    }

    public function getAccessRule(): ?string
    {
        return $this->accessRule;
    }

    public function setAccessRule(string $accessRule): self
    {
        $this->accessRule = $accessRule;

        return $this;
    }
}
