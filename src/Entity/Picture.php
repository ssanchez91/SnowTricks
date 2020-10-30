<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 */
class Picture
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
     *
     */
    private $path;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("tricks:read")
     */
    private $defaultPicture = false;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getDefaultPicture(): ?bool
    {
        return $this->defaultPicture;
    }

    public function setDefaultPicture(bool $defaultPicture): self
    {
        $this->defaultPicture = $defaultPicture;

        return $this;
    }

    public function getFigure(): ?Figure
    {
        return $this->figure;
    }

    public function setFigure(?Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }
}
