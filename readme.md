the out library
===============

Terse output functions for effortless php templating.

install
-------

Add the following to [composer.json](https://getcomposer.org/).
TODO: Update this library with a tag or publish to packagist,
so the `@dev` modifier will not be required.

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.tagged.com/cjohnson/php-out.git"
        }
    ],
    "require": {
        "tagged/out": "*@dev"
    }

The out library is included with the composer autoloader.

    require 'vendor/autoload.php';


usage
-----

### output functions

All output functions write directly to stdout.


#### Write html-escaped text with `out\text`

    <h1>Hello <?php out\text($name) ?></h1>

    <img src="<?php out\text($image_url) ?>">

#### Write raw html with `out\html`

    <div id="content">
        <?php out\html($content_html) ?>
    </div>

#### Write binary with `out\binary`

    <?php out\binary($image_binary) ?>

#### Write data into a script block with `out\script`

    <script>
        var data = <?php out\script(json_encode($data)) ?>;
    </script>

#### Write data into a style block with `out\style`

	<style>
	    <?php out\style($css) ?>
	</style>

#### Write data into a cdata block with `out\cdata`

    <![CDATA[
        <?php out\cdata($character_data) ?>
    ]]>


### string functions

All string functions return the result as a string.
Every output function has a corresponding string function.

    $encodedName = out\stext($name);
    $content     = out\shtml($content_html);
    $imageBinary = out\sbinary($image_binary);
    $scriptData  = out\sscript(json_encode($data));
    $styleData   = out\sstyle($css);
    $cdataData   = out\scdata($character_data);
