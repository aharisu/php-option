# PHP Option object like a [Rust Option type](https://doc.rust-lang.org/std/option/enum.Option.html).
<p>
<a href="LICENSE-APACHE"><img src="https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="LICENSE-MIT2"><img src="https://img.shields.io/badge/license-MIT2-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/aharisu/php-option/actions"><img src="https://github.com/aharisu/php-option/actions/workflows/tests.yml/badge.svg" alt="Build Status"></img></a>
<a href='https://coveralls.io/github/aharisu/php-option'><img src='https://coveralls.io/repos/github/aharisu/php-option/badge.svg' alt='Coverage Status' /></a>
<a href="https://github.com/aharisu/php-option/releases"><img src="https://img.shields.io/github/release/aharisu/php-option.svg?style=flat-square" alt="Latest Version"></img></a>
</p>

## Installation

```php
composer require aharisu/option
```

## Usage

```php
// make some object
$some = some(1);

// make none object
$none = none();

if ($some->isSome()) {
    $v = $some->unwrap();
}
if ($none->isNone()) {
    //do something
}

if (null != $v = $some->tryUnwrap()) {
    //do something
    print_r($v);
}

$some->someThen(function ($v) {
    //do something
    print_r($v);
});

$v2 = $none->unwrapOr(2);
$k = 10;
$v3 = $none->unwrapOrElse(fn () => 2 * $k);

// true
if (some(1)->equals(1)) {
}
if (some(1)->equals(some(1))) {
}
// false
if (none()->equals(null)) {
}

// false and type mismatch warning
if (some(1)->equals(1.0)) {
}
// false and type mismatch warning
if (some(1)->equals('1')) {
}
```

## Using class property

```php
use aharisu\Option;

class ValueType
{
    /**
     * @param Option<string> $text
     * @param Option<int>    $value
     */
    public function __construct(
        public readonly int $id,
        public readonly Option $text,
        public readonly Option $value,
    ) {
    }
}

new ValueType(
    1,
    toOption('text'), //some
    toOption(null),   //none
);
```

## License

Apache 2.0 & MIT