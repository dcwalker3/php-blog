<?php
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
        require_once "db/conn.php";
        $db = new connection();
        $dbConnection = $db->getConnection();

        $sql = "SELECT * FROM images WHERE user_id = {$_SESSION['user']}";
        $result = $dbConnection->query($sql);
        $images = $result->fetch_all(MYSQLI_ASSOC);

        echo '<div class="admin-gallery">';
        foreach($images as $image){
            echo '<div class="admin-gallery-image">';
                echo '<img src="http://blogSite/' . $image['path'] . '" alt="' . $image['alt'] . '">';
                echo '<div class="admin-gallery-image-actions">';
                    echo '<a href="delete-image.php?id=' . $image['id'] . '">Delete</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    }
    else{
        header('Location: /');
    }