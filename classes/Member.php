<?php
    class Member
    {
        private $_fName;
        private $_lName;
        private $_age;
        private $_gender;
        private $_phone;
        private $_email;
        private $_state;
        private $_seeking;
        private $_bio;

        function __construct($fName, $lName, $age, $gender, $phone)
        {
            $this->_fName = $fName;
            $this->_lName = $lName;
            $this->_age = $age;
            $this->_gender = $gender;
            $this->_phone = $phone;
        }

        function setFirstName($fname)
        {
            $this->_fName = $fname;
        }

        function getFirstName()
        {
            return $this->_fName;
        }

        function setLastName($lname)
        {
            $this->_lName = $lname;
        }

        function getLastName()
        {
            return $this->_lName;
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
            return $this->_seeking;
        }

        function setBio($bio)
        {
            $this->_bio = $bio;
        }

        function getBio()
        {
            return $this->_bio;
        }
    }
