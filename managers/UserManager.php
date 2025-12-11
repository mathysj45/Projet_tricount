<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {
        $query = $this->db->prepare('SELECT * FROM user');
        $parameters = [

        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($result as $item)
        {
            $user = new User($item["email"], $item["password"], $item["username"], $item["role"], $item["id"]);
            $users[] = $user;
        }

        return $users;
    }

    public function findById(int $id) : ? User
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new User($item["email"], $item["password"], $item["username"], $item["role"], $item["id"]);
        }

        return null;
    }

    public function findByEmail(string $email) : ? User
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE email = :email');
        $parameters = [
            "email" => $email
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new User($item["email"], $item["password"], $item["username"], $item["role"], $item["id"]);
        }

        return null;
    }

    public function create(User $user) : void
    {
        $query = $this->db->prepare('INSERT INTO user (email, password, username, role) VALUES (:email, :password, :username, :role)');
        $parameters = [
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "username" => $user->getUsername(),
            "role" => $user->getRole()
        ];
        $query->execute($parameters);
    }

    public function update(User $user) : void
    {
        $query = $this->db->prepare('UPDATE user SET email = :email, password = :password, username = :username, role = :role WHERE id = :id');
        $parameters = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "username" => $user->getUsername(),
            "role" => $user->getRole()
        ];
        $query->execute($parameters);
    }

    public function delete(User $user) : void
    {
        $query = $this->db->prepare('DELETE FROM user WHERE id = :id');
        $parameters = [
            "id" => $user->getId()
        ];
        $query->execute($parameters);
    }
}
