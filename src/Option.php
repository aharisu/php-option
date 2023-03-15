<?php

namespace aharisu;

use Exception;
use IteratorAggregate;

/**
 * @template T
 *
 * @extends IteratorAggregate<int, T>
 */
interface Option extends IteratorAggregate
{
    public function isSome(): bool;

    public function isNone(): bool;

    /**
     * @throws Exception
     *
     * @return T
     */
    public function unwrap();

    /**
     * @return T
     */
    public function unwrapUnchecked();

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
    public function unwrapOr($default);

    /**
     * Returns the contained Some value or computes it from a closure.
     *
     * @param  callable():T  $f
     * @return T
     */
    public function unwrapOrElse($f);

    /**
     * @return T|null
     */
    public function tryUnwrap();

    /**
     * @param  callable(T):mixed  $callback
     * @return void
     */
    public function someThen($callback);

    /**
     * Maps an Option<T> to Option<U> by applying a function to a contained value.
     *
     * @template U
     *
     * @param  callable(T): U  $f
     * @return Option<U>
     */
    public function map($f);

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
    public function mapOr($default, $f);

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
    public function mapOrElse($default, $f);

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
    public function and($other);

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
    public function andThen($f);

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
    public function or($other);

    /**
     * Returns the option if it contains a value, otherwise calls f and returns the result.
     *
     * @param  callable(): Option<T>  $f
     * @return Option<T>
     */
    public function orElse($f);

    /**
     * Returns Some if exactly one of self, $other is Some, otherwise returns None.
     *
     * @param  Option<T>  $other
     * @return Option<T>
     */
    public function xor($other);

    /**
     * Returns None if the option is None,
     * otherwise calls predicate with the wrapped value and returns:
     * - Some(t) if predicate returns true (where t is the wrapped value), and
     * - None if predicate returns false.
     *
     * @param  callable(T): bool  $predicate
     * @return Option<T>
     */
    public function filter($predicate);

    /**
     * @param  Option<T>|T  $other
     */
    public function equals($other): bool;
}
