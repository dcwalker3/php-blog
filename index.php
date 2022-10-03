<?php
     ob_start();
     session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta author="Odin">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Odin's Knowledge</title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Boostrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/8d3d444b91.js" crossorigin="anonymous"></script>

        <!-- Custom CSS -->
        <link rel="stylesheet" href="StyleSheets/login-signup-forms.css">
        <link rel="stylesheet" href="StyleSheets/posts.css">
        <link rel="stylesheet" href="StyleSheets/dashboard.css">
        <link rel="stylesheet" href="StyleSheets/tags.css">

        <!-- TinyMCE -->
        <script src="tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
        <script>

        tinymce.init(
            {
                selector: '#postContent',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                showMenuBar: true
            }
        );
    </script>
    </head>
    <body>
        <?php
            # Navbar up top
            require_once('Components/Navbar/navbar.php');

            $request = $_SERVER['REQUEST_URI'];
            # Check if $_SESSION['username'] is set
            if(isset($_SESSION['username'])){
                switch ($request) {
                    case '/' :
                        require_once('Components/Home/home.php');
                        break;
                    case '/create-post':
                        require_once('Components/Create-Post/create-post.php');
                        break;
                    case '/login':
                        require_once('Components/Login-Signup-Forms/login.php');
                        break;
                    case '/signup':
                        require_once('Components/Login-Signup-Forms/signup.php');
                        break;
                    case '/logout':
                        session_destroy();
                        header('Location: /');
                        break;
                    case '/post-viewer?id=' . $_GET['id']:
                        # Get parameters from URL
                        require_once('Components/Post-Viewer/Post-Viewer.php');
                        break;
                    default:
                        require_once('404.php');
                }
            }
            else{
                switch ($request) {
                    case '/login':
                        require_once('Components/Login-Signup-Forms/login.php');
                        break;
                    case '/signup':
                        require_once('Components/Login-Signup-Forms/signup.php');
                        break;
                    default:
                        require_once('Components/Login-Signup-Forms/login.php');
                }
            }
        ?>
    </body>
</html>