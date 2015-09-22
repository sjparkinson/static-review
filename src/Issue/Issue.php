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

namespace StaticReview\Issue;

use StaticReview\Review\ReviewableInterface;
use StaticReview\Review\ReviewInterface;

class Issue implements IssueInterface
{
    /**
     * Issue level flags.
     */
    const LEVEL_INFO    = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR   = 4;
    const LEVEL_ALL     = 7;

    private $level;

    private $message;

    private $review;

    private $subject;

    /**
     * Initializes a new instance of the Issue class.
     *
     * @param int                 $level
     * @param string              $message
     * @param ReviewInterface     $review
     * @param ReviewableInterface $subject
     */
    public function __construct(
        $level,
        $message,
        ReviewInterface $review,
        ReviewableInterface $subject
    ) {
        $this->level   = $level;
        $this->message = $message;
        $this->review  = $review;
        $this->subject = $subject;
    }

    /**
     * Gets the Issues level.
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Gets the Issues message.
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Gets the name of the Issues Review.
     */
    public function getReviewName()
    {
        $classPath = explode('\\', get_class($this->review));

        return end($classPath);
    }

    /**
     * Gets the Issues Reviewable instance.
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Gets the Issues level as a string.
     */
    public function getLevelName()
    {
        switch ($this->getLevel()) {
            case self::LEVEL_INFO:
                return 'Info';

            case self::LEVEL_WARNING:
                return 'Warning';

            case self::LEVEL_ERROR:
                return 'Error';

            default:
                throw new \UnexpectedValueException('Level was set to ' . $this->getLevel());
        }
    }

    /**
     * Gets the colour to use when echoing to the console.
     *
     * @return string
     */
    public function getColour()
    {
        switch ($this->level) {
            case self::LEVEL_INFO:
                return 'cyan';

            case self::LEVEL_WARNING:
                return 'brown';

            case self::LEVEL_ERROR:
                return 'red';

            default:
                throw new \UnexpectedValueException('Could not get a colour. Level was set to ' . $this->getLevel());
        }
    }

    /**
     * Check that the Issue matches the possible level options.
     *
     * @link http://php.net/manual/en/language.operators.bitwise.php#108679
     */
    public function matches($option)
    {
        $result = ($this->getLevel() & $option);

        return ($result === $this->getLevel());
    }

    /**
     * Overrides the toString method.
     */
    public function __toString()
    {
        return sprintf(
            "%s %s: %s in %s",
            $this->getReviewName(),
            $this->getLevelName(),
            $this->getMessage(),
            $this->getSubject()->getName()
        );
    }
}
