# PHP Option object like a [Rust Option type](https://doc.rust-lang.org/std/option/enum.Option.html).
<p>
<a href="LICENSE-APACHE"><img src="https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="LICENSE-MIT2"><img src="https://img.shields.io/badge/license-MIT2-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/aharisu/php-option/actions"><img src="https://github.com/aharisu/php-option/actions/workflows/tests.yml/badge.svg" alt="Build Status"></img></a>
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

if (($v = $some->tryUnwrap()) !== null) {
    //do something
}

$some->someThen(function ($v) {
    //do something
});

$v2 = $none->unwrapOr(2);
$k = 10;
$v3 = $none->unwrapOrElse(fn () => 2 * $k);

```

## License

Apache 2.0 & MIT