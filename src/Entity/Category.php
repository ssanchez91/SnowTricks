<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="`category`")
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Figure::class, mappedBy="category")
     */
    private $figures;

    public function __construct()
    {
        $this->figures = new ArrayCollection();
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
     * @return Collection|Figure[]
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    public function addFigure(Figure $figure): self
    {
        if (!$this->figures->contains($figure)) {
            $this->figures[] = $figure;
            $figure->setCategory($this);
        }

        return $this;
    }

    public function removeFigure(Figure $figure): self
    {
        if ($this->figures->contains($figure)) {
            $this->figures->removeElement($figure);
            // set the owning side to null (unless already changed)
            if ($figure->getCategory() === $this) {
                $figure->setCategory(null);
            }
        }

        return $this;
    }
}
