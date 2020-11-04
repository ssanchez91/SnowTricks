<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FigureRepository::class)
 * @UniqueEntity(fields={"name"}, message="There is already an trick with this name")
 */
class Figure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("tricks:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("tricks:read")
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("tricks:read")
     * @Assert\NotBlank(message="You must add a name", groups={"personal_error"})
     * @Assert\Length(
     *     min=3,
     *     max=30,
     *     minMessage="The name should contain at least {{ limit }} characters",
     *     maxMessage="The name should not contain more than {{ limit }} characters",
     *     allowEmptyString=false,
     *     groups={"personal_error"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups("tricks:read")
     * @Assert\NotBlank(message="You must add a description")
     * @Assert\Length(
     *     min=30,
     *     minMessage="The description should contain at least {{ limit }} characters",
     *     allowEmptyString=false,
     *     groups={"personal_error"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("tricks:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("tricks:read")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("tricks:read")
     *
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("tricks:read")
     */
    private $user;

    /**
     *
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="figure", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $pictures;

    /**
     * 
     * @ORM\OneToMany(targetEntity=Movie::class, mappedBy="figure", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Groups("tricks:read")
     */
    private $movies;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="figure", cascade={"persist", "remove"})
     * @Groups("tricks:read")
     */
    private $comments;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->movies = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
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

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setFigure($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getFigure() === $this) {
                $picture->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->setFigure($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
            // set the owning side to null (unless already changed)
            if ($movie->getFigure() === $this) {
                $movie->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFigure($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFigure() === $this) {
                $comment->setFigure(null);
            }
        }

        return $this;
    }
}
