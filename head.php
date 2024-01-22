<!-- <?php
 function addHeader(){

 }
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK HEAVEN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <link href="style/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="script/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="script/script.js"></script>
</head>
<body>
    <div class="spinner-overlay">
        <div class="spinner"></div>
    </div>



    





 
    <div class="full-site">
        
        <div class="container">
            <div class="row">
                
                
                <div id="sidebar" class="p-0" style="left: 0px">
                    <div id="menu-icon"> &#9776; </div>
                    <div class="sidebar">

                        
                        <a class="sidebar-option dashboard u-admin u-library u-publication u-seller d-none" href="./dashboard"> Dashboard</a>
                        <a class="sidebar-option dashboard u-user u-none d-none" href="./"> Home</a>

                        <bold class="sidebar-head u-admin d-none"> User</bold>
                        <a class="sidebar-option u-admin d-none" href="./users"> Users </a>
                        


                        <bold class="sidebar-head u-library u-publication u-seller d-none"> Books</bold>
                        <a class="sidebar-option u-library u-publication u-seller d-none" href="./stock"> Stock </a>
                        <a class="sidebar-option u-library u-publication u-seller d-none" href="./add_item"> Add Stock</a>
                        <bold class="sidebar-head u-user u-library u-publication u-seller u-admin d-none"> Orders</bold>
                        <a class="sidebar-option u-user d-none" href="./cart" >Cart
                            <!-- <small class="text-light rounded-5 cart-count2" id="cart_count_nav">0</small> -->
                            <span class="badge rounded-pill text-bg-primary ms-auto cart_count" id="cart_count">0</span>
                        </a>
                        <a class="sidebar-option u-user u-library u-publication u-seller d-none" href="./my_order">My Orders</a>
                        <a class="sidebar-option u-admin d-none" href="./pending_order">Pending</a>
                        <a class="sidebar-option u-admin d-none" href="./processing_order">Processing</a>
                        <a class="sidebar-option u-admin d-none" href="./shipped_order">Shipped</a>
                        <a class="sidebar-option u-admin d-none" href="./delivered_order">Delivered</a>
                        <a class="sidebar-option u-user u-library u-publication u-seller d-none" href="./ongoing_order">Current Orders</a>
                        <a class="sidebar-option u-user u-library u-publication u-seller d-none" href="./complete_order">Completed Orders</a>
                        <bold class="sidebar-head u-user u-library u-publication u-seller u-admin u-none d-none"> Profile</bold>
                        <a class="sidebar-option u-none d-none" id="loginBtnSideBar">Login</a>
                        <!-- <a class="sidebar-option u-none d-none" href=">Signup</a> -->
                        <a class="sidebar-option u-user u-library u-publication u-seller u-admin d-none" href="./profile">Account</a>
                        <a class="sidebar-option u-user u-library u-publication u-seller u-admin d-none" href="./edit_profile">Edit Profile</a>
                        <a class="sidebar-option u-user u-library u-publication u-seller u-admin d-none" href="./change_password">Change Password</a>
                        <a class="sidebar-option u-library u-publication u-seller d-none" href="./withdraw">Withdraw</a>
                        
                        <div class="row fixed-bottom">
                            <div class="col">
                                <!-- Facebook -->
                                <a
                                class="btn btn-outline-light btn-floating m-1"
                                class="text-white"
                                role="button" style="display: inline-block"
                                ><i class="fab fa-facebook-f"></i
                                ></a>

                                <!-- Twitter -->
                                <a
                                class="btn btn-outline-light btn-floating m-1"
                                class="text-white"
                                role="button" style="display: inline-block"
                                ><i class="fab fa-twitter"></i
                                ></a>

                                <!-- Google -->
                                <a
                                class="btn btn-outline-light btn-floating m-1"
                                class="text-white"
                                role="button"
                                ><i class="fab fa-google"></i
                                ></a>

                                <!-- Instagram -->
                                <a
                                class="btn btn-outline-light btn-floating m-1"
                                class="text-white"
                                role="button"
                                ><i class="fab fa-instagram"></i
                                ></a>
                            </div>
                        </div>
                        
                        
                            



                    </div>
                </div>
                
                <div class="col body p-3" id="content">
                    <div class="row">
                        <div class="col-12 px-0">
                            <div class="col-12 bg-black rounded mb-2">
                                <nav class="navbar">
                                    <a href="/"><img src="./img/logo.png" alt="" class="logo"></a>
                                    
                                    <ul class="nav-ul ml-auto" id="nav_ul">
                                        <li class="nav-li u-user d-none"><a class="nav-a" href="/cart">
                                                <i class="fa-solid fa-cart-shopping" style="color: #FFFFFF;"></i>
                                                <small class="text-light rounded-5 cart_count" >0</small>
                                            </a>
                            
                                        </li>
                                        <li class="nav-li" id="nav_login_li"><a class="nav-a" href="/" id="nav_login_btn">LOGIN</a></li>
                                        <li class="nav-li d-none" id="nav_profile_li">
                                            <a class="nav-a  nav-drop" href="/" id="nav_profile_btn"></a>
                                            <ul class="drop-ul">
                                                <div class="drop-li">
                                                    <a class="drop-a" href="/profile">My Profile</a>
                                                </div>
                                                <div class="drop-li">
                                                    <a class="drop-a" href="/edit_profile">Edit Profile</a>
                                                </div>
                                                <div class="drop-li" id="nav_myOrder_btn">
                                                    <a class="drop-a">My Order</a>
                                                </div>
                                                <!-- only for library or publication -->
                                                <div class="drop-li d-none" id="nav_myItem_btn">
                                                    <a class="drop-a" href="/my_item">My Books</a>
                                                </div>
                                                <div class="drop-li d-none" id="nav_addItem_btn">
                                                    <a class="drop-a" href="/add_item">Add new book</a>
                                                </div>
                            
                                                <div class="drop-li">
                                                    <a class="drop-a" id="nav_logout_btn">Logout</a>
                                                </div>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                    