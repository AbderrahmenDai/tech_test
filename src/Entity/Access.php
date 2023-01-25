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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'accesses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;
 
    private ?bool $canAccess = null;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'accesses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Group $group = null;

    #[ORM\Column(length: 255)]
    private ?string $permission = null;

    #[ORM\ManyToMany(targetEntity: Modules::class, mappedBy: 'accesses')]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $modules;

     #[ORM\ManyToMany(targetEntity: Functions::class, mappedBy: 'accesses')]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $functions;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->functions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

     /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAccessRight($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeAccessRight($this);
        }

        return $this;
    }

    public function getCanAccess(): bool
    {
        return this->canAccess;
    }

    public function setCanAccess(?bool $canAccess): self
    {
        return $this->canAccess = $canAccess;
    }

     /**
     * @return Collection|Group[]
     */
    public function getGroup(): collection
    {
    return $this->group;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addAccessRight($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeAccessRight($this);
        }

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

    /**
     * @return Collection<int, Functions>
    */
    public function getFunctions(): Collection
    {
         return $this->functions;
    }
    public function addFunction(Functions $function): self
    {
        if (!$this->functions->contains($functions)) {
            $this->functions->add($functions);
            $function->addAccess($this);
        }
        return $this;
    }
    public function removeFunctions(Functions $function): self
    {
        if ($this->functions->removeElement($function)) {
            $function->removeAccess($this);
        }
        return $this;
    }
}
