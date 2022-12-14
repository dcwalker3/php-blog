<?php
     ob_start();
     session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta author="Dakota Walker">
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
        <link rel="stylesheet" href="/StyleSheets/styles.css">
        <link rel="stylesheet" href="/StyleSheets/login-signup-forms.css">
        <link rel="stylesheet" href="/StyleSheets/admin-login-signup-forms.css">
        <link rel="stylesheet" href="/StyleSheets/admin-create-article.css">
        <link rel="stylesheet" href="/StyleSheets/posts.css">
        <link rel="stylesheet" href="/StyleSheets/tags.css">
        <link rel="stylesheet" href="/StyleSheets/image-upload.css">
        <link rel="stylesheet" href="/StyleSheets/admin-gallery.css">
    </head>
    <body class="dark-mode">
        <?php
            require_once('Components/Navbar/navbar.php');
        ?>
        <div class="content-area dark-mode">
        <?php

            $request = $_SERVER['REQUEST_URI'];

            switch ($request) {
                case '/' :
                    require 'Components/Home/home.php';
                    break;
                case '/admin':
                    require 'Components/admin/admin-dashboard/index.php';
                    break;
                case '/admin/upload-image':
                    require 'Components/admin/upload-image/upload-image.php';
                    break;
                case '/admin/gallery':
                    require 'Components/admin/admin-gallery/gallery.php';
                    break;
                case '/admin/login':
                    require 'Components/admin/admin-login/login.php';
                    break;
                case '/admin/signup':
                    require 'Components/admin/admin-login/signup.php';
                    break;
                case '/admin/create':
                    require 'Components/admin/Create-Article/create-article.php';
                    break;
                case '/login' :
                    require 'Components/Login-Signup-Forms/login.php';
                    break;
                case '/signup' :
                    require 'Components/Login-Signup-Forms/signup.php';
                    break;
                case '/logout' :
                    require 'utils/logout.php';
                    break;
                case '/article-viewer?id=' . ($_GET['id'] ?? '') :
                    require 'Components/Article-Viewer/Article-Viewer.php';
                    break;
                default:
                    http_response_code(404);
                    require __DIR__ . '/404.php';
                    break;
            }
        ?>
        </div>
    </body>
</html>