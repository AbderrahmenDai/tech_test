<?php

namespace App\Entity;

use App\Repository\AccessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessRepository::class)]
class Access
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'accesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'accesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group = null;

    #[ORM\Column(length: 255)]
    private ?string $permission = null;

    #[ORM\ManyToMany(targetEntity: Modules::class, mappedBy: 'accesses')]
    private Collection $modules;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGroup(): ?Group
    {
    return $this->group;
    }

    public function setGroup(?Group $group): self
    {
    $this->group = $group;
    
    return $this;
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function setPermission(string $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @return Collection<int, Modules>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Modules $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
            $module->addAccess($this);
        }

        return $this;
    }

    public function removeModule(Modules $module): self
    {
        if ($this->modules->removeElement($module)) {
            $module->removeAccess($this);
        }

        return $this;
    }
}
