<?php
    function validatePersonalInfoForm() {
        global $f3;
        $isValid = true;

        // checks first name
        if (!validateName($f3->get('fname'))) {
            $isValid = false;
            $f3->set("errors['fname']", "Required field, please re-enter first name with letters only");
        }

        // checks last name
        if (!validateName($f3->get('lname'))) {
            $isValid = false;
            $f3->set("errors['lname']", "Required field, please re-enter last name with letters only");
        }

        // checks age
        if (!validateAge($f3->get('age'))) {
            $isValid = false;
            $f3->set("errors['age']", "Required field, please re-enter age with a number from 18 to 118");
        }

        // todo: validate gender?

        // checks phone number
        if (!validatePhone($f3->get('phone'))) {
            $isValid = false;
            $f3->set("errors['phone']", "Required field, please re-enter phone number with dash");
        }

        return $isValid;
    }

    /*
     * Checks if a string contains all alphabetic
     * @return true|false
     */
    function validateName($name) {
        return ctype_alpha($name);
    }

    /*
     * Checks if an age is all numeric and between 18 and 118
     * @return true|false
     */
    function validateAge($age) {
        return ctype_digit($age) && $age >= 18 && $age <= 118;
    }

    /*
     * Checks if a phone number is valid
     * @true|false
     */
    function validatePhone($phone) {
        return preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone);
    }

    function validateProfileForm() {
        global $f3;
        $isValid = true;

        if (!validateEmail($f3->get('email'))) {
            $isValid = false;
            $f3->set("errors['email']", "Please re-enter email");
        }

        // todo: validate state?
        // todo: validate seeking?
        // todo: validate bio?

        return $isValid;
    }

    /*
     * Checks if an email is valid
     * @return true|false
     */
    function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function validateState($states) {

    }

    function validateSeeking() {

    }

    function validateBio() {

    }

    function validateInterestsForm() {
        global $f3;
        $isValid = true;

        return $isValid;
    }

    /*
     * Checks each selected indoor interest against a list of valid options
     */
    function validateIndoor($indoor) {
    }

    /*
     * Checks each selected outdoor interest against a list of valid options
     */
    function validateOutdoor($outdoor) {

    }