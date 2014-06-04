out\text('is safer than echo')
==============================

Motivated by the need to easily construct properly formatted php templates,
the out library provides terse output functions for all HTML5 contexts: text, html, script, style and CDATA.
It also ensures consistent character encoding by assuming [UTF-8 will be used everywhere](http://www.utf8everywhere.org/),
and replacing (or removing) all invalid characters with the unicode replacement character, '�'.


example
-------

```php
<?php

// blog post submitted by user
$userName  = '</script> I am an xss attacker';
$postTitle = 'I pwn you <script>pwn(home)</script>';
$postBody  = '<p>This html block <em>MUST</em> be well scrubbed or come from a trusted source.</p>';
$customCss = 'background:black;color:white;</style> XSS here';
$cdata     = 'Who uses this?';

?>
<!DOCTYPE html>
<html>
<head>
  <title><?php out\text(sprintf(_('Blog post: %s'), $postTitle)) ?></title>
  <style>
    <?php out\style($customCss) ?>
  </style>
</head>
<body>
  <h1><?php out\text($postTitle) ?></h1>
  <div id="post-body">
    <?php out\html($postBody) ?>
  </div>
  <script>
    initApp(<?php out\script(json_encode($userName))) ?>)
  </script>
  <![CDATA[<?php out\cdata($cdata) ?>]]>
</body>
</html>
```


install
-------

Add to [composer.json](https://getcomposer.org/) from packagist.

    composer install --save tagged/out

The out library is included with the composer autoloader.

    require 'vendor/autoload.php';


usage
-----

### output functions

All output functions write directly to stdout.


#### Write html-escaped text with `out\text`

```php
<h1>Hello <?php out\text($name) ?></h1>

<img src="<?php out\text($image_url) ?>">
```

#### Write raw html with `out\html`

```php
<div id="content">
    <?php out\html($content_html) ?>
</div>
```

#### Write binary with `out\binary`

```php
<?php out\binary($image_binary) ?>
```

#### Write data into a script block with `out\script`

```php
<script>
    var data = <?php out\script(json_encode($data)) ?>;
</script>
```

#### Write data into a style block with `out\style`

```php
<style>
    <?php out\style($css) ?>
</style>
```

#### Write data into a cdata block with `out\cdata`

```php
<![CDATA[
    <?php out\cdata($character_data) ?>
]]>
```

### string functions

All string functions return the result as a string.
Every output function has a corresponding string function.

```php
$encodedName = out\stext($name);
$content     = out\shtml($content_html);
$imageBinary = out\sbinary($image_binary);
$scriptData  = out\sscript(json_encode($data));
$styleData   = out\sstyle($css);
$cdataData   = out\scdata($character_data);
```
