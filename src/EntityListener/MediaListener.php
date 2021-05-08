<?php

namespace App\EntityListener;

use App\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MediaListener.
 */
class MediaListener
{
    private string $uploadDir;

    private string $uploadAbsoluteDir;

    /**
     * MediaListener constructor.
     *
     * @param string $uploadDir
     * @param string $uploadAbsoluteDir
     */
    public function __construct(string $uploadDir, string $uploadAbsoluteDir)
    {
        $this->uploadDir = $uploadDir;
        $this->uploadAbsoluteDir = $uploadAbsoluteDir;
    }

    /**
     * @param Media $media
     */
    public function prePersist(Media $media): void
    {
        $this->upload($media);
    }

    /**
     * @param Media $media
     */
    public function preUpdate(Media $media): void
    {
        $this->upload($media);
    }

    /**
     * @param Media $media
     */
    public function upload(Media $media): void
    {
        if ($media->getFile() instanceof UploadedFile) {
            $filename = sprintf("%s.%s", bin2hex(random_bytes(6)), $media->getFile()->getClientOriginalExtension());
            $media->getFile()->move($this->uploadAbsoluteDir, $filename);
            $media->setPath(sprintf("%s/%s", $this->uploadDir, $filename));
        }
    }
}