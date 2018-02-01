<?php

use PHPUnit\Framework\TestCase;

use AssertGD\GDImage;

/**
 * @SuppressWarnings(PHPMD)
 */
class GDImageTest extends TestCase
{
    public function test__construct()
    {
        // should take a resource
        $img = imagecreatetruecolor(10, 20);
        $obj = new GDImage($img);
        $this->assertSame($img, $obj->getResource());
        imagedestroy($img);

        // should take a file path
        $obj = new GDImage('./tests/images/stripes-bw-10x10.png');
        $this->assertSame(10, imagesx($obj->getResource()));
        $obj->finish();
    }

    public function testGetWidth()
    {
        $img = imagecreatetruecolor(10, 20);
        $obj = new GDImage($img);

        $val = $obj->getWidth();
        imagedestroy($img);

        // should return the width
        $this->assertSame(10, $val);
    }

    public function testGetHeight()
    {
        $img = imagecreatetruecolor(10, 20);
        $obj = new GDImage($img);

        $val = $obj->getHeight();
        imagedestroy($img);

        // should return the height
        $this->assertSame(20, $val);
    }

    public function testFinish()
    {
        // should not delete provided resource
        $img = imagecreatetruecolor(10, 10);
        $obj = new GDImage($img);
        $obj->finish();
        imagesx($img); // fails if $img is deleted

        // should delete automatically loaded resource
        $obj = new GDImage('./tests/images/stripes-bw-10x10.png');
        $img = $obj->getResource();
        $obj->finish();
        try {
            imagesx($img);
        } catch (Exception $e) {
            return;
        }
        $this->fail();
    }
}
