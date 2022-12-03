<?php  

session_start();
require("../model/database.php");
require("../model/retrieve_books.php");
require("../model/user_db.php");
require("../model/add_delete_update_books.php");

// Check user action to determine what they want to do
$user_Action = filter_input (INPUT_POST, 'user_Action');


if (isset($_COOKIE['userName'])) {
	$value = filter_input (INPUT_COOKIE, 'userName', FILTER_VALIDATE_INT);
	if ($value === false || $value == 0) {
		$customer = "";
	}
	else {
		$user = get_user($value);
		$customer = $user['userName'];
	}
}


if(isset($_GET['user_Action']))
	$user_Action = $_GET['user_Action'];

// Default case, if user does not specify
// Getting user action from form or url
if ($user_Action == null)
{
    // Always displays the home page
    // 12/2/2022 goes to admin version of the website
    //$user_Action = 'admin_home_page';
     $user_Action = 'home';
}

// 11/30/2022 search box
if($user_Action =='search')
{
    if(isset($_POST['keyWord']))
    {
        $keyWord = $_POST['keyWord'];

        $all_result = select_by_book_name($keyWord);
        if($all_result == null)
        {
            $all_result = select_by_isbn($keyWord);
        }

        if($all_result == null)
        {
            $all_result = select_by_author($keyWord);
        }
        if($all_result == null)
        {
            $all_result = select_by_publisher($keyWord);
        }
        // Display result if there is stuff in the $all_result array
        $user_Action ='result_display';

        if($all_result == null)
        {
            // Display result
            $user_Action ='home';
            $error_msg = "Oops, we don't have this book in stock";
        }

        
    }
    else
    {
        include('../view/error.php');
    }
}

if($user_Action == 'home')
{
    // To hold all books' data
    $all_books = select_all_books();
    
    include('../view/home.php');
}



/* 11/20/2022 this ask user for a genre they want to find */ 
if($user_Action == 'categories')
{
    $book_genres = select_all_genre();
    include('../view/categories.php');
}

/* // 12/2/2022  this ask user for a genre they want to find */ 
if($user_Action == 'admin_categories')
{
    $book_genres = select_all_genre();
    include('../view/admin_categories.php');
}
// 12/2/2022 
if($user_Action == 'admin_categories_result')
{
    if(isset($_GET['genre']))
    {
        $genreId = $_GET['genre'];

        $select_books = select_by_genre($genreId);


        include('../view/admin_categories_result.php');
    }
    else
    {
        include('../view/error.php');
    }
}

/* 11/20/2022 this display what user choose in categories.php */
/* 11/22/2022 updated else condition */
if($user_Action == 'categories_result')
{
    if(isset($_GET['genre']))
    {
        $genreId = $_GET['genre'];

        $select_books = select_by_genre($genreId);


        include('../view/categories_result.php');
    }
    else
    {
        include('../view/error.php');
    }
}

// 12/2/2022
// 11/22/20221
// when user clicks buy, displays the details of the book and allows user to add a book to their shopping cart
if($user_Action =='book')
{
    if(isset($_GET['bookId']))
    {
        $bookId = $_GET['bookId'];

        $select_book = select_by_id($bookId);

        $genre = select_by_genre($select_book[1]);


        include('../view/book.php');
    }
    else
    {
        include('../view/error.php');
    }
    
}



// 11/30/2022 search result
if($user_Action =='result_display')
{
    if(isset($all_result))
    {
        include('../view/search_result.php');
    }
    else
    {
        include('../view/error.php');
    }
}


if($user_Action == 'authors')
{
    include('../view/authors.php');
}

// 12/2/2022
if($user_Action == 'delete_books')
{
    $bookID = filter_input(INPUT_POST, 'bookID');

    $delete_Msg = delete_book($bookID);

    $user_Action = 'admin_home_page';

}

// 12/2/2022 
if($user_Action == 'update_books_form')
{
    $bookID = filter_input(INPUT_POST, 'bookID');

    $target = select_by_id($bookID);

    $pictureName_short = str_replace("../view/pic/", "", $target[9]);

    // Get all genre for dropdown box
    $all_genres = select_all_genre();

    include('../view/update_books_form.php');
    
}

