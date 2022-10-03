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
            $msg = 'Comment created successfully';

            $interactionsSQL = "INSERT INTO interactions (user_id, post_id, interaction_type) VALUES ('$userId', '$postId', 'comment')";
            if ($dbConnection->query($interactionsSQL) === TRUE) {
               echo "New Document created successfully";
               echo "<br>ID: " . $dbConnection->insert_id;
               echo "<br>Body: " . $comment;
                echo "<br>Post ID: " . $postId;
                echo "<br>User ID: " . $userId;
            } else {
                $err = 'Error creating comment';
            }
        } else {
            $err = 'Error creating comment';
        }
        if(isset($err)){
            return $err;
        } else {
            return true;
        }
    }

