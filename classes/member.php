<?php
    class Member
    {
        private $_fname;
        private $_lname;
        private $_age;
        private $_gender;
        private $_phone;
        private $_email;
        private $_state;
        private $_seeking;
        private $_bio;

        function __construct($fname, $lname, $age, $gender, $phone)
        {
            $_fname = $fname;
            $_lname = $lname;
            $_age = $age;
            $_gender = $gender;
            $_phone = $phone;
        }

        function setFirstName($fname)
        {
            $this->_fname = $fname;
        }

        function getFirstName()
        {
            return $this->_fname;
        }

        function setLastName($lname)
        {
            $this->_lname = $lname;
        }

        function getLastName()
        {
            return $this->_lname;
        }

        function setAge($age)
        {
            $this->_age = $age;
        }

        function getAge()
        {
            return $this->_age;
        }

        function setGender($gender)
        {
            $this->_gender = $gender;
        }

        function getGender()
        {
            return $this->_gender;
        }

        function setPhone($phone)
        {
            $this->_phone = $phone;
        }

        function getPhone()
        {
            return $this->_phone;
        }

        function setEmail($email)
        {
            $this->_email = $email;
        }

        function getEmail()
        {
            return $this->_email;
        }

        function setState($state)
        {
            $this->_state = $state;
        }

        function getState()
        {
            return $this->_state;
        }

        function setSeeking($seeking)
        {
            $this->_seeking = $seeking;
        }

        function getSeeking()
        {
            return $this->seeking;
        }

        function setBio($bio)
        {
            $this->_bio = $bio;
        }

        function getBio()
        {
            return $this->bio;
        }
    }
