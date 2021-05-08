<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @ORM\EntityListeners({"App\EntityListener\MediaListener"})
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UploadedFile|null
     * @Assert\Image
     */
    private ?UploadedFile $file = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="medias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

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

    /**
     * Get the value of file
     *
     * @return  UploadedFile|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @param  UploadedFile|null  $file
     *
     * @return  self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }
}