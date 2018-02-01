# AssertGD for PHPUnit

[![Build Status](https://travis-ci.org/meyfa/phpunit-assert-gd.svg?branch=master)](https://travis-ci.org/meyfa/phpunit-assert-gd)

Trying to assert images with PHPUnit? This project provides a constraint and the
required assertions that allow you do to so.

It supports comparing **files on disk** as well as **image resources** in
memory.

**Compatibility note:** This library supports PHP versions 5.3.3 up to 7.2.2. It
supports PHPUnit from version 4.8.36 to version 6.5.0.
Since those PHPUnit versions are completely incompatible, extreme hacks have to
be used that depend on the Composer autoloading order. Please file an issue if
you notice any errors.

## Installation

Add this package to your Composer dev-dependencies:

```
composer require --dev meyfa/phpunit-assert-gd
```

## Examples

The assertions are available as a
[trait](http://php.net/manual/en/language.oop5.traits.php), so you can easily
`use` them in your test case class:

```php
<?php
use AssertGD\GDAssertTrait;

class ExampleTest extends PHPUnit_Framework_TestCase
{
    // this trait adds the assert methods to your test case
    use GDAssertTrait;

    public function testSomething()
    {
        $this->assertSimilarGD('./tests/expected.png', './tests/actual.png');
    }
}
```

**Note:** While this library should work with PHP down to at least v5.3.3,
traits are a v5.4.0 feature. For versions lower than v5.4.0, you have to use
this alternative syntax:

```php
<?php
use AssertGD\GDSimilarityConstraint;

class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function testSomething()
    {
        $this->assertThat('./tests/actual.png',
            new GDSimilarityConstraint('./tests/expected.png'));
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
