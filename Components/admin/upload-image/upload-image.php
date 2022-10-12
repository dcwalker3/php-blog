<?php
    if($_SESSION["role"] != "admin" && $_SESSION["role"] != "author" || !isset($_SESSION["user"])){
        header("Location: /");
    } else {
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_FILES["image"])){
                // Get Image Extension
                $image = $_FILES["image"]["name"];
                $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));

                $extensions_arr = array("jpg", "jpeg", "png", "gif"); // Valid Extensions

                // Check extension
                if(in_array($imageFileType, $extensions_arr)){
                    // Set our variables
                    $userID = $_SESSION['user'];
                    // Store image in the /SRC/uploads/images/thumbnails folder
                    // Get the image itself
                    $file = $_FILES["image"];
                    $targetPath = $_SERVER["DOCUMENT_ROOT"] . "/SRC/uploads/images/gallery/";

                    // Get timestamp so we can append to the image name
                    $timestamp = time();
                    $fileName = $timestamp . "-" . $file["name"];
                    $uploadPath = $targetPath . $fileName;

                    // Check if it truly is an image.
                    $checkImage = getimagesize($_FILES["image"]["tmp_name"]);
                    if($checkImage !== false){
                        // If the image uploaded smoothly
                        if(move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)){
                            require_once "db/conn.php";
                            $db = new connection();
                            $conn = $db->getConnection();

                            $alt = $_POST["altText"];

                            // SQL Query to insert article
                            $sql = "INSERT INTO images (path, alt, user_id) VALUES ('$uploadPath', '$alt', '$userID')";

                            // Run Query
                            if($conn->query($sql) === TRUE){
                                echo "Image uploaded successfully";
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }

                        }
                    }
                } else {
                    echo "Invalid Image";
                }
            } else {
                echo "No image";
            }

        } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
            $userID = $_SESSION["user"];

            // Echo out the form to upload an image
            echo '
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="image" id="image" accept="image/*" required><br>
                    <input type="text" name="altText" id="altText" placeholder="Descriptive Text" required><br>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            ';
        }
    }
