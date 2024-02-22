<?php

namespace AssertGD;

use AssertGD\DiffCalculator\RgbaChannels;
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
     * @var DiffCalculator The difference calculator to compare the images with.
     */
    private $diffCalculator;

    /**
     * Constructs a new constraint. A threshold of 0 means only exactly equal
     * images are allowed, while a threshold of 1 matches every image.
     *
     * @param string|resource $expected  File name or resource to match against.
     * @param float           $threshold Error threshold between 0 and 1.
     * @param DiffCalculator|null     $diffCalculator The difference calculator to use.
     */
    public function __construct($expected, $threshold = 0, $diffCalculator = null)
    {
        $this->expected = $expected;
        $this->threshold = $threshold;
        $this->diffCalculator = $diffCalculator ?? new RgbaChannels();
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString(): string
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
    public function matches($other): bool
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
                $delta += $this->diffCalculator->calculate($imgExpec, $imgOther, $x, $y);
            }
        }

        $imgExpec->finish();
        $imgOther->finish();

        // error is in range 0..1
        $error = $delta / ($w * $h);

        return $error <= $this->threshold;
    }
}
