<?php


namespace Repository;

use Entity\User;

/**
 * Description of UserRepository
 *
 * @author hello
 */
class UserRepository extends RepositoryAbstract {
    /*
     * 
     * @param type $email
     * @return User
     */
    public function findByEmail($email) {
        $dbUser = $this->db->fetchAssoc(
                'SELECT * FROM user WHERE email = :email',
                [
                    ':email' => $email
                ]
        );
        
        if(!empty($dbUser)){
            return $this->buildEntity($dbUser);
        }
    }
    
  private function buildEntity(array $data) {
        
      $user = new \Entity\User();
      
      $user 
           ->setId($data['id'])
           ->setLastname($data['lastname'])
           ->setFirstname($data['firstname'])
           ->setEmail($data['email'])
           ->setPassword($data['password'])
           ->setRole($data['role'])
       ;
      
      return $user;
    }
    
     public function save(User $user){
        $data =[
            'lastname' => $user->getLastname(),
            'firstname' => $user->getFirstname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ];
        
        if($user->getId()){
            $this->db->update('user', $data, 
                    [
                        'id'=>$user->getId()
                    ]
                );
        } else{
            $this->db->insert('user', $data);
            $user->setId($this->db->lastInsertId());
        }
    }
    
}