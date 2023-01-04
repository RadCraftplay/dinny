<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/ServiceType.php';

class ServiceTypeRepository extends Repository {
    public function getServiceTypes(): array {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.service_types order by service_type_id
        ');
        $stmt->execute();

        return $this->rowsToServiceTypes($stmt);
    }

    public function getServiceTypeById(int $id): ?ServiceType {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.service_types where service_type_id = :id
        ');
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();

        $service_type = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$service_type) {
            return null;
        }

        return $this->rowToServiceType($service_type);
    }

    public function getServiceTypeByName(int $name): ?ServiceType {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.service_types where service_name = :name
        ');
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();

        $service_type = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$service_type) {
            return null;
        }

        return $this->rowToServiceType($service_type);
    }

    private function rowsToServiceTypes($stmt): array {
        $service_types = [];
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $service_types[] = $this->rowToServiceType($row);
        }

        return $service_types;
    }

    private function rowToServiceType(array $row): ServiceType {
        return new ServiceType(
            $row["service_type_id"],
            $row["service_name"],
            $row["service_image_name"]
        );
    }
}