<?php



namespace Service;

use Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
 

class UserManager {
    
    
    /**
     * 
     * @var Session
     */
    
    private $session;
    
   public function __construct(Session $session) {
         $this->session = $session;
    }

    /**
     * 
     * @param string $plainPassword
     * @return string
     */
    public function encodePassword($plainPassword){
        
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }
    
    
    /**
     * 
     * @param string $plainPassword
     * @param string $encodedPassword
     * @return bool
     */
    public function verifyPassword($plainPassword, $encodedPassword) {
        
        return password_verify($plainPassword, $encodedPassword ) ;
    }
    
    public function login(User $user) {
        $this->session->set('user', $user);
    }
    
    public function logout() {
        $this->session->remove('user');
    } 
    
    
    
    /**
     * 
     * @return User|null
     */
    public function getUser() {
        return $this->session->get('user');
    }

    /**
     * 
     * @return string
     */
    public function getUserName() {
        if($this->session->has('user')){
            return $this->getUser()->getFullName();
        }
        
        return '';
    }
    
    
    /**
     * 
     * @return bool
     */
    public function isAdmin(){
        
        return $this->session->has('user') && $this->getUser()->isAdmin();
    }
    
    
}
