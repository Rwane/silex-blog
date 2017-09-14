<?php


namespace Controller\Admin;

use Controller\ControllerAbstract;
use Entity\Article;
use Entity\Category;

class ArticleController extends ControllerAbstract {
    
    public function listAction(){
       $articles = $this->app['article.repository']->findAll();
       
       return $this->render(
               'admin/article/list.html.twig',
               [
                 'articles' => $articles
               ]
        );
    }
    
       public function editAction($id = null){
        
        if(is_null($id)){
            $article = new Article();
            $article->setCategory(new Category())
                    ->setAuthor($this->app['user.manager']->getUser());
        } else{
            $article = $this->app['article.repository']->find($id);
            
            if(is_null($article)){
                $this->app->abort(404);
            }
        }
        $errors = [];
        
        if(!empty($_POST)){
            $this->sanitizePost();
            
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $article->setHeader($_POST['header']);
            
            $article->getCategory()->setId($_POST['category']);
            
            //controle les champs de formulaire
            if(empty($_POST['title'])){
                $errors['title'] = 'Le titre est obligatoire';
            } elseif(strlen($_POST['title']) > 20){
                $errors['title'] = 'Le titre ne doit pas dépasser 20 caractères';
            }
            
            if(empty($_POST['header'])){
                $errors['header'] = 'Le header est obligatoire';
            }
            if(empty($_POST['content'])){
                $errors['content'] = 'Le content est obligatoire';
            }
            
            if(empty($_POST['category'])){
                $errors['category'] = 'La category t est obligatoire';
            }
            
            if(empty($errors)){
                $this->app['article.repository']->save($article);
                $this->addFlashMessage('article  est enregistrée');
                return $this->redirectRoute('admin_articles');
            } else {
                $message = '<strong>Le formulaire contient des erreurs</strong>';
                $message .= '<br>' .implode('<br>', $errors);
                $this->addFlashMessage($message, 'error');
            }
        }
        
        $categories = $this->app['category.repository']->findAll();
        
        return $this->render(
              'admin/article/edit.html.twig',
                ['article' => $article, 'categories' => $categories]
        );
        
        
        
        
     }
     
     public function deleteAction($id){
        $article = $this->app['article.repository']->find($id);
        
        if (is_null($article)){
            $this->app->abort(404);
        }
         $this->app['article.repository']->delete($article);
        $this->addFlashMessage('article est supprimée');
        
        return $this->redirectRoute('admin_articles');
    }
}
