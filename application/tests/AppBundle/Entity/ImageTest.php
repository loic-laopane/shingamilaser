<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 15:10
 */

namespace test\AppBundle\Entity;

use AppBundle\Entity\Image;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTest extends TestCase
{

    public function testGettersSetters()
    {
        $name = 'fichier.jpg';
        $dir = '/web/image';
        $filename = $dir.'/'.$name;
        $ext = 'jpg';
        $file = $this->createMock(UploadedFile::class);

        $image = new Image();
        $this->assertNull($image->getId());

        $this->assertEquals($image, $image->setName($name));
        $this->assertEquals($name, $image->getName());

        $this->assertEquals($image, $image->setDir($dir));
        $this->assertEquals($dir, $image->getDir());

        $this->assertEquals($image, $image->setExtension($ext));
        $this->assertEquals($ext, $image->getExtension());

        $this->assertEquals($image, $image->setFilename($filename));
        $this->assertEquals($filename, $image->getFilename());

        $this->assertEquals($image, $image->setTmpFilename($filename));
        $this->assertEquals($filename, $image->getTmpFilename());

        $this->assertEquals($image, $image->setFile($file));
        $this->assertEquals($file, $image->getFile());

        $this->assertRegExp('/web\/\img\/avatar$/', $image->getUploadedDir());

    }

}