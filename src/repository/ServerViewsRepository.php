<?php

class ServerViewsRepository extends Repository {
    public function submitViewForServer(string $server_id) {
        $stmt = $this->database->connect()->prepare('
            insert into public.server_views (server_id)
            values (?)
        ');
        $stmt->execute([
            $server_id
        ]);
    }

    public function getPopularServerIds(int $count = 5): array {
        $stmt = $this->database->connect()->prepare(
            'select server_id, count(date_viewed) as views
                   from server_views
                   group by server_id
                   order by views desc
                   limit :count;'
        );

        $stmt->bindParam(":count", $count, PDO::PARAM_INT);
        $stmt->execute();

        $ids = [];
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $ids[] = $row["server_id"];
        }
        return $ids;
    }
}