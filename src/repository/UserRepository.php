<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../common/Random.php';

class UserRepository extends Repository
{
    public function getUser(string $email) : ?User {
        $stmt = $this->database->connect()->prepare('
            SELECT u.user_id, u.email, u.username, u.user_role_id, uc.password_hash, uc.password_salt
            from public.users u
                left join user_credentials uc on uc.credentials_id = u.credentials_id
            WHERE email = :email
        ');
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
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
            $user["password_salt"],
            $user["user_role_id"]
        );
    }

    public function getUserById(string $id) : ?User {
        $stmt = $this->database->connect()->prepare('
            SELECT u.user_id, u.email, u.username, u.user_role_id, uc.password_hash, uc.password_salt
            from public.users u
                left join user_credentials uc on uc.credentials_id = u.credentials_id
            WHERE user_id = :id
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
            $user["password_salt"],
            $user["user_role_id"]
        );
    }

    public function createUser(string $email, string $username, string $password): bool {
        $salt = Random::getRandomString(7);
        $password_with_hash = $password . $salt;
        $hash = password_hash($password_with_hash, PASSWORD_BCRYPT);

        $stmt = $this->database->connect()->prepare('
            with cred_id as (
                insert into public.user_credentials (password_hash, password_salt)
                    values (?, ?) RETURNING credentials_id
            )
            insert into public.users (email, username, credentials_id)
            select ?, ?, credentials_id
            from cred_id
            returning user_id
        ');

        return $stmt->execute([
            $hash,
            $salt,
            $email,
            $username
        ]);
    }

}