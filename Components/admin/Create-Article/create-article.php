<?php
    if($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] != 'admin' and $_SESSION['role'] != 'author') {
                header("Location: /");
            }
        } else {
            header("Location: /");
        }
    } elseif($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] != 'admin' and $_SESSION['role'] != 'author') {
                header("Location: /");
            } else {
                if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST["thumbnail"]) && (isset($_POST['category']) or isset($_POST["custom-category"]))) {
                    // Get Image Extension
                    $image = $_FILES["thumbnail"]["name"];
                    $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));

                    $extensions_arr = array("jpg", "jpeg", "png", "gif"); // Valid Extensions
                    if(in_array($imageFileType, $extensions_arr)){
                        // Set our category
                        // If the user has selected a category from the dropdown, use that
                        // else use the custom category
                        if(isset($_POST['category'])) {
                            $category = $_POST['category'];
                        } else {
                            $category = $_POST['custom-category'];
                        }

                        // Set our variables
                        $title = $_POST['title'];
                        $userID = $_SESSION['user'];
                        $content = $_POST['content'];

                        // Store image in the /SRC/uploads/images/thumbnails folder
                        // Get the image itself
                        $file = $_FILES["thumbnail"];
                        $targetPath = "/SRC/uploads/images/thumbnails";

                        // Get timestamp so we can append to the image name
                        $timestamp = time();
                        $uploadPath = $targetPath . $file["name"] . "-" . $timestamp;

                        // Check if it truly is an image.
                        $checkImage = getimagesize($_FILES["thumbnail"]["tmp_name"]);
                        if($checkImage !== false){
                            // If the image uploaded smoothly
                            if(move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetPath)){
                                // SQL Query to insert article
                                $sql = "INSERT INTO articles (title, content, category, user_id, thumbnail_path) VALUES ('$title', '$content', '$category', '$userID', '$targetPath')";

                                // Run Query

                            }
                        }


                    } else {
                        echo "Invalid Image";
                    }

                }
            }
        } else {
            header("Location: /");
        }
    }
?>

<!-- Text Area for HTML for article -->
<div class="createArticle">
    <div class="form-group createArticle-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3 class="createArticle-title">Create Article</h3>
            <div class="row">
                <div class="col">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
                </div>
                <div class="col">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="Technology">Technology</option>
                        <option value="Finances/Economics">Finances & Economics</option>
                        <option value="Anime/Manga">Anime, Manga, and Gacha Games</option>
                        <option value="TV/Movies">TV and Movies</option>
                        <option value="Music">Music</option>
                        <option value="Video Games">Video Games</option>
                        <option value="Custom">Custom</option>
                    </select>
                </div>
            </div><br/>
            <div class="row">
                <div class="col">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" accept="image/*" id="thumbnail" class="form-control" placeholder="Thumbnail" required>
                </div>
            </div>


            <script type="text/javascript">
                // Get The Select Element
                let categorySelect = document.getElementById('category');

                // Add A Change Event Listener
                categorySelect.addEventListener('change', function(){
                    // If the user selects "Custom" then show the input field
                    if(categorySelect.value === 'Custom'){
                        let customCategory = document.createElement('input');
                        customCategory.setAttribute('type', 'text');
                        customCategory.setAttribute('name', 'custom-category');
                        customCategory.setAttribute('id', 'custom-category');
                        customCategory.setAttribute('placeholder', 'Custom Category');
                        customCategory.setAttribute('required', 'true');
                        customCategory.setAttribute('class', 'form-control');
                        categorySelect.parentNode.insertBefore(customCategory, categorySelect.nextSibling);

                        // Add event listener to set value of custom category to the value of the input
                        customCategory.addEventListener('change', function(){
                            categorySelect.value = customCategory.value;
                        });
                    }else{
                        let customCategory = document.querySelector('#custom-category');
                        customCategory.parentNode.removeChild(customCategory);
                    }
                });
            </script>
            <br/>
            <label for="content">Content</label>
            <textarea class="form-control" name="content" id="content" cols="30" rows="10" required></textarea>
            <div class="createArticle-footer">
                <button type="submit" name="submit" class="btn btn-success" value="create-article" id="create-article">Create</button>
            </div>
        </form>
    </div>
</div>
