<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Server.php';

class ServerRepository extends Repository {
    const PAGE_ENTRIES_COUNT = 40;

    public function getServers(): array {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.servers s order by s.submission_date desc
        ');
        $stmt->execute();

        return $this->rowsToServers($stmt);
    }

    public function getServersBySubmitterId(string $user_id): array {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.servers WHERE submitter_id = :submitter_id
        ');
        $stmt->bindParam(":submitter_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();

        return $this->rowsToServers($stmt);
    }

    public function getPageCount(): int {
        $stmt = $this->database->connect()->prepare('
            SELECT count(*) from public.servers
        ');
        $stmt->execute();
        $entries_count = $stmt->fetchColumn();

        if (!$entries_count) {
            return 1;
        }

        return (int)($entries_count / self::PAGE_ENTRIES_COUNT) + 1;
    }

    public function getPage(int $page) {
        $to_skip = ($page - 1) * self::PAGE_ENTRIES_COUNT;
        $to_fetch = self::PAGE_ENTRIES_COUNT;

        $stmt = $this->database->connect()->prepare('
            SELECT *
            from public.servers s
            order by s.submission_date desc
            offset :toskip rows
            limit :lim
        ');
        $stmt->bindParam(":toskip", $to_skip, PDO::PARAM_INT);
        $stmt->bindParam(":lim", $to_fetch, PDO::PARAM_INT);
        $stmt->execute();

        return $this->rowsToServers($stmt);
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

    public function deleteServer(Server $server): bool {
        $stmt = $this->database->connect()->prepare('
            delete from public.servers s where s.submission_id = ?
        ');

        return $stmt->execute([
            $server->getSubmissionId()
        ]);
    }

    private function rowsToServers($stmt): array
    {
        $servers = [];
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
}