<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Server.php';

class ServerRepository extends Repository {
    public function getServers(): array {
        $servers = [];

        $stmt = $this->database->connect()->prepare('
            SELECT * from public.servers s order by s.submission_date desc
        ');
        $stmt->execute();
        $server_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($server_rows as $server) {
            $servers[] = new Server(
                $server["submission_id"],
                $server["submitter_id"],
                $server["title"],
                $server["service_type_id"],
                $server["address"],
                $server["description"],
                $server["submission_date"],
                $server["expiration_date"]
            );
        }

        return $servers;
    }

    public function getServerById(string $id) : ?Server {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.servers where submission_id = :id
        ');
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();

        $server = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$server) {
            return null;
        }

        return new Server(
            $server["submission_id"],
            $server["submitter_id"],
            $server["title"],
            $server["service_type_id"],
            $server["address"],
            $server["description"],
            $server["submission_date"],
            $server["expiration_date"]
        );
    }

    public function submitServer($submitter_id, $title, $service_type_id, $address, $description): bool {
        $stmt = $this->database->connect()->prepare('
            insert into public.servers (submitter_id, title, service_type_id, address, description)
            values (?, ?, ?, ?, ?)
        ');

        return $stmt->execute([
            $submitter_id,
            $title,
            $service_type_id,
            $address,
            $description
        ]);
    }
}