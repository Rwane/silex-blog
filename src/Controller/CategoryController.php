<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller;


class CategoryController extends ControllerAbstract {
    
    
    public function indexAction($id) {
        
        $category = $this->app['category.repository']->find($id);
        $articles= $this->app['article.repository']->findByCategory($category);
        
        if(empty($category)){
            $this->app->abort(404);
        }
        
        return $this->render(
             'category/index.html.twig',
              [
                  'category' => $category,
                   'articles' => $articles
              ]
        );
    }
    
    public function menuAction(){
        
        $categories = $this->app['category.repository']->findAll();
       
        return $this->render(
                
                'category/menu.html.twig',
                [
                    'categories' => $categories
                ]
        );
    }
}
