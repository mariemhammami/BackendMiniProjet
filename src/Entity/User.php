<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=500)
     * @Serializer\Exclude()
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user")
     */
    private $usermessage;

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }


    public function __construct($username)
    { $this->roles=array('ROLE_ADMIN');
        $this->isActive = true;
        $this->username = $username;
        $this->usermessage = new ArrayCollection();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Message[]
     */
    public function getUsermessage(): Collection
    {
        return $this->usermessage;
    }

    public function addUsermessage(Message $usermessage): self
    {
        if (!$this->usermessage->contains($usermessage)) {
            $this->usermessage[] = $usermessage;
            $usermessage->setUser($this);
        }

        return $this;
    }

    public function removeUsermessage(Message $usermessage): self
    {
        if ($this->usermessage->contains($usermessage)) {
            $this->usermessage->removeElement($usermessage);
            // set the owning side to null (unless already changed)
            if ($usermessage->getUser() === $this) {
                $usermessage->setUser(null);
            }
        }

        return $this;
    }
}
