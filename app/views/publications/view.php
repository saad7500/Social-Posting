<!DOCTYPE html>
<html>

<head>
    <title>Publications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</head>

<body>
<div class="container">
<h1>All Publications</h1>
<div class="container2">
<?php if (!empty ($publications)): ?>
<?php foreach ($publications as $publication): ?>
<div>
<?php if (isset ($publication['publication_title'])): ?>
    <h2><?php echo $publication['publication_title']; ?></h2>
    <p><?php echo $publication['publication_text']; ?></p>
    <p><?php echo $publication['timestamp']; ?></p>
    <p>
        <a href="/Publications/edit/<?php echo $publication['publication_id']; ?>">Modify</a>
        <a href="/Publications/delete/<?php echo $publication['publication_id']; ?>">Delete</a>
    </p>
    <?php endif; ?>

    <h5>Comments</h5>
    <?php if (!empty ($comments)): ?>
    <?php foreach ($comments as $comment): ?>
    <div>
    <p><?php echo $comment['comment']; ?></p>
    <p><?php echo $comment['timestamp']; ?></p>
    <?php if (isset ($_SESSION['user_id'])): ?>
    <?php
        $profile = new \app\models\Profile();
        $commentProfile = $profile->getForUser($_SESSION['user_id']);
        if ($commentProfile && $comment['profile_id'] == $commentProfile->profile_id): ?>
        <form action="/Publications/editComment/<?php echo $comment['publication_comment_id']; ?>" method="POST">
            <textarea name="new_comment_text" rows="4" cols="50" required><?php echo $comment['comment']; ?></textarea><br><br>
            <input type="submit" value="Save Changes">
        </form>
    <a href="/Publications/deleteComment/<?php echo $comment['publication_comment_id']; ?>">Delete</a>
    <?php endif; ?>
    <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <h2>Add Comment</h2>
    <form action='/Publications/addComment/<?php echo $publication['publication_id']; ?>' method="POST">
        <textarea name="comment_text" id="comment_text" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" value="Add Comment">
    </form>
    </div>
    <?php endforeach; ?>
     <?php else: ?>
    <p>Nothing</p>
    <?php endif; ?>
    </div>
    <a href="/Publications/index">Return To publications</a>
    </div>
</body>

</html>