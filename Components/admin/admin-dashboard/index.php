<?php
    if(isset($_SESSION['role'])){
        if($_SESSION['role'] == "admin"){
            echo "
                <div class='admin-dashboard'>
                    <div class='admin-dashboard-content'>
                        <div class='admin-dashboard-content-header'>
                            <h1>Admin Dashboard</h1>
                        </div>
                        <div class='admin-dashboard-content-body'>
                            <div class='admin-dashboard-content-body-item'>
                                <div class='admin-dashboard-content-body-item-header'>
                                    <h2>Articles</h2>
                                </div>
                                <div class='admin-dashboard-content-body-item-body'>
                                    <div class='admin-dashboard-content-body-item-body-item'>
                                        <a href='/admin/articles/create'>Create Article</a>
                                    </div>
                                    <div class='admin-dashboard-content-body-item-body-item'>
                                        <a href='/admin/articles'>View Articles</a>
                                    </div>
                                </div>
                            </div>
                            <div class='admin-dashboard-content-body-item'>
                                <div class='admin-dashboard-content-body-item-header'>
                                    <h2>Categories</h2>
                                </div>
                                <div class='admin-dashboard-content-body-item-body'>
                                    <div class='admin-dashboard-content-body-item-body-item'>
                                        <a href='/admin/categories/create'>Create Category</a>
                                    </div>
                                    <div class='admin-dashboard-content-body-item-body-item'>
                                        <a href='/admin/categories'>View Categories</a>
                                    </div>
                                </div>
                            </div>
                            <div class='admin-dashboard-content-body-item'>
                                <div class='admin-dashboard-content-body-item-header'>
                                    <h2>Tags</h2>
                                </div>
                                <div class='admin-dashboard-content-body-item-body'>
                                    <div class='admin-dashboard-content-body-item-body-item'>
                                        <a href='/admin/tags/create'>Create Tag</a>
                                    </div>
                                    <div class='admin-dashboard-content-body-item-body-item'>
                                        <a href='/admin/tags'>View Tags</a>
                                    </div>
                                </div>
                            </div>
                            <div class='admin-dashboard-content-body-item'>
                                <div class='admin-dashboard-content-body-item-header'>
                                    <h2>Users</h2>
                                </div>
                                <div class='admin-dashboard-content-body-item-body'>
                                    <div class='admin-dashboard-content-body-item-body-item'>
                                        <a href='/admin/users/create'>Create User</a>
                                    </div>
                                    <div class='admin-dashboard-content-body-item-body>
                                        <a href='/admin/users'>View Users</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            ";
        }
    } else {
        header("Location: /admin/login");
    }
