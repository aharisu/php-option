<?php

namespace aharisu\Option;

use aharisu\Option;
use EmptyIterator;
use Exception;
use Traversable;

/**
 * @template T
 *
 * @implements Option<T>
 */
final class None implements Option
{
    /**
     * @var None<T>
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return None<T>
     */
    public static function make(): None
    {
        if (self::$instance === null) {
            self::$instance = new None();
        }

        return self::$instance;
    }

    /**
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    public function isSome(): bool
    {
        return false;
    }

    public function isNone(): bool
    {
        return true;
    }

    /**
     * @throws Exception
     *
     * @return never
     */
    public function unwrap()
    {
        throw new Exception('Access the none value');
    }

    /**
     * @throws Exception
     *
     * @return never
     */
    public function unwrapUnchecked()
    {
        throw new Exception('Access the none value');
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
        return $default;
    }

    /**
     * Returns the contained Some value or null.
     *
     * @return T|null
     */
    public function unwrapOrNull()
    {
        return null;
    }

    /**
     * Returns the contained Some value or computes it from a closure.
     *
     * @param  callable():T  $f
     * @return T
     */
    public function unwrapOrElse($f)
    {
        return $f();
    }

    /**
     * @return T|null
     */
    public function tryUnwrap()
    {
        return null;
    }

    /**
     * @param  callable(T):mixed  $callback
     * @return void
     */
    public function someThen($callback)
    {
        //do nothing
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
        return $this;
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
        return $default;
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
        return $default();
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
        return $this;
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
        return $this;
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
        return $other;
    }

    /**
     * Returns the option if it contains a value, otherwise calls f and returns the result.
     *
     * @param  callable(): Option<T>  $f
     * @return Option<T>
     */
    public function orElse($f)
    {
        return $f();
    }

    /**
     * Returns Some if exactly one of self, $other is Some, otherwise returns None.
     *
     * @param  Option<T>  $other
     * @return Option<T>
     */
    public function xor($other)
    {
        return $other;
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
        return $this;
    }

    /**
     * @param  Option<T>|T  $other
     */
    public function equals($other): bool
    {
        return $this === $other;
    }

    //
    // implements IteratorAggregate
    //
    public function getIterator(): Traversable
    {
        return new EmptyIterator();
    }
}
