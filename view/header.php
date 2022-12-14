<!DOCTYPE html>
<html>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//<div class="nav-link-wrapper active-nav-link">
?>

<header>
    <!-- style sheet -->

    <head>
        <html lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width-device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title> PHP Assassin Shop </title>
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Bungee' rel='stylesheet'>
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link rel="stylesheet" href="../view/css/header_style.css" />

        <!-- search button -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    </head>
</header>
<?php
if (isset($_SERVER['QUERY_STRING'])) {
    $current_query = $_SERVER['QUERY_STRING'];
    $current_page = explode("=", $current_query);
    //echo $current_page[1];

}

?>
</div>

<div class="container1">
    <div class="nav-wrapper">
        <div class="left-side">

            <div class="nav-link-wrapper <?php if (isset($current_page) && $current_page[1] == 'home') echo 'active-nav-link'; ?>">
                <a href="../controller/index.php?user_Action=home">Home </a>
            </div>



            <div class="nav-link-wrapper <?php if (isset($current_page) && $current_page[1] == 'categories') echo 'active-nav-link'; ?>">
                <a href="../controller/index.php?user_Action=categories">Categories</a>
            </div>

            <?php

            if (isset($_COOKIE['userName'])) {
                $value = filter_input(INPUT_COOKIE, 'userName', FILTER_VALIDATE_INT);
                //print_r($_COOKIE);
                if (!($value === false || $value == 0)) { ?>

                    <div class="nav-link-wrapper <?php if (isset($current_page) && $current_page[1] == 'orders') echo 'active-nav-link'; ?>">
                        <a href="../controller/index.php?user_Action=orders">Orders</a>
                    </div>

                    <div class="nav-link-wrapper<?php if (isset($current_page) && $current_page[1] == 'cart') echo 'active-nav-link'; ?>">
                        <a href="../controller/index.php?user_Action=cart" style="width:32px; height:32px;" class="uil uil-shopping-cart"></a>
                    </div>
            <?php

                }
            }

            ?>


        </div>

        <!-- search box, submit what user enters with form to controller/index.php -->
        <div class="search_box">
            <div class="search-container">
                <form action="../controller/index.php" method="post">

                    <input type="hidden" name="user_Action" value="search">
                    <input type="text" placeholder="Search.." name="keyWord">
                    <button type="submit" style="border-radius:12px;"><i class="fa fa-search fa-2x"></i></button>

                </form>
            </div>
        </div>


        <div class="right-side">
            <?php
            $href = "../controller/index.php?user_Action=login";
            $buttonName = "Login";

            if (!empty($_GET)) {
                if (isset($current_page) && ($current_page[1] == 'register' || $current_page[1] == 'registerAddress')) {
                    $href = "../controller/index.php?user_Action=register";
                    $buttonName = "Signup";
                }
            }

            if (isset($_COOKIE['userName'])) {
                $value = filter_input(INPUT_COOKIE, 'userName', FILTER_VALIDATE_INT);
                //print_r($_COOKIE);
                if (!($value === false || $value == 0)) {
                    $user = get_user($value);
                    if( is_array($user) ) {
                        $customer = $user['userName'];
                        $buttonName = $customer;
                        $href = "../controller/index.php?user_Action=profile";


                    }
                    
                    //print_r($user);
                    //echo "USER: ", $customer;
                   

                    echo '<div class="nav-link-wrapper">
        
                <div class="dropdown">
                    
                    <button class="dropbtn" disabled>', $buttonName, '</button>
                    <div class="dropdown-content">
                        <a href="../controller/index.php?user_Action=profile">Edit Profile</a>
                        
                        <a href="../controller/index.php?user_Action=logout">Logout</a>
                    </div>
                </div>
        
            </div>';
                }
            } else {
                $linkClass = "nav-link-wrapper";
                if (!empty($_GET)) {
                    if (isset($current_page) && $current_page[1] == 'login') {
                        $linkClass .= ' active-nav-link';
                        $href = "../controller/index.php?user_Action=login";
                        $buttonName = "Login";
                    } else if (isset($current_page) && ($current_page[1] == 'register' || $current_page[1] == 'registerAddress')) {
                        $linkClass .= ' active-nav-link';
                        $href = "../controller/index.php?user_Action=register";
                        $buttonName = "Signup";
                    }
                }
                echo '<div class="', $linkClass, '">
            <a href=', $href, '>', $buttonName, '</a>
        </div>';
            }

            ?>




            <div class="brand">
                PHP Assassin Shop
            </div>

        </div>
    </div>


    <body>