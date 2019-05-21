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
            $gender = $_POST['gender'];
            $phone = $_POST['phone'];

            echo "fist name: $fname<br>";
            echo "last name: $lname<br>";
            echo "age: $age<br>";
            echo "gender: $gender<br>";
            echo "phone: $phone<br>";

            // stores all the data to hive
            $f3->set('fname', $fname);
            $f3->set('lname', $lname);
            $f3->set('age', $age);
            $f3->set('gender', $gender);
            $f3->set('phone', $phone);

            if (validatePersonalInfoForm()) {
                // writes data to session variables
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;
                $_SESSION['age'] = $age;
                $_SESSION['gender'] = $gender;
                $_SESSION['phone'] = $phone;

                // redirects to next form: Profile Form
                $f3->reroute('/profile_form');
            }
        }

        $view = new Template();
        echo $view->render('views/personal_info_form.html');
    });

    // defines "Profile" route
    $f3->route('GET|POST /profile_form', function($f3) {
        $f3->set('stateOptions', array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            'Idaho', 'Illinois', 'Indiana', 'Lowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
            'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'Ohio', 'Oklahoma', 'Oregon',
            'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia',
            'Wisconsin', 'Wyoming'));

        $f3->set('seekingOptions', array('Male', 'Female'));

        if (!empty($_POST)) {
            $email = $_POST['email'];
            $state = $_POST['state'];
            $seeking = $_POST['seeking'];
            $bio = $_POST['bio'];

            echo "email: $email<br>";
            echo "state: $state<br>";
            echo "seeking: $seeking<br>";
            echo "bio: $bio<br>";

            $f3->set('email', $email);
            $f3->set('state', $state);
            $f3->set('seeking', $seeking);
            $f3->set('bio', $bio);

            if (validateProfileForm()) {
                $_SESSION['email'] = $email;
                $_SESSION['state'] = $state;
                $_SESSION['seeking'] = $seeking;
                $_SESSION['bio'] = $bio;

                $f3->reroute('/interests_form');
            }
        }

        $view = new Template();
        echo $view->render('views/profile_form.html');
    });

    // defines "Interests" route
    $f3->route('GET|POST /interests_form', function($f3) {
        $f3->set('indoorOptions', array('tv', 'movies', 'cooking', 'board games', 'puzzles', 'reading', 'playing cards', 'video games'));
        $f3->set('outdoorOptions', array('hiking', 'biking', 'swimming', 'collecting', 'walking', 'climbing'));

        if ((isset($_POST['submit']))) {
            $indoorInterests = $_POST['indoor'];
            $outdoorInterests = $_POST['outdoor'];

            $f3->set('indoorInterests', $indoorInterests);
            $f3->set('outdoorInterests', $outdoorInterests);

            if (validateInterestsForm()) {
                if (!empty($indoorInterests)) {
                    $_SESSION['indoorInterests'] = implode(', ', $indoorInterests);
                } else {
                    $_SESSION['indoorInterests'] = "(No indoor interests selected)";
                }

                if (!empty($outdoorInterests)) {
                    $_SESSION['outdoorInterests'] = implode(', ', $outdoorInterests);
                } else {
                    $_SESSION['outdoorInterests'] = "(No outdoor interests selected)";
                }

                $f3->reroute('/summary');
            }
        }

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
