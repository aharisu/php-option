<?php

namespace aharisu\Option;

use aharisu\Option;
use stdClass;

/**
 * @template T
 *
 * @implements Option<T>
 */
final class Some extends stdClass implements Option
{
    /**
     * @var T
     */
    private $value;

    /**
     * @param  T  $value
     * @return Option<T>
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    /**
     * @param  T  $value
     * @return Some<T>
     */
    public static function make($value): Some
    {
        return new Some($value);
    }

    public function isSome(): bool
    {
        return true;
    }

    public function isNone(): bool
    {
        return false;
    }

    /**
     * @return T
     */
    public function unwrap()
    {
        return $this->value;
    }

    /**
     * @return T
     */
    public function unwrapUnchecked()
    {
        return $this->value;
    }

    /**
     * Returns the contained Some value or a provided default.
     *
     * Arguments passed to unwrap_or are eagerly evaluated;
     * if you are passing the result of a function call,
     * it is recommended to use unwrapOrElse, which is lazily evaluated.
     *
     * @param  T  $default
     * @return T
     */
    public function unwrapOr($default)
    {
        return $this->value;
    }

    /**
     * Returns the contained Some value or computes it from a closure.
     *
     * @param  callable():T  $f
     * @return T
     */
    public function unwrapOrElse($f)
    {
        return $this->value;
    }

    /**
     * @return T|null
     */
    public function tryUnwrap()
    {
        return $this->value;
    }

    /**
     * @param  callable(T):mixed  $callback
     */
    public function someThen($callback): void
    {
        $callback($this->value);
    }

    /**
     * Maps an Option<T> to Option<U> by applying a function to a contained value.
     *
     * @template U
     *
     * @param  callable(T): U  $f
     * @return Option<U>
     */
    public function map($f)
    {
        return $f($this->value);
    }

    /**
     * Returns the provided default result (if none),
     * or applies a function to the contained value (if any).
     *
     * Arguments passed to mapOr are eagerly evaluated;
     * if you are passing the result of a function call,
     * it is recommended to use mapOrElse, which is lazily evaluated.
     *
     * @template U
     *
     * @param  U  $default
     * @param  callable(T): U  $f
     * @return Option<U>
     */
    public function mapOr($default, $f)
    {
        return $f($this->value);
    }

    /**
     * Computes a default function result (if none),
     * or applies a different function to the contained value (if any).
     *
     * @template U
     *
     * @param  callable():U  $default
     * @param  callable(T): U  $f
     * @return Option<U>
     */
    public function mapOrElse($default, $f)
    {
        return $f($this->value);
    }

    /**
     * Returns None if the option is None, otherwise returns $other.
     *
     * Arguments passed to and are eagerly evaluated;
     * if you are passing the result of a function call,
     * it is recommended to use andThen, which is lazily evaluated.
     *
     * @template U
     *
     * @param  Option<U>  $other
     * @return Option<U>
     */
    public function and($other)
    {
        return $other;
    }

    /**
     * Returns None if the option is None,
     * otherwise calls f with the wrapped value and returns the result.
     *
     * Some languages call this operation flatmap.
     *
     * @template U
     *
     * @param  callable(T): Option<U>  $f
     * @return Option<U>
     */
    public function andThen($f)
    {
        return $f($this->value);
    }

    /**
     * Returns the option if it contains a value, otherwise returns $other.
     *
     * Arguments passed to or are eagerly evaluated;
     * if you are passing the result of a function call,
     * it is recommended to use orElse, which is lazily evaluated.
     *
     * @param  Option<T>  $other
     * @return Option<T>
     */
    public function or($other)
    {
        return $this;
    }

    /**
     * Returns the option if it contains a value, otherwise calls f and returns the result.
     *
     * @param  callable(): Option<T>  $f
     * @return Option<T>
     */
    public function orElse($f)
    {
        return $this;
    }

    /**
     * Returns Some if exactly one of self, $other is Some, otherwise returns None.
     *
     * @param  Option<T>  $other
     * @return Option<T>
     */
    public function xor($other)
    {
        if ($other->isSome()) {
            return None::make();
        } else {
            return $this;
        }
    }

    /**
     * Returns None if the option is None,
     * otherwise calls predicate with the wrapped value and returns:
     * - Some(t) if predicate returns true (where t is the wrapped value), and
     * - None if predicate returns false.
     *
     * @param  callable(T): bool  $predicate
     * @return Option<T>
     */
    public function filter($predicate)
    {
        if ($predicate($this->value)) {
            return $this;
        } else {
            return None::make();
        }
    }

    /**
     * @param  Option<T>|T  $other
     */
    public function equals($other): bool
    {
        if (gettype($other) === 'object' && get_class($other) === Some::class) {
            //両者ともSomeなら内部値で比較する
            return $this->unwrapUnchecked() === $other->unwrapUnchecked();
        } else {
            //The $other is not an Option type

            return $this->value === $other;
        }
    }

    //
    // implements Iterator
    //

    //
    // NOTE
    // we need the Iterator feature in the first place?

    // NOTE
    // Intentionally dynamic property.
    // Because we do not want to consume memory for functions that are used infrequently.
    //private bool $iteratorFirst;

    /**
     * @return T
     */
    public function current(): mixed
    {
        return $this->value;
    }

    /**
     * @codeCoverageIgnore
     */
    public function key(): mixed
    {
        return 0;
    }

    public function next(): void
    {
        $this->iteratorFirst = false;
    }

    public function rewind(): void
    {
        $this->iteratorFirst = true;
    }

    public function valid(): bool
    {
        return  $this->iteratorFirst && $this->isSome();
    }
}
