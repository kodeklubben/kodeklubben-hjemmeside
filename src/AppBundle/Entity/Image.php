<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Club;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Club.
 *
 * @ORM\Table(name="image", uniqueConstraints= {
 *      @ORM\UniqueConstraint(name="club_name_idx", columns={"club_id", "name"})
 * })
 * @UniqueEntity({"club", "name"})
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Club", cascade={"persist"})
     * @Assert\Valid
     */
    private $club;

    /**
     * @var UploadedFile
     *
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" })
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $filePath;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return Club
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @param Club $club
     */
    public function setClub($club)
    {
        $this->club = $club;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }
    
    /**
     * @return Image
     */
    public static function getPlaceholderImage(Club $club, $name = null)
    {
        $image = new Image();
        $image->setName($name ?$name :"placeholder");
        $image->setClub($club);
        $image->setFileName(self::getPlaceholderFilename());
        $image->setFilePath(self::getPlaceholderPath());
        $image->setFile(new File(self::getPlaceholderPath()));
        return $image;
    }
    private static function getPlaceholderPath()
    {
        # Workaround for tests which are run inside web/
        if(file_exists("web")){
            return "web/img/club/default/placeholder.svg";
        } else {
            return "img/club/default/placeholder.svg";
        }
    }
    private static function getPlaceholderFilename()
    {
        return preg_replace("_^.*/([^/]*)\$_", '\1', self::getPlaceholderPath());
    }
}
