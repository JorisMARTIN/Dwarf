<?php

require_once(dirname(__FILE__) .'/DAO.class.php');
require_once(dirname(__FILE__).'/User.class.php');

class UserDAO extends DAO {
    function getUser(int $userId) : ?User {
        $query = 'SELECT * FROM "User" WHERE userid=:userId';
        $tmp = $this->db->prepare($query);
        if($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "User")[0];
        } else {
            return NULL;
        }
    }

    function putUser(string $email, string $username, string $password, string $ip, string $birthdate) : int {
        $query = "INSERT INTO \"User\" (nickname, email, password, creationdate, ips, birthdate) VALUES (:username, :email, :password, CURRENT_TIMESTAMP, ARRAY[:ip], TO_DATE(:birthdate, 'YYYY-MM-DD')) RETURNING userid";
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':ip' => $ip,
            ':birthdate' => $birthdate
        ])) {
            $userId = $tmp->fetchColumn();
            return $userId;
        } else {
            var_dump($tmp->errorInfo());
            return -1;
        }
    }

    function addIp(int $userId, string $ip) : bool {
        $query = 'UPDATE "User" SET ips = ips || :ip WHERE userid=:userId';
        return $this->db->prepare($query)->execute([':userId' => $userId, ':ip' => $ip]);
    }

    //get userId from email & plain password
    function logUser(string $email, string $password) : int {
        $query = 'SELECT userid, password FROM "User" WHERE email=:email';
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([':email' => $email])) {
            $userInfos = $tmp->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $userInfos['password'])) {
                return $userInfos['userid'];
            } else {
                return -1;
            }
        } else {
            return -1;
        }
    }

    function removeUser(int $userId) : bool {
        $query = 'DELETE FROM "User" WHERE userId = :userId';
        return $this->db->prepare($query)->execute([
        ':userId' => $userId
        ]);
    }

}
