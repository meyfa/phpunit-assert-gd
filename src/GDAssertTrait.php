<?php

namespace AssertGD;

use AssertGD\DiffCalculator\RgbaChannels;

/**
 * Use this trait in a test case class to gain access to the image similarity
 * assertions.
 */
trait GDAssertTrait
{
    /**
     * @var DiffCalculator The difference calculator to compare the images with.
     */
    protected $diffCalculator;

    /**
     * Asserts that the difference between $expected and $actual is AT MOST
     * $threshold. $expected and $actual can be GD image resources or paths to
     * image files.
     *
     * A threshold value of 0 means only exactly equal images will be accepted,
     * while a value of 1 means all images will be accepted.
     *
     * This is the opposite to `assertNotSimilarGD`.
     *
     * @param string|resource $expected  The expected image.
     * @param string|resource $actual    The actual image.
     * @param string          $message   The failure message.
     * @param float           $threshold Error threshold between 0 and 1.
     * @param DiffCalculator|null $diffCalculator The difference calculator to use.
     *
     * @return void
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertSimilarGD($expected, $actual, $message = '', $threshold = 0, $diffCalculator = null)
    {
        $constraint = $this->isSimilarGD($expected, $threshold, $diffCalculator);
        $this->assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that the difference between $expected and $actual is MORE THAN
     * $threshold. $expected and $actual can be GD image resources or paths to
     * image files.
     *
     * This is the opposite to `assertSimilarGD`.
     *
     * @param string|resource $expected  The expected image.
     * @param string|resource $actual    The actual image.
     * @param string          $message   The failure message.
     * @param float           $threshold Error threshold between 0 and 1.
     * @param DiffCalculator|null $diffCalculator The difference calculator to use.
     *
     * @return void
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertNotSimilarGD($expected, $actual, $message = '', $threshold = 0, $diffCalculator = null)
    {
        $constraint = $this->logicalNot(
            $this->isSimilarGD($expected, $threshold, $diffCalculator)
        );
        $this->assertThat($actual, $constraint, $message);
    }

    /**
     * Creates a constraint for the expected image with the given threshold.
     * $expected can be a GD image resource or a path to an image file.
     *
     * A threshold value of 0 means only exactly equal images will be accepted,
     * while a value of 1 means all images will be accepted.
     *
     * @param string|resource $expected  The expected image.
     * @param float           $threshold Error threshold between 0 and 1.
     * @param DiffCalculator|null $diffCalculator The difference calculator to use.
     *
     * @return GDSimilarityConstraint The constraint.
     */
    public function isSimilarGD($expected, $threshold = 0, $diffCalculator = null)
    {
        $calc = isset($diffCalculator)
            ? $diffCalculator
            : (isset($this->diffCalculator) ? $this->diffCalculator : new RgbaChannels());
        return new GDSimilarityConstraint($expected, $threshold, $calc);
    }

    /**
     * Sets the difference calculator to use for image comparisons in this test case.
     *
     * @var DiffCalculator $diffCalculator
     */
    public function setDiffCalculator($diffCalculator)
    {
        if (!($diffCalculator instanceof DiffCalculator)) {
            throw new \InvalidArgumentException(
                'The difference calculator must implement the `AssertGD\DiffCalculator` interface'
            );
        }

        $this->diffCalculator = $diffCalculator;
    }
}
