<?php

namespace App\Entity;

use App\Repository\WishRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: WishRepository::class)]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    #[Assert\Length(min: 5, max: 250, minMessage: 'Le titre de votre souhait doit dépasser 5 caractères', maxMessage: 'Le titre de votre souhait ne doit pas dépasser 250 caractères')]
    #[Assert\NotBlank([],'Le titre de votre souhait doit être saisi !')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length( min : 5, max : 5000, minMessage : "Minimum 5 characters please!", maxMessage : "Maximum 5000 characters please!")]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank([], 'Le nom de l\'auteur doit être saisi !')]
    #[Assert\Length( min : 3, max : 50, minMessage : "Minimum 3 characters please!", maxMessage : "Maximum 50 characters please!")]
    #[Assert\Regex(pattern : "/^[a-z0-9_-]+$/i", message : "Please use only letters, numbers, underscores and dashes!")]
    private ?string $author = null;

    #[ORM\Column]
    private ?bool $isPublished = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\ManyToOne(targetEntity : Category::class, inversedBy: 'wishes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

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
}
