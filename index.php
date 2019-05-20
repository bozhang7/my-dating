<?php
    // starts a session
    session_start();

    // turns on error reporting
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // requires autoload file
    require_once('vendor/autoload.php');
    require_once('model/validate.php');

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
    $f3->route('GET|POST /personal_info_form', function($f3) {
        // defines an array of gender with available options
        $f3->set('genders', array('Male', 'Female'));

        // if the form has been submitted (via POST), validates it
        if (!empty($_POST)) {
            // gets all the data from the form
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $age = $_POST['age'];
            $genderChoice = $_POST['gender']; echo "gender: $genderChoice<br>";
            $phone = $_POST['phone'];

            // stores all the data to hive
            $f3->set('fname', $fname);
            $f3->set('lname', $lname);
            $f3->set('age', $age);
            $f3->set('genderChoice', $genderChoice);
            $f3->set('phone', $phone);

            if (validatePersonalInfoForm()) {
                echo "all personal info fields are valid!";
                // writes data to session variables
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;
                $_SESSION['age'] = $age;
                $_SESSION['gender'] = $genderChoice;
                $_SESSION['phone'] = $phone;

                // redirects to next form: Profile Form
                $f3->reroute('/profile_form');
            }
        }

        $view = new Template();
        echo $view->render('views/personal_info_form.html');
    });

    // defines "Profile" route
    $f3->route('GET|POST /profile_form', function() {
        $_SESSION['fname'] = $_POST['fname'];
        $_SESSION['lname'] = $_POST['lname'];
        $_SESSION['age'] = $_POST['age'];
        $_SESSION['gender'] = $_POST['gender'];
        $_SESSION['phone'] = $_POST['phone'];

        $view = new Template();
        echo $view->render('views/profile_form.html');
    });

    // defines "Interests" route
    $f3->route('GET|POST /interests_form', function() {
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['state'] = $_POST['state'];
        $_SESSION['seeking-gender'] = $_POST['seeking-gender'];
        $_SESSION['bio'] = $_POST['bio'];

        $view = new Template();
        echo $view->render('views/interests_form.html');
    });

    // defines "Summary" route
    $f3->route('GET /summary', function($f3) {
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
