<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $entity)
    {
        $extension = $file->guessExtension();
        $formats = ['jpg', 'png', 'svg'];

        if (in_array($extension, $formats)) {

            $fileName = $entity.'-'.uniqid().'-'.time().'.'.$file->guessExtension();

            try {
                $file->move($this->getTargetDirectory(), $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            return $fileName;
        }

        throw new \Exception('Le format de fichier n\'est pas valide');
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}