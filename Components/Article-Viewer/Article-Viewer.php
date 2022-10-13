<?php
    require_once 'db/conn.php';
    $db = new connection();
    $conn = $db->getConnection();

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["id"])){
            $id = mysqli_escape_string($conn, $_GET["id"]);

            // SQL query to get the article with the specified id as well as the author's name
            $sql = "
            SELECT title, content, thumbnail_path, articles.created_at, category, user_id, users.first_name, users.last_name
            FROM articles
            INNER JOIN users ON articles.user_id = users.id
            WHERE articles.id = $id
            ";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $article = $result->fetch_assoc();
                $author = $article["first_name"] . " " . $article["last_name"];

                echo "<div class='article-viewer'>";

                if(isset($article['thumbnail_path'])){
                    echo "<div class='article-image'>
                            <img src='uploads/$article[thumbnail_path]' alt='article thumbnail'>
                        </div>";
                }

                echo "<h1>$article[title]</h1>";
                echo "<span class='article-author'>By $author</span>";
                echo "<div class='article-content'>$article[content]</div>";
                echo "</div>";
            }
            else{
                header("Location: /404");
            }
        } else {
            header("Location: /");
        }
    }
?>
