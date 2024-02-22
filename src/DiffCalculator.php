<?php

namespace AssertGD;

use AssertGD\GDImage;

/**
 * Difference calculator.
 *
 * Determines the difference between two given images.
 */
interface DiffCalculator
{
    /**
     * Calculates the difference between two pixels at the given coordinates.
     *
     * This method will be provided with two `GDImage` objects representing the images being compared, and co-ordinates
     * of the pixel being compared.
     *
     * The method should return a float value between 0 and 1 inclusive, with 0 meaning that the pixels of both images
     * at the given co-ordinates are an exact match, and 1 meaning that the pixels are the complete opposite.
     */
    public function calculate(GDImage $imageA, GDImage $imageB, int $pixelX, int $pixelY): float;
}
