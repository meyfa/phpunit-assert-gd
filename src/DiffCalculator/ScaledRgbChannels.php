<?php

namespace AssertGD\DiffCalculator;

use AssertGD\DiffCalculator;
use AssertGD\GDImage;

/**
 * Calculate the difference between two pixels using the RGB channels, and scales down the RGB difference by the alpha
 * channel.
 *
 * This calculation will pre-multiply the RGB channels by the opacity percentage (alpha) of the pixel, meaning that a
 * translucent pixel will have less of an impact on the overall difference than an opaque pixel. For transparent pixels,
 * this will mean that the RGB difference will be scaled down to zero, effectively meaning that transparent pixels will
 * match regardless of their RGB values.
 *
 * This calculation method is useful for images with transparent pixels or images that have been anti-aliased or
 * blurred over a transparent background, effectively making translucent pixels less likely to cause a false positive as
 * being different.
 */
class ScaledRgbChannels implements DiffCalculator
{
    public function calculate(GDImage $imageA, GDImage $imageB, int $pixelX, int $pixelY): float
    {
        $pixelA = $this->premultiply($imageA->getPixel($pixelX, $pixelY));
        $pixelB = $this->premultiply($imageB->getPixel($pixelX, $pixelY));

        $diffR = abs($pixelA['red'] - $pixelB['red']) / 255;
        $diffG = abs($pixelA['green'] - $pixelB['green']) / 255;
        $diffB = abs($pixelA['blue'] - $pixelB['blue']) / 255;

        return ($diffR + $diffG + $diffB) / 4;
    }

    protected function premultiply(array $pixel)
    {
        $alpha = 1 - ($pixel['alpha'] / 127);

        return [
            'red' => $pixel['red'] * $alpha,
            'green' => $pixel['green'] * $alpha,
            'blue' => $pixel['blue'] * $alpha,
        ];
    }
}
