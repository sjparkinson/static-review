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

namespace StaticReview\Test\Unit\VersionControl;

use PHPUnit_Framework_TestCase as TestCase;
use StaticReview\VersionControl\VersionControlFactory;

class VersionControlFactoryTest extends TestCase
{
    public function testBuildWithGitVersionControl()
    {
        $vcs = VersionControlFactory::SYSTEM_GIT;

        $expected = 'StaticReview\VersionControl\GitVersionControl';

        $this->assertInstanceOf($expected, VersionControlFactory::build($vcs));
    }
}
