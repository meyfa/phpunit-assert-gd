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
     * If you provide an already-loaded image resource, it is YOUR job to
     * destroy the image when you no longer need it.
     * Resources loaded from a file will be destroyed by this class upon calling
     * finish().
     *
     * @param string|resource|\GdImage $value The image resource or file path.
     */
    public function __construct($value)
    {
        // PHP < 8 uses resources, PHP >= 8 uses GdImage objects.
        if (is_resource($value) || $value instanceof \GdImage) {
            $this->res = $value;
            return;
        }
        $this->res = imagecreatefromstring(file_get_contents($value));
        $this->destroy = true;
    }

    /**
     * Disposes of this image by calling `finish()`.
     */
    public function __destruct()
    {
        $this->finish();
    }

    /**
     * Free any allocated resources. This should be called as soon as the image
     * is no longer needed.
     *
     * @return void
     */
    public function finish()
    {
        if ($this->destroy && isset($this->res)) {
            imagedestroy($this->res);
        }
        $this->res = null;
    }

    /**
     * @return resource The underlying GD image resource.
     */
    public function getResource()
    {
        return $this->res;
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
