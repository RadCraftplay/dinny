<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../common/Random.php';

class UserRepository extends Repository
{
    public function getUser(string $email) : ?User {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.users WHERE email = :email
        ');
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return new User(
            $user["user_id"],
            $email,
            $user["username"],
            $user["password_hash"],
            $user["password_salt"]
        );
    }

    public function getUserById(string $id) : ?User {
        $stmt = $this->database->connect()->prepare('
            SELECT * from public.users WHERE user_id = :id
        ');
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return new User(
            $user["user_id"],
            $user["email"],
            $user["username"],
            $user["password_hash"],
            $user["password_salt"]
        );
    }

    public function createUser(string $email, string $username, string $password): bool {
        $salt = Random::getRandomString(7);
        $password_with_hash = $password . $salt;
        $hash = password_hash($password_with_hash, PASSWORD_BCRYPT);

        $stmt = $this->database->connect()->prepare('
            insert into public.users (email, username, password_hash, password_salt)
            values (?, ?, ?, ?)
        ');

        return $stmt->execute([
            $email,
            $username,
            $hash,
            $salt
        ]);
    }

}