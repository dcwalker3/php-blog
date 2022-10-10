<?php
    require_once 'db/conn.php';
    $db = new connection();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM articles ORDER BY created_at DESC";

    $result = $conn->query($sql);
    $articles = $result->fetch_all(MYSQLI_ASSOC);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['search'])){
            $search = $_POST['search'];
            $sql = "SELECT * FROM articles WHERE title LIKE '%$search%' OR content LIKE '%$search%'  OR category LIKE '%$search%' tags LIKE '%$search%' ORDER BY created_at DESC";
            $result = $conn->query($sql);
            $articles = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
?>
<div class="home">
    <div class="article-search">
        <form action="search.php" method="GET">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
            <button class="btn btn-primary" type="submit" value="search">Search</button>
        </form>
    </div>
    <div class="most-recent">
        <div class="articles">
            <?php
                if(isset($articles[0])) {
                    $mostRecent = $articles[0];
                    echo "
                        <div class='article'>
                            <div class='article-image'>
                                <img src='uploads/$mostRecent[image]' alt=''>
                            </div>
                            <div class='article-content'>
                                <h2>$mostRecent[title]</h2>
                                <div >$mostRecent[content]</div>
                                <a href='article.php?id=$mostRecent[id]'>Read More</a>
                            </div>
                        </div>
                    ";
                }
            ?>
        </div>
    </div>
</div>
