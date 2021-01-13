<?php

require_once(dirname(__FILE__) .'/DAO.class.php');
require_once(dirname(__FILE__).'/User.class.php');

class UserDAO extends DAO {

    /**
     * Get an user 
     * 
     * @param int $userId ID of the user
     * 
     * @return User User Object | NULL = ❌
     */
    function getUser(int $userId) : ?User {
        $query = 'SELECT * FROM "User" WHERE userid=:userId';
        $tmp = $this->db->prepare($query);
        if($tmp->execute([':userId' => $userId])) {
            return $tmp->fetchAll(PDO::FETCH_CLASS, "User")[0];
        } else {
            return NULL;
        }
    }

    /**
     * Add a new user
     * 
     * @param string $email Email of the user
     * @param string $username Name of the user
     * @param string $password Password of the user
     * @param string $ip The current IP address of the user
     * @param string $birthdate The birthdate of the user 🎂
     * 
     * @return int The new user ID | -1 = ❌
     */
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
            return -1;
        }
    }

    /**
     * Add a new ip adress of the user
     * 
     * @param int $userId ID of the user
     * @param string $ip The new ip address
     * 
     * @return bool true = ✅ | false = ❌
     */
    function addIp(int $userId, string $ip) : bool {
        $query = 'UPDATE "User" SET ips = array_append(ips, :ip) WHERE userid=:userId';
        return $this->db->prepare($query)->execute([':userId' => $userId, ':ip' => $ip]);
    }

    /**
     * Get user ID from his email and password
     * 
     * @param string $email The user email
     * @param string $password The user password
     * 
     * @return int The user ID | -1 = ❌
     */
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

    /**
     * Delete an user
     * 
     * @param int $userId ID of the user
     * 
     * @return bool true = ✅ | false = ❌
     */
    function removeUser(int $userId) : bool {
        $query = 'DELETE FROM "User" WHERE userId = :userId';
        return $this->db->prepare($query)->execute([
        ':userId' => $userId
        ]);
    }

    /**
     * Update a user
     * 
     * @param string $userId Id of the user
     * @param string $email Email of the user
     * @param string $password Password of the user
     * @param string $birthdate The birthdate of the user 🎂
     * 
     * @return int true = ✅ | false = ❌
     */
    function updateUser(string $userId, string $email, string $password, string $birthdate) : bool {
        $query = "UPDATE \"User\" SET email = :email, password = :password, birthdate = TO_DATE(:birthdate, 'YYYY-MM-DD') WHERE userid = :userid";
        $tmp = $this->db->prepare($query);
        if ($tmp->execute([
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':birthdate' => $birthdate,
            ':userid' => $userId
        ])) {
            return true;
        } else {
            return false;
        }
    }

}
