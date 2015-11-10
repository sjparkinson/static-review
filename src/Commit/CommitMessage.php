<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace StaticReview\Commit;

class CommitMessage implements CommitMessageInterface
{
    /**
     * @var string Commit message subject.
     */
    protected $subject;

    /**
     * @var string Commit message body.
     */
    protected $body = '';

    /**
     * @var boolean Commit identifier.
     */
    protected $hash;

    /**
     * Initializes a new instance of the CommitMessage class.
     *
     * @param string $message
     * @param string $hash
     */
    public function __construct($message, $hash = null)
    {
        // Strip out the diff that is included in the commit message when
        // using `git commit -v` as we should not check it as text.
        list($message) = preg_split('/# \-+ >8 \-+/', $message, 2);

        // Remove all comment lines from the message
        $message = preg_replace('/^#.*/m', '', $message);

        // Split the message by newlines
        $message = preg_split('/(\r?\n)+/', trim($message));

        $this->subject = array_shift($message);

        if ($message) {
            $this->body = implode("\n", $message);
        }

        $this->hash = $hash;
    }

    /**
     * Get the commit message subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get the commit message body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Is the commit current or historical?
     *
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash ?: null;
    }

    /**
     * Get the reviewable name for the commit.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getHash() ?: 'current commit';
    }
}
