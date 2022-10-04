<?php
    function addComment($postId, $userId, $comment)
    {

        require_once 'db/conn.php';
        $db = new connection();
        $dbConnection = $db->getConnection();



        $commentBody = mysqli_real_escape_string($dbConnection, $comment);
        $postId = mysqli_real_escape_string($dbConnection, $postId);
        $userId = mysqli_real_escape_string($dbConnection, $userId);


        $sql = "INSERT INTO comments (body, post_id, user_id) VALUES ('$comment', '$postId', '$userId')";
        if ($dbConnection->query($sql) === TRUE) {

            return true;
        } else {
            return false;
        }
    }

