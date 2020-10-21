<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathLogo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Figure::class, mappedBy="user", orphanRemoval=true)
     */
    private $figures;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $enabled = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenAt;

    /**
     *
     */
    public function __construct()
    {
        $this->figures = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     *
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public
/**
 * @return string
 */
function getUsername(): string
    {
        return (string) $this->username;
    }

    public
/**
 * @param string $username
 * @return mixed
 */
function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public
/**
 * @return mixed
 */
function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public
/**
 * @param array $roles
 * @return mixed
 */
function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public
/**
 * @return string
 */
function getPassword(): string
    {
        return (string) $this->password;
    }

    public
/**
 * @param string $password
 * @return mixed
 */
function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public
/**
 *
 */
function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public
/**
 *
 */
function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public
/**
 *
 */
function getPathLogo(): ?string
    {
        return $this->pathLogo;
    }

    public /**
 * @param string $pathLogo
 * @return mixed
 */function setPathLogo(string $pathLogo): self
    {
        $this->pathLogo = $pathLogo;

        return $this;
    }

    public /**
 *
 */function getLastName(): ?string
    {
        return $this->lastName;
    }

    public /**
 * @param string $lastName
 * @return mixed
 */function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public /**
 *
 */function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public /**
 * @param string $firstName
 * @return mixed
 */function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public /**
 *
 */function getEmail(): ?string
    {
        return $this->email;
    }

    public /**
 * @param string $email
 * @return mixed
 */function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Figure[]
     */
    public /**
 * @return Collection
 */function getFigures(): Collection
    {
        return $this->figures;
    }

    public /**
 * @param Figure $figure
 * @return mixed
 */function addFigure(Figure $figure): self
    {
        if (!$this->figures->contains($figure)) {
            $this->figures[] = $figure;
            $figure->setUser($this);
        }

        return $this;
    }

    public /**
 * @param Figure $figure
 * @return mixed
 */function removeFigure(Figure $figure): self
    {
        if ($this->figures->contains($figure)) {
            $this->figures->removeElement($figure);
            // set the owning side to null (unless already changed)
            if ($figure->getUser() === $this) {
                $figure->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public /**
 * @return Collection
 */function getComments(): Collection
    {
        return $this->comments;
    }

    public /**
 * @param Comment $comment
 * @return mixed
 */function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public /**
 * @param Comment $comment
 * @return mixed
 */function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getTokenAt(): ?\DateTimeInterface
    {
        return $this->tokenAt;
    }

    public function setTokenAt(?\DateTimeInterface $tokenAt): self
    {
        $this->tokenAt = $tokenAt;

        return $this;
    }



}
