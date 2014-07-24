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

use \Mockery;
use \PHPUnit_Framework_TestCase as TestCase;

use StaticReview\Helper;
use StaticReview\Issue\Issue;

class HelperTests extends TestCase
{
    public function testGetColourString()
    {
        $testString = 'Test String';

        $result = Helper::getColourString($testString, null);

        $this->assertTrue(strpos($result, $testString) !== false);
    }
}
