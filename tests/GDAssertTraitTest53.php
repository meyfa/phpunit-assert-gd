<?php

use AssertGD\DiffCalculator\ScaledRgbChannels;
use PHPUnit\Framework\TestCase;

use AssertGD\GDSimilarityConstraint;

/**
 * @SuppressWarnings(PHPMD)
 */
class GDAssertTraitTest53 extends TestCase
{
    public function testSamePath()
    {
        // should compare successfully
        $this->assertThat('./tests/images/stripes-bw-10x10.png',
            new GDSimilarityConstraint('./tests/images/stripes-bw-10x10.png'));
    }

    public function testDifferentDimensions()
    {
        // should compare unsuccessfully
        $this->assertThat('./tests/images/stripes-bw-10x10.png',
            $this->logicalNot(new GDSimilarityConstraint('./tests/images/stripes-bw-20x20.png')));
    }

    public function testDifferentImages()
    {
        // should compare unsuccessfully
        $this->assertThat('./tests/images/stripes-bw-10x10.png',
            $this->logicalNot(new GDSimilarityConstraint('./tests/images/stripes-bw-10x10-alt.png')));
    }

    public function testDifferentImagesThreshold1()
    {
        // should compare successfully
        $this->assertThat('./tests/images/stripes-bw-10x10.png',
            new GDSimilarityConstraint('./tests/images/stripes-bw-10x10-alt.png', 1), '');
    }

    public function testJpeg()
    {
        // should compare unsuccessfully with threshold = 0.01
        $this->assertThat('./tests/images/jpeg.jpg',
            $this->logicalNot(new GDSimilarityConstraint('./tests/images/jpeg-alt.jpg', 0.01)), '');

        // should compare successfully with threshold = 0.1
        $this->assertThat('./tests/images/jpeg.jpg',
            new GDSimilarityConstraint('./tests/images/jpeg-alt.jpg', 0.1), '', 0.1);
    }

    public function testAlternativeDiffCalculator()
    {
        // the default method of calculating images will not consider these images exact due to the transparent pixels
        // having different RGB values
        $this->assertThat('./tests/images/transparent-black.gif',
            $this->logicalNot(new GDSimilarityConstraint('./tests/images/transparent-white.gif')));

        // using the ScaledRgbChannels diff calculator, the images will be considered exact
        $this->assertThat('./tests/images/transparent-black.gif',
            new GDSimilarityConstraint('./tests/images/transparent-white.gif', 0.0, new ScaledRgbChannels()));
    }
}
