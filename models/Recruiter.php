<?php

require_once 'Database.php';

class Recruiter {
    
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    //Find user by email or username
    public function findRecruiterByEmail($email){
        $this->db->query('SELECT * FROM recruiters WHERE Email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        //Check row
        if($this->db->rowCount() > 0){
            return $row;
        }else{
            return false;
        }
    }

    //Register User
    public function register($data){
        $this->db->query('INSERT INTO recruiters (Role, Email, Password, Is_Checked) 
        VALUES (:role, :email, :password, :checked)');
        //Bind values
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':checked', 0);

        //Execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    //Login user
    public function login($nameOrEmail, $password){
        $row = $this->findRecruiterByEmail($nameOrEmail);

        if($row == false) return false;

        $hashedPassword = $row->users_pwd;
        if(password_verify($password, $hashedPassword)){
            return $row;
        }else{
            return false;
        }
    }

    //Reset Password
    public function resetPassword($newPwdHash, $tokenEmail){
        $this->db->query('UPDATE users SET users_pwd=:pwd WHERE users_email=:email');
        $this->db->bind(':pwd', $newPwdHash);
        $this->db->bind(':email', $tokenEmail);

        //Execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}