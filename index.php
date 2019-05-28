<?php
    // turns on error reporting
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // requires autoload file
    require_once('vendor/autoload.php');
    require_once('model/validate.php');

    // starts a session
    session_start();

    // instantiates f3
    $f3 = Base::instance();

    // turn on f3 error reporting
    $f3->set('DEBUG', 3);

    // creates a database object
    $db = new Database();

    // defines a default route
    $f3->route('GET /', function() {
        //echo '<h1>Welcome To My Dating Website!</h1>';

        // resets session, clears everything
        $_SESSION = array();
        //print_r($_SESSION);

        // displays a view
        $view = new Template();
        echo $view->render('views/home.html');
    });

    // defines an admin route
    $f3->route('GET /admin', function($f3) {
        //print_r($_SESSION);

        global $db;
        $result = $db->getMembers();
        $f3->set('result', $result);

        // do something about each member to get all the interests

        // displays a view
        $view = new Template();
        echo $view->render('views/admin.html');
    });

    // defines "Personal Information" route
    $f3->route('GET|POST /personal_info_form', function($f3) {
        //print_r($_SESSION);

        $f3->set('genderOptions', array('Male', 'Female'));

        // if the form has been submitted (via POST), validates it
        if (!empty($_POST['personal-info-submit'])) {
            // gets all the data from the personal info form
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $phone = $_POST['phone'];
            $premium = $_POST['premium'];

            // stores all the data to hive for the purposes of sticky form and data validation
            $f3->set('fname', $fname);
            $f3->set('lname', $lname);
            $f3->set('age', $age);
            $f3->set('gender', $gender);
            $f3->set('phone', $phone);
            $f3->set('premium', $premium);

            if (validatePersonalInfoForm()) {
                // stores data in session
                $_SESSION['fname'] = $fname; // required
                $_SESSION['lname'] = $lname; // required
                $_SESSION['age'] = $age; // required
                $_SESSION['gender'] = $gender; // optional
                $_SESSION['phone'] = $phone; // required
                $_SESSION['premium'] = $premium; // optional

                if ($premium == 'yes') {
                    // creates a premium member object
                    $premiumMember = new PremiumMember($fname, $lname, $age, $gender, $phone);

                    $f3->set('membership', $premiumMember);
                    $_SESSION['membership'] = $premiumMember;
                } else {
                    // creates a regular member object
                    $regularMember = new Member($fname, $lname, $age, $gender, $phone);

                    $f3->set('membership', $regularMember);
                    $_SESSION['membership'] = $regularMember;
                }

                $f3->reroute('/profile_form');
            }
        }

        $view = new Template();
        echo $view->render('views/personal_info_form.html');
    });

    // defines "Profile" route
    $f3->route('GET|POST /profile_form', function($f3) {
        //print_r($_SESSION);

        // sets a states array in hive (note that the 1st element is empty)
        $f3->set('stateOptions', array('', 'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            'Idaho', 'Illinois', 'Indiana', 'Lowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
            'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'Ohio', 'Oklahoma', 'Oregon',
            'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia',
            'Wisconsin', 'Wyoming'));

        $f3->set('seekingGenderOptions', array('Male', 'Female'));

        if (!empty($_POST['profile-submit'])) {
            $email = $_POST['email'];
            $state = $_POST['state'];
            $seeking = $_POST['seeking'];
            $bio = $_POST['bio'];

            $f3->set('email', $email);
            $f3->set('state', $state);
            $f3->set('seeking', $seeking);
            $f3->set('bio', $bio);

            if (validateProfileForm()) {
                $_SESSION['email'] = $email; // required
                $_SESSION['state'] = $state; // optional
                $_SESSION['seeking'] = $seeking; // optional
                $_SESSION['bio'] = $bio; // optional

                $memberShip = $_SESSION['membership'];
                $memberShip->setEmail($email);
                $memberShip->setState($state);
                $memberShip->setSeeking($seeking);
                $memberShip->setBio($bio);

                if ($memberShip instanceof PremiumMember) {
                    $f3->reroute('/interests_form');
                } else {
                    $f3->reroute('/summary');
                }
            }
        }

        $view = new Template();
        echo $view->render('views/profile_form.html');
    });

    // defines "Interests" route
    $f3->route('GET|POST /interests_form', function($f3) {
        //print_r($_SESSION);

        $f3->set('indoorOptions', array('tv', 'movies', 'cooking', 'board games', 'puzzles', 'reading', 'playing cards', 'video games'));
        $f3->set('outdoorOptions', array('hiking', 'biking', 'swimming', 'collecting', 'walking', 'climbing'));

        if (!empty($_POST['interests-submit'])) {
            $indoorInterests = $_POST['indoorInterests'];
            $outdoorInterests = $_POST['outdoorInterests'];

            $f3->set('indoorInterests', $indoorInterests);
            $f3->set('outdoorInterests', $outdoorInterests);

            if (validateInterestsForm()) {
                $premiumMember = $_SESSION['membership'];

                if (!empty($indoorInterests)) {
                    //$_SESSION['indoorInterests'] = implode(', ', $indoorInterests);
                    $_SESSION['indoorInterests'] = $indoorInterests;
                    $premiumMember->setIndoorInterests($indoorInterests);
                } else {
                    $_SESSION['indoorInterests'] = "No indoor interests selected";
                    $premiumMember->setIndoorInterests(array());
                }

                if (!empty($outdoorInterests)) {
                    //$_SESSION['outdoorInterests'] = implode(', ', $outdoorInterests);
                    $_SESSION['outdoorInterests'] = $outdoorInterests;
                    $premiumMember->setOutDoorInterests($outdoorInterests);
                } else {
                    $_SESSION['outdoorInterests'] = "No outdoor interests selected";
                    $premiumMember->setOutDoorInterests(array());
                }

                $f3->reroute('/summary');
            }
        }

        $view = new Template();
        echo $view->render('views/interests_form.html');
    });

    // defines "Summary" route
    $f3->route('GET /summary', function() {
        //print_r($_SESSION);

        $member = $_SESSION['membership'];

        global $db;
        $db->insertMember($member);

        //session_unset();
        //session_destroy();

        $view = new Template();
        echo $view->render('views/summary.html');
    });

    // defines "Contact" route
    $f3->route('GET /contact', function() {
        //print_r($_SESSION);

        $view = new Template();
        echo $view->render('views/contact.html');
    });

    // runs f3
    $f3->run();