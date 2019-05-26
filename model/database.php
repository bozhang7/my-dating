<?php

require '/home/bzhanggr/config.php';

/*
CREATE TABLE member (
    member_id INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(50),
    lname VARCHAR(50),
    age INT(3),
    gender VARCHAR(15),
    phone VARCHAR(30),
    email VARCHAR(30),
    state VARCHAR(30),
    seeking VARCHAR(15),
    bio VARCHAR(255),
    premium TINYINT
);

CREATE TABLE interest (
    interest_id INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    interest VARCHAR(30),
    type VARCHAR(30),
	UNIQUE (interest)
);

CREATE TABLE member_interest (
    member_id INT(3) REFERENCES member(member_id),
    interest_id INT(3) REFERENCES interest(interest_id),
    PRIMARY KEY (member_id, interest_id)
);
*/

class Database
{
    private $_dbh;

    function __construct()
    {
        $this->connect();
    }

    function connect()
    {
        try {
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            //echo 'connected!';
            return $this->_dbh;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    function insertMember($member)
    {
        $fname = $member->getFirstName();
        $lname = $member->getLastName();
        $age = $member->getAge();
        $gender = $member->getGender();
        $phone = $member->getPhone();
        $email = $member->getEmail();
        $state = $member->getState();
        $seeking = $member->getSeeking();
        $bio = $member->getBio();
        if ($member instanceof PremiumMember) {
            $premium = 1;
        } else {
            $premium = 0;
        }

        // 1. defines the query - enters member info into table member
        $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, state, seeking, bio, premium) VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium)";

        // 2. prepares the statement
        $statement = $this->_dbh->prepare($sql);

        // 3. binds the parameters
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':age', $age, PDO::PARAM_STR);
        $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':state', $state, PDO::PARAM_STR);
        $statement->bindParam(':seeking', $seeking, PDO::PARAM_STR);
        $statement->bindParam(':bio', $bio, PDO::PARAM_STR);
        $statement->bindParam(':premium', $premium, PDO::PARAM_STR);

        // 4. executes the statement
        $statement->execute();

        // 5. gets the primary key of the last inserted member row
        $memberId = $this->_dbh->lastInsertId();
        echo "member id: $memberId<br>";

        // for premium members, process interests if there is any
        if ($premium == 1) {
            $indoorInterests = $member->getInDoorInterests();
            $outdoorInterests = $member->getOutDoorInterests();

            // indoor interests
            if (count($indoorInterests) != 0) {
                // processes each indoor interest
                foreach ($indoorInterests as $interest) {
                    // adds the interest into database if new
                    $sql = "INSERT IGNORE INTO interest (interest, type) VALUES (:interest, 'indoor')"; //todo: UNIQUE (column 1, column 2)?
                    $statement = $this->_dbh->prepare($sql);
                    $statement->bindParam(':interest', $interest, PDO::PARAM_STR);
                    $statement->execute();

                    $interestId = $this->_dbh->lastInsertId();
                    echo "indoor interest id: $interestId<br>";

                    // checks if the interest has been entered before
                    if ($interestId != 0) {
                        $sql = "INSERT INTO member_interest (member_id, interest_id) VALUES (:memberId, :interestId)";
                        $statement = $this->_dbh->prepare($sql);
                        $statement->bindParam(':memberId', $memberId, PDO::PARAM_STR);
                        $statement->bindParam(':interestId', $interestId, PDO::PARAM_STR);
                        $statement->execute();
                    } else {
                        // find the id of the interest that has been entered into the database already
                        //$interestId = ???
                        $sql = "INSERT INTO member_interest (member_id, interest_id) VALUES (:memberId, :interestId)";
                        $statement = $this->_dbh->prepare($sql);
                        $statement->bindParam(':memberId', $memberId, PDO::PARAM_STR);
                        $statement->bindParam(':interestId', $interestId, PDO::PARAM_STR);
                        $statement->execute();
                    }
                }
            }

            // outdoor interests
            if (count($outdoorInterests) != 0) {
                foreach ($outdoorInterests as $interest) {
                    $sql = "INSERT IGNORE INTO interest (interest, type) VALUES (:interest, 'outdoor')";
                    $statement = $this->_dbh->prepare($sql);
                    $statement->bindParam(':interest', $interest, PDO::PARAM_STR);
                    $statement->execute();

                    $interestId = $this->_dbh->lastInsertId();
                    echo "outdoor interest id: $interestId<br>";

                    if ($interestId != 0) {
                        $sql = "INSERT INTO member_interest (member_id, interest_id) VALUES (:memberId, :interestId)";
                        $statement = $this->_dbh->prepare($sql);
                        $statement->bindParam(':memberId', $memberId, PDO::PARAM_STR);
                        $statement->bindParam(':interestId', $interestId, PDO::PARAM_STR);
                        $statement->execute();
                    } else {
                        // find the id of the interest that has been entered into the database already
                        //$interestId = ???
                        $sql = "INSERT INTO member_interest (member_id, interest_id) VALUES (:memberId, :interestId)";
                        $statement = $this->_dbh->prepare($sql);
                        $statement->bindParam(':memberId', $memberId, PDO::PARAM_STR);
                        $statement->bindParam(':interestId', $interestId, PDO::PARAM_STR);
                        $statement->execute();
                    }
                }
            }
        }
    }

    function getMembers()
    {
        // 1. defines the query - enters member info into table member
        //$sql = "SELECT * FROM member ORDER BY member.lname";
        //$sql = "SELECT * FROM member JOIN member_interest USING (member_id)";
        $sql = "SELECT member.member_id, member.fname, member.lname, member.age, member.phone, member.email, member.state, member.gender, member.seeking, member.premium, interest.interest
                FROM member_interest
                JOIN member ON member_interest.member_id = member.member_id
                JOIN interest ON member_interest.interest_id = interest.interest_id
                ORDER BY member.lname";

        // 2. prepares the statement
        $statement = $this->_dbh->prepare($sql);

        // 3. binds the parameters

        // 4. executes the statement
        $statement->execute();

        // 5. returns the result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function getMember($member_id)
    {
        $sql = "SELECT * FROM member WHERE member.member_id = :id";
        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':id', $member_id, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function getInterests($member_id)
    {
        $sql = "SELECT * FROM interest WHERE interest.interest_id = :id";
        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':id', $member_id, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}