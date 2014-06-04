<?php
/**
 * Copyright 2014 Tagged Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
            array("foo bar 漢字 \xFF blargh", 'text',   "foo bar 漢字 $textReplace blargh"),
            array("foo bar 漢字 \xFF blargh", 'html',   "foo bar 漢字 $htmlReplace blargh"),
            array("foo bar 漢字 \xFF blargh", 'binary', "foo bar 漢字 \xFF blargh"),
            array("foo bar 漢字 \xFF blargh", 'script', "foo bar 漢字 $htmlReplace blargh"),
            array("foo bar 漢字 \xFF blargh", 'style',  "foo bar 漢字 $htmlReplace blargh"),
            array("foo bar 漢字 \xFF blargh", 'cdata',  "foo bar 漢字 $htmlReplace blargh"),
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
