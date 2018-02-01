<?php

namespace AssertGD;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * PHPUnit constraint for checking that two GD image resources are similar.
 *
 * The similarity error is calculated as the average difference over all pixels.
 * E.g., for 5 pixels that differ by 10%, 20%, 5%, 0%, 0%, the error would be
 * (10% + 20% + 5% + 0% + 0%) / 5 = 7%.
 * A threshold of 0.07 would still match, while a threshold of 0.06 would not.
 */
class GDSimilarityConstraint extends Constraint
{
    private $expected;
    private $threshold;

    /**
     * Constructs a new constraint. A threshold of 0 means only exactly equal
     * images are allowed, while a threshold of 1 matches every image.
     *
     * @param string|resource $expected  File name or resource to match against.
     * @param float           $threshold Error threshold between 0 and 1.
     */
    public function __construct($expected, $threshold = 0)
    {
        parent::__construct();

        $this->expected = $expected;
        $this->threshold = $threshold;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf('is similar with threshold = <%F>', $this->threshold);
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     *
     * @return bool
     */
    public function matches($other)
    {
        $imgOther = new GDImage($other);
        $imgExpec = new GDImage($this->expected);

        $w = $imgExpec->getWidth();
        $h = $imgOther->getHeight();

        if ($w !== $imgOther->getWidth() || $h !== $imgOther->getHeight()) {
            return false;
        }

        // delta = sum of per-pixel differences
        $delta = 0;
        for ($x = 0; $x < $w; ++$x) {
            for ($y = 0; $y < $h; ++$y) {
                $delta += $this->getPixelError($imgExpec, $imgOther, $x, $y);
            }
        }

        $imgExpec->finish();
        $imgOther->finish();

        // error is in range 0..1
        $error = $delta / ($w * $h);

        return $error <= $this->threshold;
    }

    /**
     * Calculates the error between 0 and 1 (inclusive) of a specific pixel.
     *
     * @param GDImage $imgA The first image.
     * @param GDImage $imgB The second image.
     * @param int     $x    The pixel's x coordinate.
     * @param int     $y    The pixel's y coordinate.
     *
     * @return float The pixel error.
     */
    private function getPixelError(GDImage $imgA, GDImage $imgB, $x, $y)
    {
        $pixelA = $imgA->getPixel($x, $y);
        $pixelB = $imgB->getPixel($x, $y);

        $diffR = abs($pixelA['red'] - $pixelB['red']) / 255;
        $diffG = abs($pixelA['green'] - $pixelB['green']) / 255;
        $diffB = abs($pixelA['blue'] - $pixelB['blue']) / 255;
        $diffA = abs($pixelA['alpha'] - $pixelB['alpha']) / 127;

        return ($diffR + $diffG + $diffB + $diffA) / 4;
    }
}
