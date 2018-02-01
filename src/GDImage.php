<?php

namespace AssertGD;

/**
 * Helper class for managing image resource loading, disposal, and pixel access.
 */
class GDImage
{
    private $res;
    private $destroy = false;

    /**
     * Constructs a new instance. Accepts either an image resource or a file
     * path to load the image from.
     *
     * @param string|resource $value The image resource or file path.
     */
    public function __construct($value)
    {
        if (!is_resource($value)) {
            $value = imagecreatefromstring(file_get_contents($value));
            $this->destroy = true;
        }
        $this->res = $value;
    }

    /**
     * @return resource The underlying GD image resource.
     */
    public function getResource()
    {
        return $this->res;
    }

    /**
     * Frees the allocated resource if it was loaded in the constructor. Will
     * not free the resource if it was passed already-loaded.
     *
     * @return void
     */
    public function finish()
    {
        if ($this->destroy) {
            imagedestroy($this->res);
        }
    }

    /**
     * Obtains the pixel components at the given coordinates.
     *
     * The return value is an associative array with keys 'red', 'green', 'blue'
     * and 'alpha'. All of them are in the range 0 - 255 (inclusive) except for
     * 'alpha' which is in the range 0 - 127.
     *
     * @param int $x The pixel's x coordinate.
     * @param int $y The pixel's y coordinate.
     *
     * @return int[] The pixel values.
     */
    public function getPixel($x, $y)
    {
        $index = imagecolorat($this->res, $x, $y);
        return imagecolorsforindex($this->res, $index);
    }

    /**
     * @return int The image's width.
     */
    public function getWidth()
    {
        return imagesx($this->res);
    }

    /**
     * @return int The image's height.
     */
    public function getHeight()
    {
        return imagesy($this->res);
    }
}
