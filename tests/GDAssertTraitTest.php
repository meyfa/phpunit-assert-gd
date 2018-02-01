<?php

use PHPUnit\Framework\TestCase;

use AssertGD\GDAssertTrait;

/**
 * @SuppressWarnings(PHPMD)
 */
class GDAssertTraitTest extends TestCase
{
    use GDAssertTrait;

    public function testSamePath()
    {
        // should compare successfully
        $this->assertSimilarGD('./tests/images/stripes-bw-10x10.png',
            './tests/images/stripes-bw-10x10.png');
    }

    public function testDifferentDimensions()
    {
        // should compare unsuccessfully
        $this->assertNotSimilarGD('./tests/images/stripes-bw-10x10.png',
            './tests/images/stripes-bw-20x20.png');
    }

    public function testDifferentImages()
    {
        // should compare unsuccessfully
        $this->assertNotSimilarGD('./tests/images/stripes-bw-10x10.png',
            './tests/images/stripes-bw-10x10-alt.png');
    }

    public function testDifferentImagesThreshold1()
    {
        // should compare successfully
        $this->assertSimilarGD('./tests/images/stripes-bw-10x10.png',
            './tests/images/stripes-bw-10x10-alt.png', '', 1);
    }

    public function testJpeg()
    {
        // should compare unsuccessfully with threshold = 0.01
        $this->assertNotSimilarGD('./tests/images/jpeg.jpg',
            './tests/images/jpeg-alt.jpg', '', 0.01);

        // should compare successfully with threshold = 0.1
        $this->assertSimilarGD('./tests/images/jpeg.jpg',
            './tests/images/jpeg-alt.jpg', '', 0.1);
    }
}
