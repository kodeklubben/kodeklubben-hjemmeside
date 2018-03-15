<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;

class ImageUploader
{
    protected $manager;
    protected $imageDir;

    public function __construct(EntityManager $manager, $imageDir)
    {
        $this->manager = $manager;
        $this->imageDir = $imageDir;
    }

    /**
     * @param Image $image
     *
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
        $directory = $this->imageDir.'/club/'.$image->getClub()->getId();
        $file->move($directory, $fileName);

        // Update image with new file properties
        $image->setFileName($fileName);
        $image->setFilePath('/img/club/'.$image->getClub()->getId().'/'.$fileName);

        // Persist image to database
        $this->manager->persist($image);
        $this->manager->flush();

        // Image directory + everything after '/img/' in oldFilePath
        $absPathToOldFile = $this->imageDir.substr($oldFilePath, 5);

        // Remove old image if it is not a default image
        $isDefaultImage = strpos($oldFilePath, 'default') !== false;
        $fs = new Filesystem();
        if ($fs->exists($absPathToOldFile) && !$isDefaultImage && strlen($absPathToOldFile) > strlen($this->imageDir)) {
            $fs->remove($absPathToOldFile);
        }

        return $image;
    }

    public function setDefaultCourseTypeImage(Image $image)
    {
        $image->setFilePath('http://placehold.it/200x200');
        $image->setFileName('200x200');
    }
}
