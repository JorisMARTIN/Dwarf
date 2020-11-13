<?php

require_once(dirname(__FILE__).'/User.class.php');

class UserDAO extends DAO {
    function getUser(int $userId) : User {
        $query = 'SELECT * FROM "User" WHERE userid=:userId';
        $tmp = $this->db->prepare($query)->execute([':userId' => $userId]);
        if($tmp)
            return $tmp->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "User")[0];
        else
            return NULL;
    }

    function getUserPages(int $userId) : array {
        $query = 'SELECT * FROM "Page" WHERE userid=:userId';
        $tmp = $this->db->prepare($query)->execute([':userId' => $userId]);
        if($tmp) {
            return $tmp->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Page");
        } else {
            return NULL;
        }
    }

    function putUser(string $email, string $username, string $password, string $ip) : boolean {
        $data = [
            ':email' => $email,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':ips' => $ip
        ];
        $query = 'INSERT INTO "User" (nickname, email, password, creationdate, ips) VALUES (:username, :email, :password, NOW()::timestamp, ARRAY[:ip])';
        return $this->db->prepare($query)->execute($data);
    }

    function addIp(int $userId, string $ip) : boolean {
        $query = 'UPDATE "User" SET ips = ips || :ip WHERE userid=:userid';
        return $this->db->prepare($query)->execute([':userId' => $userId, ':ip' => $ip]);
    }

}

?>
