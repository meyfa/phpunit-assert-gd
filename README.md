# AssertGD for PHPUnit

[![CI](https://github.com/meyfa/phpunit-assert-gd/actions/workflows/main.yml/badge.svg)](https://github.com/meyfa/phpunit-assert-gd/actions/workflows/main.yml)

Trying to assert images with PHPUnit? This project provides a constraint and the
required assertions that allow you do to so.

It supports comparing **files on disk** as well as **image resources** in
memory.

## Installation

Add this package to your Composer dev-dependencies:

```
composer require --dev meyfa/phpunit-assert-gd
```

**Compatibility table**

| AssertGD version | Supported PHP version | Supported PHPUnit version |
|:-----------------|:----------------------|:--------------------------|
| 4.*              | >= 8.1                | 10.1                      |
| 3.*              | >= 7.3                | 9                         |
| 2.*              | >= 7.2                | 8                         |
| 1.*              | 5.3.3 - 8.0           | 4.8.36 - 6.5.0            |

## Examples

The assertions are available as a
[trait](http://php.net/manual/en/language.oop5.traits.php), so you can easily
`use` them in your test case class:

```php
<?php
use AssertGD\GDAssertTrait;

class ExampleTest extends PHPUnit\Framework\TestCase
{
    // this trait adds the assert methods to your test case
    use GDAssertTrait;

    public function testSomething()
    {
        $this->assertSimilarGD('./tests/expected.png', './tests/actual.png');
    }
}
```

### Plain comparisons

Use `assertSimilarGD` if you expect 2 images to be exactly equal.
Use `assertNotSimilarGD` if you expect there to be differences.

```php
$this->assertSimilarGD('./tests/img.png', './tests/same.png');
$this->assertNotSimilarGD('./tests/img.png', './tests/other.png');
```

### Threshold values

Provide a number between 0 and 1 to set the error threshold. For example, a
value of 0.2 would allow for at most 20% difference.

```php
$this->assertSimilarGD('./tests/img.png', './tests/similar.png', '', 0.2);
```

### Parameter types

Instead of file paths, you can pass in GD image resources. This eliminates
having to write something to disk prior to the comparison.

```php
$img = imagecreatetruecolor(10, 10);
$this->assertSimilarGD('./tests/empty-10x10.png', $img);
imagedestroy($img);
```

### Manual constraint

If you need to configure mock objects or do other, more complex matching calls,
use `isSimilarGD` to obtain a constraint object (similar to what would be
returned by `equalTo`, `isTrue`, etc.).

```php
$this->assertThat(
    './tests/actual.png',
    $this->isSimilarGD('./tests/expected.png')
);
```

## Difference calculation

By default, this library calculates the difference between two images by
comparing the RGBA color channel information at each pixel coordinate of the
source image and the test image, and averaging the difference between each
pixel to calculate the difference score.

This will work for the majority of cases, but may give incorrect scoring 
in certain circumstances, such as images that contain a lot of transparency.

An alternative calculation method, which scales the RGB color channels
based on their alpha transparency - meaning more transparent pixels will
affect the difficulty score less to offset their less observable difference
on the image itself - can be enabled by adding a new `ScaledRgbChannels`
instance to the 5th parameter of the `assertSimilarGD` or `assertNotSimilarGD`
methods.

```php
use AssertGD\DiffCalculator\ScaledRgbChannels;

public function testImage()
{
    $this->assertSimilarGD(
        'expected.png',
        'actual.png',
        '',
        0,
        new ScaledRgbChannels()
    );
}
```

### Custom difference calculators

If you wish to completely customise how calculations are done in this
library, you may also create your own calculation algorithm by creating
a class that implements the `AssertGd\DiffCalculator` interface.

A class implementing this interface must provide a `calculate` method
that is provided two `GdImage` instances, and the X and Y co-ordinate
(as `ints`) of the pixel being compared in both images.

The method should return a `float` between `0` and `1`, where 0 is
an exact match and 1 is the complete opposite.

You may then provide an instance of the class as the 5th parameter of
the `assertSimilarGD` or `assertNotSimilarGD` method to use this
calculation method for determining the image difference.