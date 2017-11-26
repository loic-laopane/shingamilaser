<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @var string
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=10)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="dir", type="string", length=255, nullable=true)
     */
    private $dir;

    /**
     * @var
     *
     */
    private $tmpFilename;

    /**
     * @var UploadedFile
     * @Assert\File()
     */
    private $file;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Image
     */
    public function setFilename($filename)
    {
       $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set dir
     *
     * @param string $dir
     *
     * @return Image
     */
    public function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get dir
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return Image
     */
    public function setFile(UploadedFile $file)
    {
        if(null !== $this->getFilename())
        {
            $this->setTmpFilename($this->getUploadedDir().'/'.$this->getFilename());
            $this->setFilename(null);
        }
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTmpFilename()
    {
        return $this->tmpFilename;
    }

    /**
     * @param mixed $tmpFilename
     * @return Image
     */
    public function setTmpFilename($tmpFilename)
    {
        $this->tmpFilename = $tmpFilename;
        return $this;
    }


    public function getUploadedDir()
    {
        return __DIR__.'/../../../web/img/avatar';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function hydrate()
    {
        $filename = uniqid().'.'.$this->file->guessExtension();
        $this->setName($this->file->getClientOriginalName());
        $this->setFilename($filename);
        $this->setExtension($this->file->guessExtension());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */

    public function upload()
    {
        $this->file->move($this->getUploadedDir(), $this->getFilename());

        if(null !== $this->getTmpFilename())
        {
            if(file_exists($this->getTmpFilename())) unlink($this->getTmpFilename());
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function unlink()
    {
        $fileToRemove = $this->getUploadedDir().'/'.$this->getFilename();
        if(file_exists($fileToRemove)) unlink($fileToRemove);
    }

}
