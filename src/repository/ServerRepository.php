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
            SELECT *
            from public.servers
            WHERE submitter_id = :submitter_id
            order by submission_date desc
        ');
        $stmt->bindParam(":submitter_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();

        return $this->rowsToServers($stmt);
    }

    public function getServersByIds(array $server_ids): array {
        if (count($server_ids) == 0) {
            return [];
        }

        $ids = '';
        $query = "
            SELECT *
            from public.servers
            where submission_id in (%s)
        ";

        foreach ($server_ids as $id) {
            $ids .= ", '" . $id . "'";
        }
        $ids = ltrim($ids, ", ");

        $stmt = $this->database->connect()->prepare(sprintf($query, $ids));
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

    public function updateServer($server_id, $title, $service_type_id, $address, $description): bool {
        $stmt = $this->database->connect()->prepare('
            update public.servers
            set title = ?, service_type_id = ?, address = ?, description = ?
            where submission_id = ?
        ');

        return $stmt->execute([
            $title,
            $service_type_id,
            $address,
            $description,
            $server_id
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

    public function getServersByQuery(string $query): array
    {
        $searchString = '%' . strtolower($query) . '%';
        $stmt = $this->database->connect()->prepare('
            SELECT s.submission_id, s.submitter_id, s.title, s.service_type_id, s.address, s.description, s.submission_date, s.expiration_date
            from public.servers s
                left join users u on u.user_id = s.submitter_id
                     where lower(s.title) like :searchString
                     or lower(s.description) like :searchString
                     or lower(u.username) like :searchString
            order by s.submission_date desc
        ');
        $stmt->bindParam(":searchString", $searchString, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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