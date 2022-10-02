<?php
    require_once 'db/conn.php';
    $db = new connection();
    $dbConnection = $db->getConnection();

    $username = $_SESSION['username'];
    $posts = [];
    $sql = "SELECT * FROM posts";
    $result = $dbConnection->query($sql);

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $posts[] = $row;
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $postID = $_POST['post_id'];
        $userID = $_SESSION['id'];

        if(isset($_POST['like'])){
            $action = 'like';
        } else if(isset($_POST['dislike'])){
            $action = 'dislike';
        }

        # Creating interaction document, so we know if the user liked something.
        $sql = "SELECT * FROM interactions WHERE post_id = '$postID' AND user_id = '$userID'";
        $result = $dbConnection->query($sql);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $currentInteraction = $row["interaction_type"];
            if($currentInteraction != $action) {
                $likeID = $row['id'];
                $sql = "UPDATE in interactions SET interaction_type = '$action' WHERE id = '$likeID'";
                $result = $dbConnection->query($sql);
            }
        } else {
            $sql = "INSERT INTO interactions (post_id, user_id, interaction_type) VALUES ('$postID', '$userID', '$action')";
            $result = $dbConnection->query($sql);
        }

        # Add Like/Dislike to post
        $actionType = $action . "s";

        $numberToUpdate = 0;
        $sql = "";

        if(isset($_POST["current-likes"])){
           $numberToUpdate = $_POST["current-likes"] + 1;
              $sql = "UPDATE posts SET likes = '$numberToUpdate' WHERE id = '$postID'";
        } else if(isset($_POST["current-dislikes"])){
            $numberToUpdate = $_POST["current-dislikes"] + 1;
            $sql = "UPDATE posts SET dislikes = '$numberToUpdate' WHERE id = '$postID'";
        }
        echo $numberToUpdate;

        $result = $dbConnection->query($sql);

    }
?>

<div class="container">
    <div class="dashboard">
        <a href="/create-post" class="btn btn-success createPostBtn">Create Post</a>
        <h1 class="dashboard-title">Dashboard</h1>
        <div class="dashboard-content">
            <h2>Welcome <?php echo $username; ?></h2>
            <?php
                foreach($posts as $post){
                    $body = $post['body'];
                    # Shorten the body if it is too long. Replace the last 3 characters with '...'
                    if(strlen($body) > 250){
                        $body = substr($body, 0, 250) . '...';
                    }

                    $humanReadableDate = date('F j, Y', strtotime($post['created_at']));
                    $capitalizedType = ucfirst($post['type']);
                    echo "<div class='post'>
                            <h3 class='post-title'>{$post['title']}</h3>
                            <p class='post-content'>{$body}</p>
                            <p class='post-author'>Created By: {$post["author"]}</p>
                            <sub class='post-upload-date'>Uploaded: {$humanReadableDate}</sub>
                            <span class='tag {$post["type"]}'>{$capitalizedType}</span>
                            <div class='post-footer'>
                                <form action='' method='post'>
                                    <div class='like-btn'>
                                        <input type='hidden' name='post_id' value='{$post['id']}'>
                                        <input type='hidden' name='current-likes' value='{$post['likes']}'>
                                        <input type='hidden' name='user_id' value='{$_SESSION['id']}'>
                                        <button type='submit' name='like' value='Like'>
                                            <i class='fas fa-thumbs-up'></i>
                                        </button>
                                        <span class='like-count'>{$post['likes']}</span>
                                    </div>
                                </form>
                                <form action='' method='post'>
                                    <div class='dislike-btn'>
                                        <input type='hidden' name='post_id' value='{$post['id']}'>
                                        <input type='hidden' name='current-dislikes' value='{$post['dislikes']}'>
                                        <input type='hidden' name='user_id' value='{$_SESSION['id']}'>
                                        <button type='submit' name='dislike' value='Dislike'>
                                            <i class='fas fa-thumbs-down'></i>
                                        </button>
                                        <span class='dislike-count'>{$post['dislikes']}</span>
                                    </div>
                                </form>                                
                            </div>
                        </div>";
                }
            ?>
        </div>
</div>
