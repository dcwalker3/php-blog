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

        $sql = "SELECT posts.id as postID, posts.user_id, posts.title, posts.body, posts.type, posts.likes, posts.dislikes, posts.created_at as post_created, users.username, users.id as userID
                FROM posts
                INNER JOIN users ON posts.user_id = users.id
                WHERE posts.id = '$postID'";

        $postResult = $dbConnection->query($sql);
        if(mysqli_num_rows($postResult) > 0){
            $post = mysqli_fetch_assoc($postResult);
            $comments = mysqli_fetch_assoc($commentResult);

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
                                    <button type='submit' name='like' value='Like'>
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
                                    <button type='submit' name='dislike' value='Dislike'>
                                        <i class='fas fa-thumbs-down'></i>
                                    </button>
                                    <span class='dislike-count'>{$post['dislikes']}</span>
                                </div>
                            </form>                                
                        </div>
                    </div>
                    <div class='comments-container'>
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
            if ($results != true) {
                echo "
                    <div class='alert alert-danger' style='
                        text-align: center;
                        width: min-content;
                        padding: 1.5rem;
                        margin: auto;
                    '>
                        {$results}
                    </div>
                ";
            }
        }
    }
