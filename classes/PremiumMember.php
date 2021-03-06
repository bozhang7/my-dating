<?php
    class PremiumMember extends Member
    {
        private $_inDoorInterests;
        private $_outDoorInterests;

        function __construct($fName, $lName, $age, $gender, $phone, $inDoorInterests=array(), $outDoorInterests=array())
        {
            // calls the parent class constructor
            parent::__construct($fName, $lName, $age, $gender, $phone);

            // sets private fields with passed in parameters
            $this->_inDoorInterests = $inDoorInterests;
            $this->_outDoorInterests = $outDoorInterests;
        }

        function setInDoorInterests($inDoorInterests)
        {
            $this->_inDoorInterests = $inDoorInterests;
        }

        function getInDoorInterests()
        {
            return $this->_inDoorInterests;
        }

        function setOutDoorInterests($outDoorInterests)
        {
            $this->_outDoorInterests = $outDoorInterests;
        }

        function getOutDoorInterests()
        {
            return $this->_outDoorInterests;
        }
    }
