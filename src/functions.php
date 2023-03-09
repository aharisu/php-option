<?php

if (!function_exists('some')) {
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

if (!function_exists('none')) {
    /**
     * @return \aharisu\Option\None
     */
    function none()
    {
        return \aharisu\Option\None::make();
    }
}

if (!function_exists('nullToNone')) {
    /**
     * @template T
     *
     * @param  T|null  $value
     * @return \aharisu\Option<T>
     */
    function nullToNone($value)
    {
        if ($value === null) {
            return \aharisu\Option\None::make();
        } else {
            return \aharisu\Option\Some::make($value);
        }
    }
}
