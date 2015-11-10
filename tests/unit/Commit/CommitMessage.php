<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2015 Woody Gilk <@shadowhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace StaticReview\Test\Unit\Commit;

use StaticReview\Commit\CommitMessage;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class CommitMessageTest extends TestCase
{
    /**
     * @var string Directory that contains fixtures
     */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = realpath(__DIR__ . '/../../fixtures');
    }

    /**
     * Get a commit message fixture by name
     *
     * @param string $name
     *
     * @return string
     */
    private function message($name)
    {
        return file_get_contents($this->fixtures . '/commit-message-' . $name . '.txt');
    }

    public function testConstructionSubjectOnly()
    {
        $commit = new CommitMessage($this->message('subject-only'));

        $this->assertSame('Create a better commit message', $commit->getSubject());
        $this->assertSame('', $commit->getBody());
    }

    public function testConstructionSubjectAndBody()
    {
        $commit = new CommitMessage($this->message('subject-and-body'));

        $this->assertSame('Create a better commit message', $commit->getSubject());
        $this->assertSame('We have the tools.', $commit->getBody());
    }

    public function testConstructionSubjectAndBodyAndComments()
    {
        $commit = new CommitMessage($this->message('subject-and-body-and-comments'));

        // Nothing should be different, the comments should be stripped
        $this->assertSame('Create a better commit message', $commit->getSubject());
        $this->assertSame('We have the tools.', $commit->getBody());
    }

    public function testConstructionSubjectAndBodyAndDiff()
    {
        $commit = new CommitMessage($this->message('subject-and-body-and-diff'));

        // Nothing should be different, the diff should be stripped
        $this->assertSame('Create a better commit message', $commit->getSubject());
        $this->assertSame('We have the tools.', $commit->getBody());
    }
}
