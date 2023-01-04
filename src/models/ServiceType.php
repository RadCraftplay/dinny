<?php

class ServiceType {

    private $service_type_id;
    private $service_name;
    private $service_image_name;

    public function __construct(int $service_type_id, string $service_name, string $service_image_name)
    {
        $this->service_type_id = $service_type_id;
        $this->service_name = $service_name;
        $this->service_image_name = $service_image_name;
    }

    public function getServiceTypeId(): int
    {
        return $this->service_type_id;
    }

    public function setServiceTypeId(int $service_type_id): void
    {
        $this->service_type_id = $service_type_id;
    }

    public function getServiceName(): string
    {
        return $this->service_name;
    }

    public function setServiceName(string $service_name): void
    {
        $this->service_name = $service_name;
    }

    public function getServiceImageName(): string
    {
        return $this->service_image_name;
    }

    public function setServiceImageName(string $service_image_name): void
    {
        $this->service_image_name = $service_image_name;
    }


}