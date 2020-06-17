<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Import
{
    /**
     * @var UploadedFile|null
     */
    private $file;

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile|null $file
     * @return Import
     */
    public function setFile(?UploadedFile $file): Import
    {
        $this->file = $file;
        return $this;
    }
}