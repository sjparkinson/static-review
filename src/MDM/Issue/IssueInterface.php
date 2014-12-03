<?php

namespace MDM\Issue;

interface IssueInterface
{
    public function getReviewName();
    public function getLevelName();
    public function getMessage();
    public function getFile();
}
