<?php

class Server {

    private $submission_id;
    private $submitter_id;
    private $title;
    private $service_type_id;
    private $address;
    private $description;

    public function __construct($submission_id, $submitter_id, $title, $service_type_id, $address, $description)
    {
        $this->submission_id = $submission_id;
        $this->submitter_id = $submitter_id;
        $this->title = $title;
        $this->service_type_id = $service_type_id;
        $this->address = $address;
        $this->description = $description;
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


}