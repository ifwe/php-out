<?php
/**
 * Terse output functions for effortless php templating.
 * All functions ensure proper UTF-8 character encoding.
 */

namespace out;


use InvalidArgumentException;

class OutException extends InvalidArgumentException {
}


/**
 * Output html escaped text.
 * Invalid UTF-8 characters are replaced (or removed in applications using php 5.3).
 *
 * @param string $s to output
 *
 * Example:
 *
 *  <h1><?php out\text("Hello $name!") ?></h1>
 */
function text($s) {
    echo stext($s);
}

/**
 * Output raw html.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to output
 *
 * Example:
 *
 *  <div id="content">
 *      <?php out\html($content) ?>
 *  </div>
 */
function html($s) {
    echo shtml($s);
}

/**
 * Output raw binary data.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to output
 *
 * Example:
 *
 *  <?php out\binary($jpegBinary);
 */
function binary($s) {
    echo $s;
}

/**
 * Output script block text.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to output
 * @throws OutException if $s contains '</script', which would otherwise terminate the block
 *
 * Example:
 *
 *  <script>
 *      someFunc(<?php out\script(json_encode($data)) ?>);
 *  </script>
 */
function script($s) {
    echo sscript($s);
}

/**
 * Output style block text.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to output
 * @throws OutException if $s contains '</style', which would terminate the block
 *
 * Example:
 *
 *  <style>
 *      <?php out\style($css) ?>
 *  </style>
 */
function style($s) {
    echo sstyle($s);
}

/**
 * Output cdata block text.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to output
 * @throws OutException if $s contains ']]>', which would terminate the block
 *
 *  Example:
 *
 *      <![CDATA[<?php out\cdata($characterData) ?>]]>
 */
function cdata($s) {
    echo scdata($s);
}


if (defined('ENT_SUBSTITUTE')):  // added in php 5.4

    /**
     * Return html escaped text.
     * Invalid UTF-8 characters are replaced with the unicode replacement character (U+FFFD).
     *
     * @param string $s to prepare for output
     * @return string
     */
    function stext($s) {
        return htmlentities($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

else:

    /**
     * Return html escaped text.
     * Invalid UTF-8 characters are removed.
     *
     * @param string $s to prepare for output
     * @return string
     */
    function stext($s) {
        return htmlentities($s, ENT_QUOTES | ENT_IGNORE, 'UTF-8');
    }

endif;


if (class_exists('UConverter')):  // available in php 5.5 with intl support

    /**
     * Return raw html.
     * Invalid UTF-8 characters are replaced with the unicode replacement character (U+FFFD).
     *
     * @param string $s to prepare for output
     * @return string
     */
    function shtml($s) {
        $s = \UConverter::transcode($s, 'UTF-8', 'UTF-8');
        return $s;
    }

else:

    /**
     * Return raw html.
     * Invalid UTF-8 characters are removed.
     *
     * @param string $s to prepare for output
     * @return string
     */
    function shtml($s) {
        $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
        return $s;
    }

endif;


/**
 * Return binary data.
 * This function actually does nothing.
 *
 * @param string $s to remain unchanged
 * @return string
 */
function sbinary($s) {
    return $s;
}

/**
 * Return script block text.
 * Script text may not contain the script terminator sequence, '</script'.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to prepare for output
 * @return string
 * @throws OutException if $s contains '</script', which would otherwise terminate the block
 */
function sscript($s) {
    $s = shtml($s);
    if (strpos($s, '</script') !== false) {
        throw new OutException("Invalid terminator found in script tag output, '$s'");
    }
    return $s;
}

/**
 * Return style block text.
 * Style text may not contain the style terminator sequence, '</style'.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to prepare for output
 * @return string
 */
function sstyle($s) {
    $s = shtml($s);
    if (strpos($s, '</style') !== false) {
        throw new OutException("Invalid terminator found in style tag output, '$s'");
    }
    return $s;
}

/**
 * Return cdata block text.
 * CData text may not contain the cdata terminator sequence, ']]>'.
 * Invalid UTF-8 characters are replaced (or removed if UConverter is not available).
 *
 * @param string $s to prepare for output
 * @return string
 */
function scdata($s) {
    $s = shtml($s);
    if (strpos($s, ']]>') !== false) {
        throw new OutException("Invalid terminator found in cdata output, '$s'");
    }
    return $s;
}
