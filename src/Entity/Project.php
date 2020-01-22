<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="4", minMessage="Le nom ne pas être inférieur à {{ limit }} caractères")
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     *     maxSize="2M",
     *     maxSizeMessage="La taille du fichier doit être inférieur à {{ limit }}{{ suffix}}",
     *     mimeTypes={"image/jpeg", "image/png"},
     *     mimeTypesMessage="Les types de fichier authorisés sont jpeg ou png"
     * )
     */
    private $files;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $info_short;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info_long;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="project")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

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

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getInfoShort(): ?string
    {
        return $this->info_short;
    }

    public function setInfoShort(?string $info_short): self
    {
        $this->info_short = $info_short;

        return $this;
    }

    public function getInfoLong(): ?string
    {
        return $this->info_long;
    }

    public function setInfoLong(?string $info_long): self
    {
        $this->info_long = $info_long;

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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }
}
