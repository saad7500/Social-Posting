<?php

namespace app\models;

use PDO;

class Comment extends \app\core\Model
{
    public $publication_comment_id;
    public $profile_id;
    public $publication_id;
    public $comment;
    public $timestamp;

    public function addComment()
    {
        $SQL = 'INSERT INTO publication_comment (profile_id, publication_id, comment, timestamp) 
            VALUES (:profile_id, :publication_id, :comment, :timestamp)';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute([
            'profile_id' => $this->profile_id,
            'publication_id' => $this->publication_id,
            'comment' => $this->comment,
            'timestamp' => $this->timestamp,
        ]);
    }


    public function getCommentsByPublicationId($id)
    {
        $SQL = 'SELECT * FROM publication_comment WHERE publication_id = :id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['id' => $id]);
        return $STMT->fetchAll(PDO::FETCH_OBJ);
    }

    public static function editComment($publication_comment_id, $new_comment)
    {
        $SQL = 'UPDATE publication_comment SET comment = :comment WHERE publication_comment_id = :publication_comment_id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute([
            'publication_comment_id' => $publication_comment_id,
            'comment' => $new_comment_text
        ]);
    }

    public static function deleteComment($publication_comment_id)
    {
        $SQL = 'DELETE FROM publication_comment WHERE publication_comment_id = :publication_comment_id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['publication_comment_id' => $publication_comment_id]);
    }

    public static function getCommentById($publication_comment_id)
    {
        $SQL = 'SELECT * FROM publication_comment WHERE publication_comment_id = :publication_comment_id';
        $STMT = self::$_conn->prepare($SQL);
        $STMT->execute(['publication_comment_id' => $publication_comment_id]);
        return $STMT->fetch(PDO::FETCH_OBJ);
    }

}

?>
