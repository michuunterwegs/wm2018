<?php

namespace Models;

use PDO;
use \Utilities\Token;
use \Utilities\Mail;
use \Utilities\Flash;
use \Core\Config;
use \Core\Model;
use \Core\View;
use Respect\Validation\Validator;
use \Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data Initial property values (optional)
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Register a new user
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return boolean True if the user was registered, false otherwise
     */
    public static function register($username, $email, $password)
    {
        if (static::validateRegistrationData($username, $email, $password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $activation_token = $token->getValue();

            $timestamp = date("Y-m-d H:i:s");

            $sql = 'INSERT INTO users (email, user_name, password_hash, activation_hash, creation_date)
                    VALUES (:email, :user_name, :password_hash, :activation_hash, :creation_date)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':user_name', $username, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);
            $stmt->bindValue(':creation_date', $timestamp, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                static::sendActivationEmail($email, $activation_token);
                return true;
            }
        }

        return false;
    }

    /**
     * Validate registration data
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return boolean True if no error occurred, false otherwise
     */
    private static function validateRegistrationData($username, $email, $password)
    {
        $noErrors = true;

        if (!Validator::alnum()->noWhitespace()->length(3, 50)->validate($username)) {
            Flash::addMessage('Username should only contain alphanumeric charactes and be at least 3 charachters long.', Flash::ERROR);
            $noErrors = false;
        }

        if (static::usernameExists($username)) {
            Flash::addMessage('Username allready taken.', Flash::ERROR);
            $noErrors = false;
        }
        
        if (!Validator::email()->validate($email)) {
            Flash::addMessage('Invalid email address.', Flash::ERROR);
            $noErrors = false;
        }

        if (static::emailExists($email)) {
            Flash::addMessage('Email address allready taken.', Flash::ERROR);
            $noErrors = false;
        }

        if(!Validator::noWhitespace()->length(6, null)->validate($password)) {
            Flash::addMessage('Password must be at least 8 characters.', Flash::ERROR);
            $noErrors = false;
        }

        return $noErrors;
    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     * @return boolean True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email)
    {
        $user = static::findByEmail($email);

        return $user ? true : false;
    }

    /**
     * See if a user record already exists with the specified username
     *
     * @param string $username Username to search for
     * @return boolean True if a record already exists with the specified username, false otherwise
     */
    public static function usernameExists($username)
    {
        $user = static::findByUsername($username);

        return $user ? true : false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * 
                FROM users 
                WHERE email = :email';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Find a user model by username
     *
     * @param string $username
     * @return mixed User object if found, false otherwise
     */
    public static function findByUsername($username)
    {
        $sql = 'SELECT * 
                FROM users 
                WHERE user_name = :username';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Delete user
     *
     * @param string $id User ID
     * @return void
     */
    public static function delete($id)
    {
        $sql = 'DELETE FROM users 
                WHERE ID = :id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Authenticate a user by username and password. User account has to be active.
     *
     * @param string $username
     * @param string $password Password of user
     * @return mixed The user object or false if authentication fails
     */
    public static function authenticate($username, $password)
    {
        $user = static::findByUsername($username);

        if ($user && $user->is_active) {

            if (password_verify($password, $user->password_hash)) {

                return $user;
            }

        }

        return false;
    }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions to the user specified
     *
     * @param string $email The email address
     * @return void
     */
    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {

            if ($user->startPasswordReset()) {

                $user->sendPasswordResetEmail();
            }

        } else {

            Flash::addMessage('Unkown email address.', Flash::ERROR);
            return false;
        }
    }

    /**
     * Start the password reset process by generating a new token and expiry
     *
     * @return void
     */
    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();
        $expiry_timestamp = time() + 60 * 60 * 2;  // 2 hours from now

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                    password_reset_expires_at = :expires_at
                WHERE id = :id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions in an email to the user
     *
     * @return void
     */
    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('User/Password/email_reset.txt', ['url' => $url]);
        $html = View::getTemplate('User/Password/email_reset.html', ['url' => $url]);

        if(Mail::send($this->email, 'Password reset', $text, $html)) {

            Flash::addMessage('Password reset email was sent.');

        } else {

            Flash::addMessage('Email could not be sent, please try again.', Flash::ERROR);
        }
    }

    /**
     * Find a user model by password reset token and expiry
     *
     * @param string $token Password reset token sent to user
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {

            // Check password reset token hasn't expired
            if (strtotime($user->password_reset_expires_at) > time()) {

                return $user;
            }
        }
    }

    /**
     * Reset the password
     *
     * @param string $password The new password
     * @return boolean True if the password was updated successfully, false otherwise
     */
    public function resetPassword($password)
    {
        $this->password = $password;

        if (Validator::noWhitespace()->length(6, null)->validate($this->password)) {
            
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users
                    SET password_hash = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id = :id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
                                          
            return $stmt->execute();
        }

        return false;
    }

    /**
     * Send an email to the user containing the activation link
     *
     * @return void
     */
    public static function sendActivationEmail($email, $activation_token)
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $activation_token;

        $text = View::getTemplate('User/Signup/email_activation.txt', [ 'url' => $url ]);
        $html = View::getTemplate('User/Signup/email_activation.html', [ 'url' => $url ]);

        Mail::send($email, 'Account activation', $text, $html);
    }

    /**
     * Activate the user account with the specified activation token
     *
     * @param string $value Activation token from the URL
     * @return void
     */
    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1, activation_hash = null
                WHERE activation_hash = :hashed_token';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();
    }
    
    /**
     * Update the user profile
     *
     * @param array $data Data from the edit profile form
     * @return boolean True if the data was updated, false otherwise
     */
    public function updateProfile($data)
    {
        $this->email = $data['email'];

        // Only validate and update the password if a value provided
        if ($data['password'] != '') {
            $this->password = $data['password'];
        }

        if ($this->validate()) {

            $sql = 'UPDATE users
                    SET email = :email';

            // Add password if it's set
            if (isset($this->password)) {
                $sql .= ', password_hash = :password_hash';
            }

            $sql .= "\nWHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            // Add password if it's set
            if (isset($this->password)) {

                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->rowCount(); 
        }

        return false;
    }

    
    /**
     * Update the users last login
     *
     * @return void
     */
    public function updateLastLogin() {

        $timestamp = date("Y-m-d H:i:s");

        $sql = 'UPDATE users
                SET last_login = :timestamp
                WHERE id = :id';           
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);

        $stmt->execute();

        $this->updateLoginCount();
    }

    /**
     * Increment the users login count
     *
     * @return void
     */
    private function updateLoginCount() {

        $sql = 'UPDATE users
                SET login_count =
                    login_count + 1
                WHERE id = :id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Get all users
     *
     * @return mixed User objects if found, null otherwise
     */
    public static function getAll()
    {
        $sql = 'SELECT * FROM users';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
