# PHP Option object like a [Rust Option type](https://doc.rust-lang.org/std/option/enum.Option.html).

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