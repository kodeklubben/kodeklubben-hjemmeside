<?php

namespace ImageBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use ImageBundle\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;

class ImageUploader {
    protected $doctrine;
    protected $imageDir;
    
    public function __construct(Registry $doctrine, $imageDir){
        $this->doctrine = $doctrine;
        $this->imageDir = $imageDir;
    }

    /**
     * @param Image $image
     * @return Image
     */
    public function uploadImage(Image $image)
    {
        // Used to remove old image after new image is saved
        $oldFilePath = $image->getFilePath();

        // Get the file that was uploaded
        $file = $image->getFile();

        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Move the file to the directory where images are stored
        $directory = $this->imageDir . '/club/' . $image->getClub()->getId();
        $file->move($directory, $fileName);

        // Update image with new file properties
        $image->setFileName($fileName);
        $image->setFilePath('/img/club/' . $image->getClub()->getId() . '/' . $fileName);

        // Persist image to database
        $manager = $this->doctrine->getManager();
        $manager->persist($image);
        $manager->flush();

        // Remove old image if it is not the default image
        $isDefaultImage = strpos($oldFilePath, 'default') !== false;
        $fs = new Filesystem();
        if($fs->exists($oldFilePath) && !$isDefaultImage) {
            $fs->remove($oldFilePath);
        }
        
        return $image;
    }
}
