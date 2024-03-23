<html>
<head>
    <title><?= $profile->first_name ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Welcome</h1>
        <dl>
            <dt>First Name:</dt>
            <dd><?= $profile->first_name ?></dd>
            <dt>Middle Name:</dt>
            <dd><?= $profile->middle_name ?></dd>
            <dt>Last Name:</dt>
            <dd><?= $profile->last_name ?></dd>
        </dl>
        <h2>Your Publications:</h2>
        <?php if (!empty($publications)): ?>
            <ul>
                <?php foreach ($publications as $publication): ?>
                    <li>
                        <a href="/Publications/content/<?= $publication->publication_id ?>">
                            <?= $publication->publication_title ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nothing yet.</p>
        <?php endif; ?>
        <a href="/Profile/modify">Modify profile</a> <br>
        <a href="/Profile/delete">Delete profile</a> <br>
        <a href="/Publications/index">Publications</a> <br>
    </div>
</body>
</html>
