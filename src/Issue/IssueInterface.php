<?php

namespace StaticReview\Issue;

interface IssueInterface
{
    public function getReviewName();
    public function getLevelName();
    public function getMessage();
    public function getSubject();
    public function getLine();
}
