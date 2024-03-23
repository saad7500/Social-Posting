<?php

namespace app\models;

use PDO;

class Publications extends \app\core\Model {
    public $publication_id;
    public $profile_id;
    public $publication_title;
    public $publication_text;
    public $timestamp;
    public $publication_status;


    public function insert() {
        $SQL = 'INSERT INTO publication (profile_id, publication_title, publication_text, timestamp, publication_status) 
                VALUES (:profile_id, :publication_title, :publication_text, :timestamp, :publication_status)';

        $STMT = self::$_conn->prepare($SQL);
        $data = [
            'profile_id' => $this->profile_id,
            'publication_title' => $this->publication_title,
            'publication_text' => $this->publication_text,
            'timestamp' => date('Y-m-d H:i:s'),
            'publication_status' => $this->publication_status,
        ];

        $STMT->execute($data);
    }

    public function getAllPublications() {
        $SQL = 'SELECT * FROM publication WHERE publication_status = :publication_status';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['publication_status' => 'public']);
        $STMT->setFetchMode(PDO::FETCH_CLASS, 'app\\models\\Publications');
        return $STMT->fetchAll();
    }

    public function updatePublication($id, $title, $content, $status) {
        if ($title !== null && $content !== null && $status !== null) {
            $SQL = 'UPDATE publication SET publication_title = :title, publication_text = :content, publication_status = :status WHERE publication_id = :id';
            $STMT = self::$_conn->prepare($SQL);
            $STMT->execute(['id' => $id, 'title' => $title, 'content' => $content, 'status' => $status]);
        } else {
            exit("Title, content, or status cannot be null.");
        }
    }

    public function getPublicationById($id) {
        $SQL = 'SELECT * FROM publication WHERE publication_id = :id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['id' => $id]);
        return $STMT->fetch(PDO::FETCH_ASSOC);
    }

    public function getPublicationByIdAndProfile($id, $profile_id) {
        $SQL = 'SELECT * FROM publication WHERE publication_id = :id AND profile_id = :profile_id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['id' => $id, 'profile_id' => $profile_id]);
        $STMT->setFetchMode(PDO::FETCH_CLASS, 'app\\models\\Publications');
        return $STMT->fetch();
    }

    public function deletePublication($id) {
        $SQL = 'DELETE FROM publication WHERE publication_id = :id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['id' => $id]);
    }

    public function getAllPublicationTitles() {
        $SQL = 'SELECT publication_id, publication_title FROM publication WHERE publication_status = :status ORDER BY timestamp DESC';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['status' => 'public']);
        return $STMT->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicationsByProfileId($profileId) {
        $SQL = 'SELECT * FROM publication WHERE profile_id = :profile_id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['profile_id' => $profileId]);
        return $STMT->fetchAll(PDO::FETCH_CLASS, 'app\\models\\Publications');
    }

    public function searchPublications($query) {
        $SQL = "SELECT * FROM publication WHERE publication_status = 'public' AND (publication_title LIKE :query OR publication_text LIKE :query)";
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['query' => "%$query%"]);
        return $STMT->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
