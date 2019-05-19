<?php
    // starts a session
    session_start();

    // turns on error reporting
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // requires autoload file
    require_once('vendor/autoload.php');

    // instantiates f3
    $f3 = Base::instance();

    // turn on f3 error reporting
    $f3->set('DEBUG', 3);

    // defines a default route
    $f3->route('GET /', function() {
        //echo '<h1>Welcome To My Dating Website!</h1>';

        // displays a view
        $view = new Template();
        echo $view->render('views/home.html');
    });

    // defines "Personal Information" route
    $f3->route('GET /personal_info_form', function() {
        $view = new Template();
        echo $view->render('views/personal_info_form.html');
    });

    // defines "Profile" route
    $f3->route('POST /profile_form', function() {
        $_SESSION['fname'] = $_POST['fname'];
        $_SESSION['lname'] = $_POST['lname'];
        $_SESSION['age'] = $_POST['age'];
        $_SESSION['gender'] = $_POST['gender'];
        $_SESSION['phone'] = $_POST['phone'];

        $view = new Template();
        echo $view->render('views/profile_form.html');
    });

    // defines "Interests" route
    $f3->route('POST /interests_form', function() {
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['state'] = $_POST['state'];
        $_SESSION['seeking-gender'] = $_POST['seeking-gender'];
        $_SESSION['bio'] = $_POST['bio'];

        $view = new Template();
        echo $view->render('views/interests_form.html');
    });

    // defines "Summary" route
    $f3->route('POST /summary', function($f3) {
        /*
        foreach($_POST['indoor-interests'] as $checked) {
            echo "in door selected: $checked<br>";
        }

        echo "<br>";

        foreach($_POST['outdoor-interests'] as $checked) {
            echo "out door selected: $checked<br>";
        }
        */

        // note: hive variables CAN'T have "-"
        $f3->set('indoor', $_POST['indoor-interests']);
        $f3->set('outdoor', $_POST['outdoor-interests']);

        $view = new Template();
        echo $view->render('views/summary.html');
    });

    // runs f3
    $f3->run();
