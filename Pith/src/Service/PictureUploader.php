<?php

namespace App\Service;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureUploader
{
    /**
     * Move a received file into a form
     *
     * @param Form $form The form from which to extract the picture field
     * @param string $fieldName The name of the control containing the picture * file
     * @return string The new file name
     */
    public function upload(Form $form, string $fieldName, string $fileName = null)
    {
        
        $pictureFile = $form->get($fieldName)->getData();

        if ($pictureFile !== null) {
            
            $newFileName = $fileName ?? $this->createFileName($pictureFile);
            
            $pictureFile->move($_ENV['PICTURES_DIRECTORY'], $newFileName);
            
            return $newFileName;
        }

        return $fileName;
    }

    public function createFileName(UploadedFile $file)
    {
        return uniqid() . '.' .$file->guessClientExtension();
    }
}