<?php

class OutTest extends PHPUnit_Framework_TestCase {

    public function outTestProvider() {
        // unicode replacement character
        $textReplace = defined('ENT_SUBSTITUTE')  ? '�' : '';
        $htmlReplace = class_exists('UConverter') ? '�' : '';

        return array(
            array("<>\"'&", 'text',   '&lt;&gt;&quot;&#039;&amp;'),
            array("<>\"'&", 'html',   '<>"\'&'),
            array("<>\"'&", 'binary', '<>"\'&'),
            array("<>\"'&", 'script', '<>"\'&'),
            array("<>\"'&", 'style',  '<>"\'&'),
            array("<>\"'&", 'cdata',  '<>"\'&'),
            array("foo bar 漢字 \xFF", 'text',   "foo bar 漢字 $textReplace"),
            array("foo bar 漢字 \xFF", 'html',   "foo bar 漢字 $htmlReplace"),
            array("foo bar 漢字 \xFF", 'binary', "foo bar 漢字 \xFF"),
            array("foo bar 漢字 \xFF", 'script', "foo bar 漢字 $htmlReplace"),
            array("foo bar 漢字 \xFF", 'style',  "foo bar 漢字 $htmlReplace"),
            array("foo bar 漢字 \xFF", 'cdata',  "foo bar 漢字 $htmlReplace"),
        );
    }

    /**
     * @dataProvider outTestProvider
     */
    public function testSOutFunctions($input, $func, $expect) {
        $outfunc = "out\\s$func";
        $output = $outfunc($input);
        $this->assertEquals($expect, $output, "$outfunc($input) produced '$output', but expected '$expect'");
    }

    /**
     * @dataProvider outTestProvider
     */
    public function testOutFunctions($input, $func, $expect) {
        $outfunc = "out\\$func";
        ob_start();
        $outfunc($input);
        $output = ob_get_clean();
        $this->assertEquals($expect, $output, "$outfunc($input) produced '$output', but expected '$expect'");
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage </script
     */
    public function testOutSScriptWithScriptTerminator() {
        $input = '</script';
        out\sscript($input);
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage </ScRiPt
     */
    public function testOutSScriptWithStrangeCaseScriptTerminator() {
        $input = '</ScRiPt';
        out\sscript($input);
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage </style
     */
    public function testOutSStyleWithStyleTerminator() {
        $input = '</style';
        out\sstyle($input);
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage </sTyLe
     */
    public function testOutSStyleWithStrangeCaseStyleTerminator() {
        $input = '</sTyLe';
        out\sstyle($input);
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage ]]>
     */
    public function testOutSCDataWithCDataTerminator() {
        $input = ']]>';
        out\scdata($input);
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage foo = "bar";</script ><script>alert(666);</script >
     */
    public function testOutScriptWithScriptTerminator() {
        $input = 'foo = "bar";</script ><script>alert(666);</script >';
        out\script($input);
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage color: red;</style ><script>alert(666);</script>
     */
    public function testOutStyleWithStyleTerminator() {
        $input = 'color: red;</style ><script>alert(666);</script>';
        out\style($input);
    }

    /**
     * @expectedException        out\OutException
     * @expectedExceptionMessage background-color: red;]]><script>alert(666);</script>
     */
    public function testOutCDataWithCDataTerminator() {
        $input = 'background-color: red;]]><script>alert(666);</script>';
        out\cdata($input);
    }
}
