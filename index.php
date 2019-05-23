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

    // defines a default route
    $f3->route('GET /', function() {
        //echo '<h1>Welcome To My Dating Website!</h1>';

        // displays a view
        $view = new Template();
        echo $view->render('views/home.html');
    });

    // defines "Personal Information" route
    $f3->route('GET|POST /personal_info_form', function($f3) {

// remove all session variables
//session_unset();

// destroy the session
//session_destroy();

        // defines an array of gender with available options
        $f3->set('genderOptions', array('Male', 'Female'));

        // if the form has been submitted (via POST), validates it
        if (!empty($_POST)) {
            // gets all the data from the form
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $age = $_POST['age'];
            if (!empty($_POST['gender'])) {
                $gender = $_POST['gender'];
            } else {
                $gender = "(Not specified)";
            }
            $phone = $_POST['phone'];
            if (!empty($_POST['premium'])) {
                $premium = $_POST['premium'];
            } else {
                $premium = "No";
            }

            echo "fist name: $fname<br>";
            echo "last name: $lname<br>";
            echo "age: $age<br>";
            echo "gender: $gender<br>";
            echo "phone: $phone<br>";
            echo "premium: $premium<br>";

            // stores all the data to hive
            $f3->set('fname', $fname);
            $f3->set('lname', $lname);
            $f3->set('age', $age);
            $f3->set('gender', $gender);
            $f3->set('phone', $phone);
            $f3->set('premium', $premium);

            /*
            // creates the appropriate class object
            if ($premium == 'yes') {
                $premiumMember = new PremiumMember($fname, $lname, $age, $gender, $phone);
                $f3->set('member', $premiumMember);
            } else {
                $member = new Member($fname, $lname, $age, $gender, $phone);
                $f3->set('member', $member);
            }
            */

            if (validatePersonalInfoForm()) {
                if ($premium == 'yes') {
                    $premiumMember = new PremiumMember($fname, $lname, $age, $gender, $phone);
                    $f3->set('membership', $premiumMember);
                    $_SESSION['membership'] = $premiumMember;
                } else {
                    $regularMember = new Member($fname, $lname, $age, $gender, $phone);
                    $f3->set('membership', $regularMember);
                    $_SESSION['membership'] = $regularMember;
                }
                /*
                // writes data to session variables
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;
                $_SESSION['age'] = $age;
                $_SESSION['gender'] = $gender;
                $_SESSION['phone'] = $phone;
                $_SESSION['premium'] = $premium;
                */

                //$_SESSION['member'] = $premiumMember;

                // redirects to next form: Profile Form
                //$f3->reroute('/profile_form');
            //} else if (validatePersonalInfoForm()) {
                /*
                // writes data to session variables
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;
                $_SESSION['age'] = $age;
                $_SESSION['gender'] = $gender;
                $_SESSION['phone'] = $phone;
                $_SESSION['premium'] = $premium;
                */

                //$_SESSION['member'] = $regularMember;

                // redirects to next form: Profile Form
                $f3->reroute('/profile_form');
            }
        }

        $view = new Template();
        echo $view->render('views/personal_info_form.html');
    });

    // defines "Profile" route
    $f3->route('GET|POST /profile_form', function($f3) {
        $f3->set('stateOptions', array('', 'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            'Idaho', 'Illinois', 'Indiana', 'Lowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
            'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'Ohio', 'Oklahoma', 'Oregon',
            'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia',
            'Wisconsin', 'Wyoming'));

        $f3->set('seekingGenderOptions', array('Male', 'Female'));

        if (!empty($_POST)) {
            $email = $_POST['email'];
            $state = $_POST['state'];
            $seeking = $_POST['seeking'];
            $bio = $_POST['bio'];

            echo "email: $email<br>";
            echo "state: $state<br>";
            echo "seeking: $seeking<br>";
            echo "bio: $bio<br>";

            // todo: necessary? what's the point?
            $f3->set('email', $email);
            $f3->set('state', $state);
            $f3->set('seeking', $seeking);
            $f3->set('bio', $bio);

            if (validateProfileForm()) {
                /*
                $_SESSION['email'] = $email;
                $_SESSION['state'] = $state;
                $_SESSION['seeking'] = $seeking;
                $_SESSION['bio'] = $bio;
                */
                $memberShip = $_SESSION['membership'];

                $memberShip->setEmail($email);
                if (!empty($state)) {
                    $memberShip->setState($state);
                }
                if (!empty($seeking)) {
                    $memberShip->setSeeking($seeking);
                }
                if (!empty($bio)) {
                    $memberShip->setBio($bio);
                }

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
        $f3->set('indoorOptions', array('tv', 'movies', 'cooking', 'board games', 'puzzles', 'reading', 'playing cards', 'video games'));
        $f3->set('outdoorOptions', array('hiking', 'biking', 'swimming', 'collecting', 'walking', 'climbing'));

        if ((isset($_POST['submit']))) {
            $indoorInterests = $_POST['indoor'];
            $outdoorInterests = $_POST['outdoor'];

            //$f3->set('indoorInterests', $indoorInterests);
            //$f3->set('outdoorInterests', $outdoorInterests);

            if (validateInterestsForm()) {
                $premiumMember = $_SESSION['membership'];

                if (!empty($indoorInterests)) {
                    //$_SESSION['indoorInterests'] = implode(', ', $indoorInterests);
                    $premiumMember->setIndoorInterests($indoorInterests);
                } else {
                    //$_SESSION['indoorInterests'] = "(No indoor interests selected)";

                }

                if (!empty($outdoorInterests)) {
                    //$_SESSION['outdoorInterests'] = implode(', ', $outdoorInterests);
                    $premiumMember->setOutDoorInterests($outdoorInterests);
                } else {
                    //$_SESSION['outdoorInterests'] = "(No outdoor interests selected)";
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
        //$f3->set('indoor', $_POST['indoor-interests']);
        //$f3->set('outdoor', $_POST['outdoor-interests']);



        $view = new Template();
        echo $view->render('views/summary.html');
    });

// remove all session variables
//session_unset();

// destroy the session
//session_destroy();

    $f3->route('GET /contact', function() {
        // remove all session variables
        session_unset();

        // destroy the session
        session_destroy();

        $view = new Template();
        echo $view->render('views/contact.html');
    });

    // runs f3
    $f3->run();
