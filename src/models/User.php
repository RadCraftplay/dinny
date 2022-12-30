<?php

class User {

    private $user_id;
    private $email;
    private $username;
    private $password_hash;
    private $password_salt;
    private $user_role_id;

    public function __construct(string $user_id, string $email, string $username, string $password_hash, string $password_salt, int $user_role_id)
    {
        $this->user_id = $user_id;
        $this->email = $email;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->password_salt = $password_salt;
        $this->user_role_id = $user_role_id;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    public function getPasswordSalt(): string
    {
        return $this->password_salt;
    }

    public function setPasswordSalt(string $password_salt): void
    {
        $this->password_salt = $password_salt;
    }

    public function getUserRoleId(): int
    {
        return $this->user_role_id;
    }

    public function setUserRoleId(int $user_role_id): void
    {
        $this->user_role_id = $user_role_id;
    }

    public function isAdmin(): bool {
        return $this->user_role_id == 2;
    }
}