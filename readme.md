the out library
===============

Echo is evil.
Use the out library instead.

install
-------

Add the following to your [composer.json](https://getcomposer.org/) file.
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

usage
-----

The out library should be included by default with the autoloader.

    require 'vendor/autoload.php';

To write text into an html file, use `out\text`.

    <h1>Hello <?php out\text($name) ?></h1>

Attributes can also be written this way.

    <img src="<?php out\text($url) ?>">

For strings containing previously formatted html, use `out\raw`.

    <div id="content">
        <?php out\text($content) ?>
    </div>

TODO: `out\binary`, `out\script`, `out\style`, `out\cdata`, and the "s" functions.
