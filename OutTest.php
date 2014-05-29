<?php

require_once __DIR__.'/out.php';

class OutTest extends PHPUnit_Framework_TestCase {

    public function outTestProvider() {
        // replacement character
        $RC = ENT_SUBSTITUTE === ENT_IGNORE ? '' : '�';

        return array(
            array("<>\"'&",    'text',   '&lt;&gt;&quot;&#039;&amp;'),
            array("<>\"'&",    'attr',   '&lt;&gt;&quot;&#039;&amp;'),
            array("<>\"'&",    'raw',    '<>"\'&'),
            array("<>\"'&",    'binary', '<>"\'&'),
            array("<>\"'&",    'script', '<>"\'&'),
            array("<>\"'&",    'style',  '<>"\'&'),
            array("foo bar 漢字 \xFF",   'text',   "foo bar 漢字 $RC"),
            array("foo bar 漢字 \xFF",   'attr',   "foo bar 漢字 $RC"),
            array("foo bar 漢字 \xFF",   'raw',    "foo bar 漢字 "),
            array("foo bar 漢字 \xFF",   'binary', "foo bar 漢字 \xFF"),
            array("foo bar 漢字 \xFF",   'script', "foo bar 漢字 "),
            array("foo bar 漢字 \xFF",   'style',  "foo bar 漢字 "),
        );
    }

    /**
     * @dataProvider outTestProvider
     */
    public function testSOut($input, $func, $expect) {
        $outfunc = "out\\s$func";
        $output = $outfunc($input);
        $this->assertEquals($expect, $output, "input='$input' func='out\\$func' expect='$expect' output='$output'");
    }

    /**
     * @dataProvider outTestProvider
     */
    public function testOut($input, $func, $expect) {
        $outfunc = "out\\$func";
        ob_start();
        $outfunc($input);
        $output = ob_get_clean();
        $this->assertEquals($expect, $output, "input='$input' func='out\\$func' expect='$expect' output='$output'");
    }

    public function testOutScriptInvalidTerminator() {
        try {
            $input = 'foo = "bar";</script ><script>alert(666);</script >';
            out\script($input);
            $this->fail('Expected script terminator to throw');
        } catch (out\InvalidOutputException $e) {
            $this->assertContains($input, $e->getMessage());
        }
    }

    public function testOutStyleInvalidTerminator() {
        try {
            $input = 'background-color: red;</style ><script>alert(666);</script>';
            out\style($input);
            $this->fail('Expected style terminator to throw');
        } catch (out\InvalidOutputException $e) {
            $this->assertContains($input, $e->getMessage());
        }
    }

    public function testOutCdataInvalidTerminator() {
        try {
            $input = 'background-color: red;]]><script>alert(666);</script>';
            out\cdata($input);
            $this->fail('Expected style terminator to throw');
        } catch (out\InvalidOutputException $e) {
            $this->assertContains($input, $e->getMessage());
        }
    }
}