// 12/2/2022 
if($user_Action == 'update_books')
{
    // Get all data
    $bookID = filter_input(INPUT_POST, 'bookID');
    $genreID = filter_input(INPUT_POST, 'genreID');
    $bookName = filter_input(INPUT_POST, 'bookName');
    $bookDescription = filter_input(INPUT_POST, 'bookDescription');
    $listPrice = filter_input(INPUT_POST, 'listPrice');
    $discountPercent = filter_input(INPUT_POST, 'discountPercent');
    $isbn = filter_input(INPUT_POST, 'isbn');
    $authors = filter_input(INPUT_POST, 'authors');
    $publisher = filter_input(INPUT_POST, 'publisher');
    $pictureName ="../view/pic/".filter_input(INPUT_POST, 'pictureName');


    $update_success = update_book($bookID, $genreID, $bookName, $bookDescription, $listPrice, $discountPercent, $isbn, $authors, $publisher, $pictureName);

    // Go back to admin home page
    $user_Action = 'admin_home_page';
}


// 12/2/2022 search box
if($user_Action =='admin_search')
{
    if(isset($_POST['keyWord']))
    {
        $keyWord = $_POST['keyWord'];

        $all_result = select_by_book_name($keyWord);
        if($all_result == null)
        {
            $all_result = select_by_isbn($keyWord);
        }

        if($all_result == null)
        {
            $all_result = select_by_author($keyWord);
        }
        if($all_result == null)
        {
            $all_result = select_by_publisher($keyWord);
        }
        // Display result if there is stuff in the $all_result array
        $user_Action ='admin_result_display';

        if($all_result == null)
        {
            // Display result
            $user_Action ='admin_home_page';
            $error_msg = "Oops, we don't have this book in stock";
        }

        
    }
    else
    {
        include('../view/error.php');
    }
}

// 11/30/2022 search result
if($user_Action =='admin_result_display')
{
    if(isset($all_result))
    {
        include('../view/admin_search_result.php');
    }
    else
    {
        include('../view/error.php');
    }
}

if($user_Action == 'admin_home_page')
{


    // To hold all books' data
    $all_books = select_all_books();

    include('../view/admin_home_page.php');
}



if($user_Action == 'add_books')
{
    // Get all data
    $genreID = filter_input(INPUT_POST, 'genreID');
    $bookName = filter_input(INPUT_POST, 'bookName');
    $bookDescription = filter_input(INPUT_POST, 'bookDescription');
    $listPrice = filter_input(INPUT_POST, 'listPrice');
    $discountPercent = filter_input(INPUT_POST, 'discountPercent');
    $isbn = filter_input(INPUT_POST, 'isbn');
    $authors = filter_input(INPUT_POST, 'authors');
    $publisher = filter_input(INPUT_POST, 'publisher');
    $pictureName ="../view/pic/".filter_input(INPUT_POST, 'pictureName');

    $success = add_book($genreID, $bookName, $bookDescription, $listPrice, $discountPercent, $isbn, $authors, $publisher, $pictureName);

    $user_Action = 'add_books_form';
}

if($user_Action == 'add_books_form')
{
    // Get all genre for dropdown box
    $all_genres = select_all_genre();

    include('../view/add_books_form.php');
}


if($user_Action == 'admin_books')
{
    if(isset($_POST['bookID']))
    {
        $bookID = filter_input(INPUT_POST, 'bookID');

        $select_book = select_by_id($bookID);

        $genre = select_a_genre($select_book[1]);


        include('../view/admin_book.php');
    }
    else
    {
        include('../view/error.php');
    }
}



if($user_Action == 'orders')
{




    include('../view/orders.php');
}


if($user_Action == 'login')
{
	include('../view/login.php');
   

}

if($user_Action == 'register')
{
	include('../view/register.php');
}

if($user_Action == 'registerAddress')
{

	include('../view/registerAddress.php');
}

