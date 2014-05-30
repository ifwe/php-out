<?php
/**
 * Terse output functions for properly escaping html output.
 */

namespace out;

// in php 5.4, we will use non-utf8 character substitution
// as this feature is not available in 5.3, fallback on non-utf8 char removal
if (!defined('ENT_SUBSTITUTE')) {
    define('ENT_SUBSTITUTE', ENT_IGNORE);
}

use InvalidArgumentException;

class InvalidOutputException extends InvalidArgumentException {
}

/**
 * Return html encoded text.
 * @return string
 */
function stext($s) {
    return htmlentities($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Write html encoded text.
 */
function text($s) {
    echo stext($s);
}

/**
 * Return html attribute text.
 * @return string
 * @deprecated identical to stext($s)
 */
function sattr($s) {
    return stext($s);
}

/**
 * Write html attribute text.
 * @deprecated identical to text($s)
 */
function attr($s) {
    echo stext($s);
}

/**
 * Return raw html.
 * @return string
 */
function sraw($s) {
    $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
    return $s;
}

/**
 * Write raw html.
 */
function raw($s) {
    echo sraw($s);
}

/**
 * Return binary data.
 * @return string
 */
function sbinary($s) {
    return $s;
}

/**
 * Write binary data.
 */
function binary($s) {
    echo $s;
}

/**
 * Return script block text.
 * @return string
 */
function sscript($s) {
    $s = sraw($s);
    if (strpos($s, '</script') !== false) {
        throw new InvalidOutputException("Invalid terminator found in script tag output, '$s'");
    }
    return $s;
}

/**
 * Write script block text.
 */
function script($s) {
    echo sscript($s);
}

/**
 * Return style block text.
 * @return string
 */
function sstyle($s) {
    $s = sraw($s);
    if (strpos($s, '</style') !== false) {
        throw new InvalidOutputException("Invalid terminator found in style tag output, '$s'");
    }
    return $s;
}

/**
 * Write style block text.
 */
function style($s) {
    echo sstyle($s);
}

/**
 * Return cdata block text.
 * @return string
 */
function scdata($s) {
    $s = sraw($s);
    if (strpos($s, ']]>') !== false) {
        throw new InvalidOutputException("Invalid terminator found in cdata output, '$s'");
    }
    return $s;
}

/**
 * Write cdata block text.
 */
function cdata($s) {
    echo scdata($s);
}
