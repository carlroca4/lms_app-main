<?php

class database{

    function opencon(): PDO{
        return new PDO('mysql:host=localhost;dbname=lms_app', 'root', '');
    }

    function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path){
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            // Insert into Users table
            $stmt = $con->prepare("INSERT INTO users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password]);

            // Get the newly inserted user_id
            $userId = $con->lastInsertID();

            // Insert into users_pictures table
            $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userId, $profile_picture_path]);

            $con->commit();
            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function insertAddress($userID, $street, $barangay, $city, $province)
    {
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            // Insert into address table
            $stmt = $con->prepare("INSERT INTO address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
            $stmt->execute([$street, $barangay, $city, $province]);

            // Get the newly inserted address_id
            $addressId = $con->lastInsertID();

            // Link User and Address into Users_Address table
            $stmt = $con->prepare("INSERT INTO users_address (user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$userID, $addressId]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function loginUser($email, $password){
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['user_password'])) {
            return $user;
        } else {
            return false;
        }
    }

    function insertAuthor($authorFirstName, $authorLastName, $authorBirthYear, $authorNationality)
    {
        $con = $this->opencon();
        try {
            $stmt = $con->prepare("INSERT INTO authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$authorFirstName, $authorLastName, $authorBirthYear, $authorNationality]);
        } catch (PDOException $e) {
            return false;
        }
    }

    function insertGenre($genreName)
    {
        $con = $this->opencon();
        try {
            $stmt = $con->prepare("INSERT INTO genres (genre_name) VALUES (?)");
            $stmt->execute([$genreName]);
            // $genreId = $con->lastInsertID(); // Not used
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    function viewAuthors(): array
    {
        $con = $this->opencon();
        return $con->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add getAuthors for admin_homepage.php
    function getAuthors(): array
    {
        $con = $this->opencon();
        $stmt = $con->query("SELECT author_id, author_FN, author_LN, author_birthday, author_nat FROM authors");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function viewAuthorsID($id): mixed 
    {
        $con = $this->opencon();
        $stmt = $con->prepare(query: "SELECT * FROM authors WHERE author_id = ?");
        $stmt->execute(params: [$id]);
        return $stmt->fetch(mode: PDO::FETCH_ASSOC);
    }

    function deleteAuthor($authorId)
    {
        $con = $this->opencon();
        $stmt = $con->prepare("DELETE FROM authors WHERE author_id = ?");
        return $stmt->execute([$authorId]);
    }
    function updateUser($user_id, $firstname, $lastname, $birthday,$sex, $username, $password) {
    try {
        $con = $this->opencon();
        $con->beginTransaction();
        $query = $con->prepare("UPDATE users SET user_firstname=?, user_lastname=?,user_birthday=?, user_sex=?,user_name=?, user_pass=? WHERE user_id=?");
        $query->execute([$firstname, $lastname,$birthday,$sex,$username, $password, $user_id]);
        // Update successful
        $con->commit();
        return true;
    } catch (PDOException $e) {
        // Handle the exception (e.g., log error, return false, etc.)
         $con->rollBack();
        return false; // Update failed
    }
}

    function updateAuthor($authorId, $firstName, $lastName, $birthYear, $nationality)
    {
        $con = $this->opencon();
        $stmt = $con->prepare("UPDATE authors SET author_FN = ?, author_LN = ?, author_birthday = ?, author_nat = ? WHERE author_id = ?");
        return $stmt->execute([$firstName, $lastName, $birthYear, $nationality, $authorId]);
    }

    function getGenres() {
        $pdo = $this->opencon();
        $stmt = $pdo->query("SELECT genre_id, genre_name FROM genres");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
