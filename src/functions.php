<?php

if (! function_exists('some')) {
    /**
     * @template T
     *
     * @param  T  $value
     * @return \aharisu\Option\Some<T>
     */
    function some($value)
    {
        return \aharisu\Option\Some::make($value);
    }
}

if (! function_exists('none')) {
    /**
     * @return \aharisu\Option\None<mixed>
     */
    function none()
    {
        return \aharisu\Option\None::make();
    }
}

if (! function_exists('toOption')) {
    /**
     * @template T
     *
     * @param  T|null  $value
     * @param  mixed|null  $noneValue
     * @return \aharisu\Option<T>
     */
    function toOption($value, $noneValue = null)
    {
        if ($value === $noneValue) {
            return \aharisu\Option\None::make();
        } else {
            return \aharisu\Option\Some::make($value);
        }
    }
}
