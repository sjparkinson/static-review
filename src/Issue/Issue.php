<?php

namespace StaticReview\Issue;

use StaticReview\Review\ReviewableInterface;
use StaticReview\Review\ReviewInterface;

class Issue implements IssueInterface
{
    /**
     * Issue level flags.
     */
    const LEVEL_INFO = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR = 4;
    const LEVEL_ALL = 7;

    private $level;
    private $message;
    private $review;
    private $line;
    private $subject;

    /**
     * Initializes a new instance of the Issue class.
     *
     * @param int                 $level
     * @param string              $message
     * @param ReviewInterface     $review
     * @param ReviewableInterface $subject
     * @param int                 $line
     */
    public function __construct(
        $level,
        $message,
        ReviewInterface $review,
        ReviewableInterface $subject,
        $line = null
    ) {
        $this->level = $level;
        $this->message = $message;
        $this->review = $review;
        $this->subject = $subject;
        $this->line = $line;
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
     * @return mixed
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param mixed $line
     */
    public function setLine($line)
    {
        $this->line = $line;
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
                throw new \UnexpectedValueException('Level was set to '.$this->getLevel());
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
                return 'yellow';
            case self::LEVEL_ERROR:
                return 'red';
            default:
                throw new \UnexpectedValueException('Could not get a colour. Level was set to '.$this->getLevel());
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

        return $result === $this->getLevel();
    }

    /**
     * Overrides the toString method.
     */
    public function __toString()
    {
        $filename = $this->getSubject()->getName();

        return sprintf(
            '    • %s%s%s',
            $this->getMessage(),
            ($filename != '') ? sprintf(' in %s', $filename) : '',
            $this->getLine() ? sprintf(' on line %d', $this->getLine()) : ''
        );
    }
}
