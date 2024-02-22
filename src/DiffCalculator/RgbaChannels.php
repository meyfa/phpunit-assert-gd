<?php

namespace AssertGD\DiffCalculator;

use AssertGD\DiffCalculator;
use AssertGD\GDImage;

/**
 * Calculate the difference between two pixels using the RGBA channels.
 *
 * This is the default calculation method used by the `AssertGD` package. It simply takes each individual channel and
 * compares the delta between the channel values of the two images.
 *
 * This works well for most images, but may not work for images with transparent pixels if the transparent pixels have
 * different RGB values.
 */
class RgbaChannels implements DiffCalculator
{
    public function calculate(GDImage $imageA, GDImage $imageB, int $pixelX, int $pixelY): float
    {
        $pixelA = $imageA->getPixel($pixelX, $pixelY);
        $pixelB = $imageB->getPixel($pixelX, $pixelY);

        $diffR = abs($pixelA['red'] - $pixelB['red']) / 255;
        $diffG = abs($pixelA['green'] - $pixelB['green']) / 255;
        $diffB = abs($pixelA['blue'] - $pixelB['blue']) / 255;
        $diffA = abs($pixelA['alpha'] - $pixelB['alpha']) / 127;

        return ($diffR + $diffG + $diffB + $diffA) / 4;
    }
}
