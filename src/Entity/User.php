<?php


namespace Entity;

/**
 * Description of User
 *
 * @author hello
 */
class User {
    
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    
    /*
     * @var int
     */
    private $id;
     /*
     * @var int
     */
    private $lastname;
     /*
     * @var int
     */
    private $firstname;
     /*
     * @var int
     */
    private $email;
     /*
     * @var int
     */
    private $password;
    
    private $role = self::ROLE_USER;
   
    
    public function getId() {
        return $this->id;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function setId($id) {
        $this->id = $id;
        
        return $this;
        
        
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        
        return $this;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        
        return $this;
    }

    public function setRole($role) {
        
        if(!in_array($role, [self::ROLE_USER, self::ROLE_ADMIN])){
            throw new \UnexpectedValueException('Unrecognized role value');
        }
        
        $this->role = $role;
        
        return $this;
    }
    
    public function getFullname() {
        return $this->firstname.' '.$this->lastname;
    }
    
    
    
    /**
     * 
     * @return bool
     */
    public function isAdmin() {
        return $this->role == self::ROLE_ADMIN;
    }


}
