<?php
/**
 * Terse output functions for properly escaping html output.
 */

namespace out;

use InvalidArgumentException;

class InvalidOutputException extends InvalidArgumentException {
}

/**
 * Return html encoded text.
 * @return string
 */
if (defined('ENT_SUBSTITUTE')) {

    function stext($s) {
        return htmlentities($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

} else {

    function stext($s) {
        return htmlentities($s, ENT_QUOTES | ENT_IGNORE, 'UTF-8');
    }

}

/**
 * Write html encoded text.
 */
function text($s) {
    echo stext($s);
}

/**
 * Return raw html.
 * @return string
 */
if (class_exists('UConverter')) {

    function sraw($s) {
        $s = \UConverter::transcode($s, 'UTF-8', 'UTF-8');
        return $s;
    }

} else {

    function sraw($s) {
        $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
        return $s;
    }

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
