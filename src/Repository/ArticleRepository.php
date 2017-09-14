<?php



namespace Repository;

use Entity\Article;
use Entity\Category;
use Entity\User;


class ArticleRepository extends RepositoryAbstract {
    
     public function findAll(){
         
       $query = <<<SQL
               
SELECT a.*,
        c.name AS category_name,
         u.lastname,
         u.firstname
FROM article a 
JOIN category c ON a.category_id = c.id
JOIN user u on a.author_id = u.id
ORDER BY a.id DESC
SQL;
         
        $dbArticles = $this->db->fetchAll($query);
        $Articles = [];
        
        foreach ($dbArticles as $dbArticle){
            $Articles[] = $this->buildEntity($dbArticle);
        }
        
        return $Articles;
    }
    
     private function buildEntity(array $data){
       
         $category = new Category();
         
         $category
                 ->setId($data['category_id'])
                 ->setName($data['category_name'])
         ;
         
         $author = new User();
         
         $author
                 ->setId($data['author_id'])
                 ->setLastname($data['lastname'])
                 ->setFirstname($data['firstname']);
         
         $article = new Article();
        
        $article
            ->setId($data['id'])
            ->setHeader($data['header'])
            ->setContent($data['content'])
            ->setTitle($data['title'])
            ->setCategory($category)
            ->setAuthor($author)
        ;
        
        return $article;
        
    }
    
     public function find($id){
         
         $query = <<<SQL
               
SELECT a.*,
        c.name AS category_name,
         u.lastname,
         u.firstname
FROM article a 
JOIN category c ON a.category_id = c.id
JOIN user u on a.author_id = u.id
WHERE a.id = :id
SQL;
         
        $dbarticle = $this->db->fetchAssoc(
                $query,
                [
                    ':id' => $id
                ]
            );
            
            if(!empty($dbarticle)){
                return $this->buildEntity($dbarticle);
            }
    }
    
    public function save(Article $article){
        $data =[
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'header' => $article->getHeader(),
            'category_id' => $article->getCategoryId(),
            'author_id' => $article->getAuthorId()
        ];
        if($article->getId()){
            $this->db->update('article', $data, ['id'=>$article->getId()]);
        } else{
            $this->db->insert('article', $data);
            $article->setId($this->db->lastInsertId());
        }
    }
    
     public function delete(article $article){
        $this->db->delete('article', ['id' => $article->getid()]);
    }
    
        public function findByCategory(Category $category){
         
       $query = <<<SQL
               
SELECT a.*,
        c.name AS category_name,
         u.lastname,
         u.firstname
FROM article a 
JOIN category c ON a.category_id = c.id
JOIN user u on a.author_id = u.id
WHERE a.category_id = :category_id
ORDER BY a.id DESC
SQL;
         
        $dbArticles = $this->db->fetchAll(
                $query,
                [
                    ':category_id' => $category->getId()
                ]
            );
        $Articles = [];
        
        foreach ($dbArticles as $dbArticle){
            $Articles[] = $this->buildEntity($dbArticle);
        }
        
        return $Articles;
    }
    
}
