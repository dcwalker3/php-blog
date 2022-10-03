<?php
    $err = '';
    $msg = '';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        require_once 'db/conn.php';
        $db = new connection();
        $dbConnection = $db->getConnection();

        $title = $_POST['postTitle'];
        $content = $_POST['postContent'];
        $type = $_POST['postType'];

        if($title != '' && $content != '' && $type != ''){
            $acceptedTypes = ['tutorial', 'review', 'misc'];

            # Make sure post type is an accepted type
            if(in_array($type, $acceptedTypes)){
                $title = mysqli_real_escape_string($dbConnection, $title);
                $content = mysqli_real_escape_string($dbConnection, $content);
                $type = mysqli_real_escape_string($dbConnection, $type);


                $sql = "INSERT INTO posts (title, body, type, user_id) VALUES ('$title', '$content', '$type', '$_SESSION[id]')";

                if ($dbConnection->query($sql) === TRUE) {
                    $msg = 'Post created successfully';
                } else {
                    $err = 'Error creating post';
                }
            } else {
                $err = 'Post type is not accepted';
            }

        }
        else{
            echo 'Please fill out all fields';
        }
        $title = mysqli_real_escape_string($dbConnection, $title);
        $content = mysqli_real_escape_string($dbConnection, $content);
        $type = mysqli_real_escape_string($dbConnection, $type);

    }
?>
<div class="container">
    <div style="width: 100%; margin-bottom: 1rem; margin-top: 1rem;">
        <button class="btn btn-danger" style="float: right; margin-bottom: 1rem" onclick="window.location.href='/'">Back</button>
    </div><br/>

        <?php
            $errMsg = "<div class='alert alert-danger' role='alert'>$err</div>";
            $userMsg = "<div class='alert alert-success' role='alert'>$msg</div>";


            if($err != ''){
                echo $errMsg;
            }
            else if($msg != ''){
                echo $userMsg;
            }
        ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="postTitle">Post Title</label>
            <input type="text" class="form-control" id="postTitle" name="postTitle" required>
        </div>
        <div class="form-group-inline">
            <label for="postType">Post Type</label>
            <select class="form-control" id="postType" name="postType">
                <option value="tutorial">Tutorial</option>
                <option value="review">Review</option>
                <option value="misc">Misc.</option>
            </select>
        <div class="form-group">
            <label for="postContent">Post Content</label>

            <script type="text/javascript">
                tinymce.init(
                    {
                        selector: 'postContent',
                        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                        showMenuBar: true
                    }
                );
            </script>
            <textarea id="postContent" name="postContent"></textarea>
        </div>
        <input type="submit" class="btn btn-success" value="Submit">

    </form>
</div>
