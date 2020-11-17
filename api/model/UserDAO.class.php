<?php

require_once(dirname(__FILE__) . '/DAO.class.php');
require_once(dirname(__FILE__).'/User.class.php');

class UserDAO extends DAO {
    function getUser(int $userId) : User {
        $query = 'SELECT * FROM "User" WHERE userid=:userId';
        $tmp = $this->db->prepare($query);
        if($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "User")[0];
        } else {
            return NULL;
        }
    }

    function getUserPages(int $userId) : array {
        $query = 'SELECT * FROM "Page" WHERE userid=:userId';
        $tmp = $this->db->prepare($query);
        if($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "Page");
        } else {
            return NULL;
        }
    }

    function putUser(string $email, string $username, string $password, string $ip) : bool {
        $query = 'INSERT INTO "User" (nickname, email, password, creationdate, ips) VALUES (:username, :email, :password, CURRENT_TIMESTAMP, ARRAY[:ip])';
        return $this->db->prepare($query)->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':ip' => $ip
        ]);
    }

    function addIp(int $userId, string $ip) : bool {
        $query = 'UPDATE "User" SET ips = ips || :ip WHERE userid=:userId';
        return $this->db->prepare($query)->execute([':userId' => $userId, ':ip' => $ip]);
    }

    //get userId from email & plain password
    function logUser(string $email, string $password) : int {
        $query = 'SELECT userid FROM "User" WHERE email=:email AND password=:password';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT)
        ])) {
            $userId = $tmp->fetchColumn();
            if($userId == false) return -1;
            else return $userId;
        } else {
            return -1;
        }
    }

}
