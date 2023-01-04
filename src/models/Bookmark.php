<?php

class Bookmark {
    private $user_id;
    private $submission_id;
    private $bookmarked_date;

    public function __construct(string $user_id, string $submission_id, string $bookmarked_date)
    {
        $this->user_id = $user_id;
        $this->submission_id = $submission_id;
        $this->bookmarked_date = strtotime($bookmarked_date);
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getSubmissionId(): string
    {
        return $this->submission_id;
    }

    public function setSubmissionId(string $submission_id): void
    {
        $this->submission_id = $submission_id;
    }

    public function getBookmarkedDate(): int
    {
        return $this->bookmarked_date;
    }

    public function setBookmarkedDate(int $bookmarked_date): void
    {
        $this->bookmarked_date = $bookmarked_date;
    }

}