<?php
namespace app\controllers;

use app\core\Controller;

class Publications extends Controller
{

    public function index()
    {
        $publicationModel = new \app\models\Publications();

        if (isset ($_GET['q']) && !empty ($_GET['q'])) {
            $publicationsData = $publicationModel->searchPublications($_GET['q']);
        } else {
            $publicationsData = $publicationModel->getAllPublicationTitles();
        }

        $publications = [];
        foreach ($publicationsData as $publication) {
            $publications[] = [
                "publication_id" => $publication['publication_id'],
                'publication_title' => $publication['publication_title'],
            ];
        }

        $this->view('Publications/publications', ['publications' => $publications]);
    }


    #[\app\filters\HasProfile]
    public function create()
    {
        $this->view('Publications/create');
    }

    #[\app\filters\HasProfile]
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $publication = new \app\models\Publications();

            $profile = new \app\models\Profile();
            $profile = $profile->getForUser($_SESSION['user_id']);
            $publication->profile_id = $profile->profile_id;
            $publication->publication_title = $_POST['title'];
            $publication->publication_text = $_POST['content'];
            $publication->timestamp = date('Y-m-d H:i:s');
            $publication->publication_status = $_POST['status'];
            $publication->insert();
            header('Location: /Publications/index');
            exit();
        }
    }

    public function content($id)
    {
        $publicationModel = new \app\models\Publications();
        $publication = $publicationModel->getPublicationById($id);

        if (!$publication) {
            exit ("Publication not found.");
        }

        $commentModel = new \app\models\Comment();
        $comments = $commentModel->getCommentsByPublicationId($id);


        $publicationArray[] = [
            'publication_id' => $publication['publication_id'],
            'publication_title' => $publication['publication_title'],
            'publication_text' => $publication['publication_text'],
            'timestamp' => $publication['timestamp'],
        ];

        $commentsArray = [];
        foreach ($comments as $comment) {
            $commentsArray[] = [
                'publication_comment_id' => $comment->publication_comment_id,
                'profile_id' => $comment->profile_id,
                'publication_id' => $comment->publication_id,
                'timestamp' => $comment->timestamp,
                'comment' => $comment->comment,
            ];
        }
        $this->view('Publications/view', ['publications' => $publicationArray, 'comments' => $commentsArray]);
    }

    #[\app\filters\HasProfile]
    public function edit($id)
    {
        $user_id = $_SESSION['user_id'];

        $profileModel = new \app\models\Profile();
        $profile = $profileModel->getForUser($user_id);

        if ($profile) {
            $profile_id = $profile->profile_id;

            $publicationModel = new \app\models\Publications();
            $publication = $publicationModel->getPublicationByIdAndProfile($id, $profile_id);

            if (!$publication) {
                exit ("error");
            }

            $this->view('Publications/edit', ['publication_id' => $id, 'publication' => $publication]);
        } else {
            exit ("error");
        }
    }

    #[\app\filters\HasProfile]
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset ($_POST['title']) ? $_POST['title'] : null;
            $content = isset ($_POST['content']) ? $_POST['content'] : null;
            $status = isset ($_POST['status']) ? $_POST['status'] : null;

            $publicationModel = new \app\models\Publications();
            $publicationModel->updatePublication($id, $title, $content, $status);
            header('Location: /Publications/index');
            exit();
        } else {
            exit ("error");
        }
    }

    #[\app\filters\HasProfile]
    public function delete($id)
    {
        $user_id = $_SESSION['user_id'];

        $profileModel = new \app\models\Profile();
        $profile = $profileModel->getForUser($user_id);

        if ($profile) {
            $profile_id = $profile->profile_id;

            $publicationModel = new \app\models\Publications();
            $publication = $publicationModel->getPublicationByIdAndProfile($id, $profile_id);

            if ($publication) {
                $publicationModel->deletePublication($id);

                header('Location: /Publications/index');
                exit();
            } else {
                exit ("error");
            }
        } else {
            exit ("error");
        }
    }


    #[\app\filters\HasProfile]
    public function addComment($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {



            $comment = new \app\models\Comment();

            $profile = new \app\models\Profile();
            $profile = $profile->getForUser($_SESSION['user_id']);

            $comment->profile_id = $profile->profile_id;
            $comment->publication_id = $id;
            $comment->timestamp = date('Y-m-d H:i:s');
            $comment->comment = $_POST['comment_text'];


            $comment->addComment();


            header("Location: /Publications/content/$id");
            exit();
        }
    }

    #[\app\filters\HasProfile]
    public function editComment($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_comment_text = isset ($_POST['new_comment_text']) ? $_POST['new_comment_text'] : null;

            if ($new_comment_text) {
                $commentModel = new \app\models\Comment();
                $comment = $commentModel->getCommentById($id);

                $user_id = $_SESSION['user_id'];
                $profileModel = new \app\models\Profile();
                $profile = $profileModel->getForUser($user_id);

                if (!$comment || $comment->profile_id != $profile->profile_id) {
                    exit ("error");
                }

                $commentModel->editComment($id, $new_comment_text);

                header("Location: /Publications/content/{$comment->publication_id}");
                exit();
            } else {
                exit ("eror");
            }
        }
    }

    #[\app\filters\HasProfile]
    public function deleteComment($id)
    {
        $commentModel = new \app\models\Comment();
        $comment = $commentModel->getCommentById($id);

        $user_id = $_SESSION['user_id'];
        $profileModel = new \app\models\Profile();
        $profile = $profileModel->getForUser($user_id);

        if (!$comment || $comment->profile_id != $profile->profile_id) {
            exit ("error");
        }

        $commentModel->deleteComment($id);

        header("Location: /Publications/content/{$comment->publication_id}");
    }


}
?>