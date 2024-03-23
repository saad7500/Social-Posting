<?php
namespace app\models;

use PDO;

class User extends \app\core\Model {
    public $user_id;
    public $username;
    public $password_hash;

    
    public function insert() {
        $SQL = 'INSERT INTO user (username, password_hash) VALUES (:username, :password_hash)';
        $STMT = self::$_conn->prepare($SQL);
        $data = [
            'username' => $this->username,
            'password_hash' => $this->password_hash
        ];
        $STMT->execute($data);
    }

    public function get($username) {
        $SQL = 'SELECT * FROM user WHERE username = :username';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['username' => $username]);
        $STMT->setFetchMode(PDO::FETCH_CLASS, 'app\models\User');
        return $STMT->fetch();
    }

    public function getById($user_id) {
        $SQL = 'SELECT * FROM user WHERE user_id = :user_id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['user_id' => $user_id]);
        $STMT->setFetchMode(PDO::FETCH_CLASS, 'app\models\User');
        return $STMT->fetch();
    }

    public function update() {
        $SQL = 'UPDATE user SET username = :username, password_hash = :password_hash WHERE user_id = :user_id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute((array)$this);
    }

}
?>
