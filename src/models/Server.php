<?php

class Server {

    private $submission_id;
    private $submitter_id;
    private $title;
    private $service_type_id;
    private $address;
    private $description;
    private $submission_date;
    private $expiration_date;

    public function __construct($submission_id, $submitter_id, $title, $service_type_id, $address, $description, $submission_date, $expiration_date)
    {
        $this->submission_id = $submission_id;
        $this->submitter_id = $submitter_id;
        $this->title = $title;
        $this->service_type_id = $service_type_id;
        $this->address = $address;
        $this->description = $description;
        $this->submission_date = $submission_date;
        $this->expiration_date = $expiration_date;
    }

    public function getSubmissionId()
    {
        return $this->submission_id;
    }

    public function setSubmissionId(string $submission_id): void
    {
        $this->submission_id = $submission_id;
    }

    public function getSubmitterId()
    {
        return $this->submitter_id;
    }

    public function setSubmitterId(string $submitter_id): void
    {
        $this->submitter_id = $submitter_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getServiceTypeId()
    {
        return $this->service_type_id;
    }

    public function setServiceTypeId(int $service_type_id): void
    {
        $this->service_type_id = $service_type_id;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSubmissionDate()
    {
        return $this->submission_date;
    }

    public function setSubmissionDate($submission_date): void
    {
        $this->submission_date = $submission_date;
    }

    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    public function setExpirationDate($expiration_date): void
    {
        $this->expiration_date = $expiration_date;
    }

    public function canBeRemovedBy($user_id): bool {
        // TODO: Allow removal by administration/moderation
        return $user_id == $this->submitter_id;
    }
}