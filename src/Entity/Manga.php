<?php

namespace App\Entity;

use App\Repository\MangaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=MangaRepository::class)
 */
class Manga
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"info_manga"})
     * @ORM\Column(type="string", length=255)
     */
    private $originalName;

    /**
     * @Groups({"info_manga"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duration;

    /**
     * @Groups({"info_manga"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $synopsis;

    /**
     * @Groups({"info_manga"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $season;

    /**
     * @Groups({"info_manga"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $background;

    /**
     * @Groups({"info_manga"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @Groups({"info_manga"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\OneToMany(targetEntity=Genre::class, mappedBy="manga", orphanRemoval=true)
     */
    private $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;
        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(?string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->setManga($this);
        }

        return $this;
    }

    public function removeGenre(genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            // set the owning side to null (unless already changed)
            if ($genre->getManga() === $this) {
                $genre->setManga(null);
            }
        }

        return $this;
    }

}
