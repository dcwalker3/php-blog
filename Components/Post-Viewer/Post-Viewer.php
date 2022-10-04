<?php
    require_once 'db/conn.php';
    $db = new connection();
    $dbConnection = $db->getConnection();

    if($_SERVER["REQUEST_METHOD"] == "GET"){


        $postID = $_GET['id'];
        $postID = mysqli_real_escape_string($dbConnection, $postID);

        $userID = $_SESSION['id'];
        $comments = [];
        $sql = "SELECT comments.id as commentID, comments.user_id, comments.post_id, comments.body, comments.created_at as comment_created, users.username, users.id as userID
                FROM comments
                INNER JOIN users ON comments.user_id = users.id
                WHERE comments.post_id = '$postID'
                ORDER BY comments.created_at DESC";

        $commentResult = $dbConnection->query($sql);

        $interactionSQL = "SELECT * FROM interactions WHERE post_id = '$postID' AND user_id = '$userID'";
        $interactionResult = $dbConnection->query($interactionSQL);
        $interaction = mysqli_fetch_assoc($interactionResult);
        $interaction['interaction_type'] = $interaction['interaction_type'] ?? null;


        $sql = "SELECT posts.id as postID, posts.user_id, posts.title, posts.body, posts.type, posts.likes, posts.dislikes, posts.created_at as post_created, users.username, users.id as userID
                FROM posts
                INNER JOIN users ON posts.user_id = users.id
                WHERE posts.id = '$postID'";

        $postResult = $dbConnection->query($sql);
        if(mysqli_num_rows($postResult) > 0){
            $post = mysqli_fetch_assoc($postResult);

            $humanReadableDate = date('F j, Y', strtotime($post['post_created']));
            $capitalizedType = ucfirst($post['type']);

            echo "<div class='post-container'>
                    <div class='post'>
                        <h3 class='post-title'>{$post['title']}</h3>
                        <div class='post-body'>{$post['body']}</div><br/>
                        <p class='post-author'>Created By: {$post["username"]}</p>
                        <sub class='post-upload-date'>Uploaded: {$humanReadableDate}</sub>
                        <span class='tag {$post["type"]}'>{$capitalizedType}</span>
                        <div class='post-footer'>
                            <form action='' method='post'>
                                <div class='like-btn'>
                                    <input type='hidden' name='post_id' value='{$post['postID']}'>
                                    <input type='hidden' name='current-likes' value='{$post['likes']}'>
                                    <input type='hidden' name='user_id' value='{$post["user_id"]}'>
                                    <button type='submit' name='like' value='like'
                                    class=". ($interaction['interaction_type'] == 'like' ? 'liked' : '') .">
                                        <i class='fas fa-thumbs-up'></i>
                                    </button>
                                    <span class='like-count'>{$post['likes']}</span>
                                </div>
                            </form>
                            <form action='' method='post'>
                                <div class='dislike-btn'>
                                    <input type='hidden' name='post_id' value='{$post['postID']}'>
                                    <input type='hidden' name='current-dislikes' value='{$post['dislikes']}'>
                                    <input type='hidden' name='user_id' value='{$_SESSION['id']}'>
                                    <button type='submit' name='dislike' value='lislike' 
                                        class=". ($interaction['interaction_type'] == 'dislike' ? 'disliked' : '') .">
                                        <i class='fas fa-thumbs-down'></i>
                                    </button>
                                    <span class='dislike-count'>{$post['dislikes']}</span>
                                </div>
                            </form>                                
                        </div>
                    </div>
                    <div class='comments-container' id='commentsContainer'>
                        <h3 class='comments-title'>Comments</h3>
                        <div class='comments'>
                            <form action='' method='post'>
                                <input type='hidden' name='post_id' value='{$post['postID']}'>
                                <input type='hidden' name='user_id' value='{$_SESSION['id']}'>
                                <input type='text' name='comment' id='comment' placeholder='Write a comment...' required></input>
                                <button type='submit' name='submit-comment'>Submit</button>
                            </form><br/><br/><br>
                            <div class='comment-list'>";
                                if(mysqli_num_rows($commentResult) > 0){
                                    while($comment = mysqli_fetch_assoc($commentResult)){
                                        $humanReadableDate = date('F j, Y', strtotime($comment['comment_created']));
                                        echo "<div class='comment'>
                                                <sub class='comment-author'>{$comment['username']}:</sub>
                                                <hr/>
                                                <p class='comment-body'>{$comment['body']}</p>
                                                <span class='comment-footer'>
                                                    <sub class='comment-upload-date'>Uploaded: {$humanReadableDate}</sub>
                                                </span>
                                                
                                            </div>";
                                    }
                                }
                            echo "</div>
                        </div>
                    </div>        
                  </div>
                ";
        } else {
            echo "<div class='alert alert-danger' style='
                    text-align: center;
                    width: min-content;
                    padding: 1.5rem;
                    margin: auto;
                '>No post found.</div>";
        }

    } elseif($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['comment'])) {
            $commentBody = $_POST['comment'];
            $userID = $_POST['user_id'];
            $postID = $_POST['post_id'];

            require_once 'utils/addComment.php';

            $results = addComment($postID, $userID, $commentBody);
            if (!$results) {
                echo "
                    <div class='alert alert-danger' style='
                        text-align: center;
                        width: min-content;
                        padding: 1.5rem;
                        margin: auto;
                    '>
                        Error Uploading Comment. Please Try Again.
                    </div>
                ";
            } else {
                echo "<script>window.location.href = 'post-viewer?id={$postID}';</script>";
            }
        } elseif(isset($_POST["like"])){
            # Check if user has already liked the post
            $sql = "SELECT * FROM interactions WHERE user_id = '{$_SESSION['id']}' AND post_id = '{$_POST['post_id']}'";
            $result = $dbConnection->query($sql);
            if(mysqli_num_rows($result) > 0){
                $result = mysqli_fetch_assoc($result);
                if($result["interaction_type"] == "dislike"){
                    $sql = "UPDATE interactions SET interaction_type = 'like' WHERE user_id = '{$_SESSION['id']}' AND post_id = '{$_POST['post_id']}'";
                    $dbConnection->query($sql);
                    $sql = "UPDATE posts SET likes = likes + 1, dislikes = dislikes - 1 WHERE id = '{$_POST['post_id']}'";
                    $dbConnection->query($sql);
                    echo "<script>window.location.href = 'post-viewer?id={$_POST['post_id']}';</script>";
                }
            } else {
                $sql = "INSERT INTO interactions (user_id, post_id, interaction_type) VALUES ('{$_SESSION['id']}', '{$_POST['post_id']}', 'like')";
                $dbConnection->query($sql);
                $sql = "UPDATE posts SET likes = likes + 1 WHERE id = '{$_POST['post_id']}'";
                $dbConnection->query($sql);
                echo "<script>window.location.href = 'post-viewer?id={$_POST['post_id']}';</script>";
            }

        } elseif(isset($_POST["dislike"])){
            # Check if user has already liked the post
            $sql = "SELECT * FROM interactions WHERE user_id = '{$_SESSION['id']}' AND post_id = '{$_POST['post_id']}'";
            $result = $dbConnection->query($sql);
            if(mysqli_num_rows($result) > 0){
                $result = mysqli_fetch_assoc($result);
                if($result["interaction_type"] == "like"){
                    $sql = "UPDATE interactions SET interaction_type = 'dislike' WHERE user_id = '{$_SESSION['id']}' AND post_id = '{$_POST['post_id']}'";
                    $dbConnection->query($sql);
                    $sql = "UPDATE posts SET likes = likes-1, dislikes = dislikes+1 WHERE id = '{$_POST['post_id']}'";
                    $dbConnection->query($sql);
                    echo "<script>window.location.href = 'post-viewer?id={$_POST['post_id']}';</script>";
                }
            }
        }
    }
