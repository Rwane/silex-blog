<?php


namespace Controller;

use Entity\User;

/**
 * Description of UserController
 *
 * @author hello
 */
class UserController extends ControllerAbstract {
    
    public function registerAction(){
        
        $user = new User();
        $errors = [];
        
        if(!empty($_POST)){
            $this->sanitizePost();
            
            $user
                 ->setLastname($_POST['lastname'])   
                 ->setFirstname($_POST['firstname'])   
                 ->setEmail($_POST['email']) 
            ;  
            
            if(empty($_POST['lastname'])){
                $errors['lastname'] = 'le nom est obligatoire';
            }elseif(strlen($_POST['firstname']) > 100){
                $errors['lastname'] = 'le nom est trop long';
            }
            
            if(empty($_POST['firstname'])){
                $errors['firstname'] = 'le prenom est obligatoire';
            }elseif(strlen($_POST['firstname']) > 100){
                $errors['firstname'] = 'le prenom est trop long';
            }
            
            if(empty($_POST['email'])){
                $errors['email'] = 'le email est obligatoire';
            }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $errors['email'] = 'le email est pas valide';
            }elseif (!is_null($this->app['user.repository']->findByEmail($_POST['email']))){
                $errors['email'] = 'le email est deja utilisé';
            }
            
            if(empty($_POST['password'])){
                $errors['password'] = 'le password est obligatoire';
            }elseif (!preg_match('/^[a-zA-Z0-9_-]{6,20}/',$_POST['password'])){
                $errors['password'] = 'le mdp doit faire entre 6 et 20 caractere et ne doit contenir que des lettres, des chiffres ou les caracteres _ et -';
            }
              
            if(empty($_POST['password_confirm'])){
                $errors['password'] = 'le password confirm est obligatoire';
            }elseif ($_POST['password_confirm'] != $_POST['password']){
                $errors['password'] = "la confirmation n'est pas identique au mot de passe";
            }
            
            if(empty($errors)){
               $user->setPassword($this->app['user.manager']->encodePassword($_POST['password']));
               $this->app['user.repository']->save($user);
               
               return $this->redirectRoute('homepage');
            } else {
                $message = '<strong>Le formulaire contient des erreurs</strong>';
                $message .= '<br>' .implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
            
        }
        
        return $this->render('user/register.html.twig',
                [
                    'user' => $user,
                ]  
        );
    }
    
    public function loginAction(){
        
        $email = '';
        
        if(!empty($_POST)){
            $this->sanitizePost();
            
            $email = $_POST['email'];
            
            $user = $this->app['user.repository']->findByEmail($email);
            
            if(!is_null($user)){
                if($this->app['user.manager']->verifyPassword($_POST['password'], $user->getPassword())){
                    $this->app['user.manager']->login($user);
                    
                    return $this->redirectRoute('homepage');
                }
            }
            
            $this->addFlashMessage('Identification incorrecte', 'error');
        }
        
        return $this->render(
                'user/login.html.twig',
                
                [
                    'email' => $email
                ]
        );
    }
    
    /**
     * 
     * @return 
     */
    public function logoutAction() {
        $this->app['user.manager']->logout();
        
        return $this->redirectRoute('homepage');
    }
    
}
