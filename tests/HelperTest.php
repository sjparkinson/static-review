<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE.md
 */

namespace StaticReview\Tests;

use StaticReview\Helper;
use StaticReview\Issue\Issue;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class HelperTests extends TestCase
{
    public function testGetColourStringWithoutColour()
    {
        $testString = 'Test String';

        $result = Helper::getColourString($testString);

        $this->assertTrue(strpos($result, $testString) === 0);

        $result = Helper::getColourString($testString, null);

        $this->assertTrue(strpos($result, $testString) === 0);
    }

    public function testGetColourStringWithColour()
    {
        $testString = 'Test String';

        $result = Helper::getColourString($testString, 'red');

        $this->assertTrue(strpos($result, $testString) !== 0);
    }
}
