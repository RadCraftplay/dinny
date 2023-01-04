<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Server.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Bookmark.php';

class BookmarkRepository extends Repository {
    public function bookmark(User $user, Server $server): bool {
        $stmt = $this->database->connect()->prepare('
            insert into public.saved_servers (user_id, submission_id)
            values (?, ?)
        ');

        return $stmt->execute([
            $user->getUserId(),
            $server->getSubmissionId()
        ]);
    }

    public function unbookmark(User $user, Server $server): bool {
        $stmt = $this->database->connect()->prepare('
            delete from public.saved_servers
            where user_id = ? and submission_id = ? 
        ');

        return $stmt->execute([
            $user->getUserId(),
            $server->getSubmissionId()
        ]);
    }

    public function isBookmarked(User $user, Server $server): bool {
        $uid = $user->getUserId();
        $sid = $server->getSubmissionId();
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.saved_servers
            where user_id = :uid and submission_id = :sid
        ');
        $stmt->bindParam(":uid", $uid, PDO::PARAM_STR);
        $stmt->bindParam(":sid", $sid, PDO::PARAM_STR);

        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return !!$item;
    }

    public function getUserBookmarkedServerIds(User $user): array {
        $bookmarks = $this->getUserBookmarks($user);
        $ids = [];

        foreach ($bookmarks as $bookmark) {
            $ids[] = $bookmark->getSubmissionId();
        }

        return $ids;
    }

    public function getUserBookmarks(User $user): array {
        $id = $user->getUserId();
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.saved_servers
            where user_id = :id
        ');
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();

        return $this->rowsToBookmarks($stmt);
    }

    private function rowsToBookmarks($stmt): array {
        $bookmarks = [];
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $bookmarks[] = $this->rowsToBookmark($row);
        }

        return $bookmarks;
    }

    private function rowsToBookmark(array $row): Bookmark {
        return new Bookmark(
            $row["user_id"],
            $row["submission_id"]
        );
    }
}