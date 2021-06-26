<?php
require_once './config.php';

$errors = [];

/**
 * Check for errors in POST variables.
 * error codes:
 * 1: name: not exists, is longer than 63 chars or shorter than 2 chars, dont match regexp.
 * 2: email: not exists, is longer than 255 chats, is not an email.
 * 3: email: not a valid email provider.
 * 4: password: not exists, not a valid and strong password.
 * 5: confirm-password: not exists or not equal to password.
 */

if ( !isset($_POST['name']) || strlen($_POST['name']) > 63 || strlen($_POST['name']) < 2 || !preg_match('/^[a-zA-Z- ]+$/', '')) {
    $errors[] = 1;
}

if ( !isset($_POST['email']) || strlen($_POST['email']) > 255 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
    $errors[] = 2;
}else
if(!checkdnsrr(substr($_POST['email'], strpos($_POST['email'], '@') + 1))) {
    $errors[] = 3;
}

if ( !isset($_POST['password']) || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[~?!@#\$%^&*])(?=.{8,})/', $_POST['password']) ) {
    $errors[] = 4;
} else
if(!isset($_POST['confirm-password']) || $_POST['confirm-password'] !== $_POST['password']) {
    $errors[] = 5;
}


// if there are no errors
if (count($errors) === 0) {

    // Create new Database object
    $db = new Database(USERS_DBFILE);

    if ($db) {

        // Select matching users form database
        $res = $db->select('*', 'Users', 'email = '.$_POST['email']);

        // if there are no users
        if (!$res || sqlite_num_rows($res) === 0) {

            // Hash password bcs security.
            $hashed_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
            // Insert into database
            $res1 = $db->insert('Users', 'username, email, password, role', $_POST['username'].', '.$_POST['email'].', '.$hashed_pass.$_POST['role'] );

            // If succeded
            if ($res1) {
                
            }

        }
        // if there are users
        else {
            var_dump($res);
        }



    }

    // If there are errors

    else {

    }
}

json_encode($errors);
