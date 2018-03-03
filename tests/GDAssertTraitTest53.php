 <?php

use PHPUnit\Framework\TestCase;

use AssertGD\GDSimilarityConstraint;

/**
 * @SuppressWarnings(PHPMD)
 */
class GDAssertTraitTest extends TestCase
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
            $this->logicalNot($this->equalTo(new GDSimilarityConstraint('./tests/images/stripes-bw-20x20.png'))));
    }

    public function testDifferentImages()
    {
        // should compare unsuccessfully
        $this->assertThat('./tests/images/stripes-bw-10x10.png',
            $this->logicalNot($this->equalTo(new GDSimilarityConstraint('./tests/images/stripes-bw-10x10-alt.png'))));
    }

    public function testDifferentImagesThreshold1()
    {
        // should compare successfully
        $this->assertThat('./tests/images/stripes-bw-10x10.png',
            new GDSimilarityConstraint('./tests/images/stripes-bw-10x10-alt.png'), '', 1);
    }

    public function testJpeg()
    {
        // should compare unsuccessfully with threshold = 0.01
        $this->assertThat('./tests/images/jpeg.jpg',
        $this->logicalNot($this->equalTo(new GDSimilarityConstraint('./tests/images/jpeg-alt.jpg', '', 0.01))));

        // should compare successfully with threshold = 0.1
        $this->assertThat('./tests/images/jpeg.jpg',
            new GDSimilarityConstraint('./tests/images/jpeg-alt.jpg'), '', 0.1);
    }
}