if($user_Action == 'addUser')
{
    if(isset($_POST['addUser']))
    {
        $emailAddress=filter_input(INPUT_POST, 'email');
        $userPassword=filter_input(INPUT_POST, 'password');
        $userName= filter_input(INPUT_POST, 'userName');

        //echo $emailAddress, $userPassword, $userName;
        //echo gettype($emailAddress), gettype($userPassword), gettype($userName);

        $name='userName';
        $id= add_user($emailAddress, $userPassword, $userName);
        
	    
        //echo $id;
        
        $_SESSION['is_valid'] = true;
        //echo "LOGGED IN\n";
        $id = valid_userID($emailAddress);
        $value=$id;
        $name='userName';
        $expiration = time()+(60*60*24*7);
        setcookie($name, $value, $expiration);
        //echo $id, $userName;
        include('../view/registerAddress.php');

    }
    else
    {
        echo "Add user not successful";
        include('../view/error.php');
    }
    
}

if($user_Action == 'addAddress')
{

    if(isset($_POST['addAddress']))
    {
        $userId= filter_input(INPUT_POST, 'id');

        $line1=filter_input(INPUT_POST, 'line1');
        $line2=filter_input(INPUT_POST, 'line2');
        if($line2==NULL)
            $line2=" ";
        $city= filter_input(INPUT_POST, 'city');
        $state= filter_input(INPUT_POST, 'state');
        $zipCode=filter_input(INPUT_POST, 'zipCode');
        $phone=filter_input(INPUT_POST, 'phone');

        
        //echo $emailAddress, $userPassword, $userName;
        //echo gettype($emailAddress), gettype($userPassword), gettype($userName);

        echo $userId,$line1, $line2, $city,$state, $zipCode, $phone;
        echo gettype($userId),gettype($line1), gettype($city),gettype($state),gettype($zipCode),gettype($phone);
        
        $id= add_address($userId, $line1, $line2, $city, $state, $zipCode, $phone);
        //echo $id;
        //add address to user
        bind_address($userId,  $id);

        header("Location: ../controller/index.php?user_Action=home");

    }
    else
    {
        echo "Add addresss not successful";
        include('../view/error.php');
    }
}



if($user_Action == 'logged_in')
{

    $emailAddress = filter_input(INPUT_POST, 'emailAddress');
    $userPassword = filter_input(INPUT_POST, 'userPassword');
    $pass = is_valid_user($emailAddress, $userPassword);
    if ($pass) {
        $_SESSION['is_valid'] = true;
        echo "LOGGED IN\n";
        $id = valid_userID($emailAddress);

        $value=$id;
        $name='userName';
        $userName = valid_userName($emailAddress);
        
    
        $expiration = time()+(60*60*24*7);
        setcookie($name, $value, $expiration);
        //echo $id, $userName;
        header("Location: ../controller/index.php?user_Action=home");

        
    }
    else {
        //echo "NOT LOGGEDIN";
        header("Location: ../controller/index.php?user_Action=login");
    }	
    
}

if($user_Action == 'profile')
{
    
    include('../view/profile.php');
}


if($user_Action == 'logout')
{
    if (isset($_COOKIE['userName'])) {
        $value = filter_input (INPUT_COOKIE, 'userName', FILTER_VALIDATE_INT);
        //print_r($_COOKIE);
        if ($value === false || $value == 0) {
             echo "No USER Found\n";
        }
        else {
            $user = get_user($value);
            $customer = $user['userName'];
            //echo "USER: ", $customer;
            echo "LOGGED IN\n";
            $id = valid_userID($emailAddress);
            $value=$id;
            $name='userName';
            $userName = valid_userName($emailAddress);
            
            
            $expiration = time()+(-999999);
            setcookie($name, $value, $expiration);
            $_SESSION['is_valid'] = false;
            unset($_SESSION['userName']);
            session_unset();
            session_destroy();
            //echo $id, $userName;
            header("Location: ../controller/index.php?user_Action=home");
            

        }
    }
}








?>
