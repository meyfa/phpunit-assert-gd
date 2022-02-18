# AssertGD for PHPUnit

[![CI](https://github.com/meyfa/phpunit-assert-gd/actions/workflows/main.yml/badge.svg?branch=v2.x)](https://github.com/meyfa/phpunit-assert-gd/actions/workflows/main.yml)

Trying to assert images with PHPUnit? This project provides a constraint and the
required assertions that allow you do to so.

It supports comparing **files on disk** as well as **image resources** in
memory.

**NOTE:** You are looking at an older version of this library that is kept
around for legacy PHPUnit support. See the compatibility table below when
deciding on the version to use for your project.

## Installation

Add this package to your Composer dev-dependencies:

```
composer require --dev meyfa/phpunit-assert-gd
```

| AssertGD version | Supported PHP version | Supported PHPUnit version |
| :--------------- | :-------------------- | :------------------------ |
| 3.*              | >= 7.3                | 9                         |
| 2.*              | >= 7.2                | 8                         |
| 1.*              | >= 5.3.3              | 4.8.36 - 6.5.0            |

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
