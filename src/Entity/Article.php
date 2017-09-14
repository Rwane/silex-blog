<?php



namespace Entity;




class Article  {
   /*
    * @var int
    */
    private $id;
    
    /*
     * 
     * @var string
     */
    private $content;
    
    private $header;
    
    private $title;
    
    /*
     * @var category
     */
    private $category;
    
    /**
     *
     * @var User
     */
    private $author;
    
    public function getAuthor(){
        return $this->author;
    }
    
    
    
    public function setAuthor(User $author){
       $this->author = $author;
       return $this;
    }
    
    public function getAuthorId() {
        if (!is_null($this->author)){
            return $this->author->getId();
        }
    }
    
    public function getAuthorName() {
         if(!is_null($this->author)){
            return $this->author->getFullname();
         }                       
    }
    
  //fin des get set de author  
    
    
    
    
    
    
    public function getId() {
        return $this->id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        
         return $this;
    }

    public function setHeader($header) {
        $this->header = $header;
        
         return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        
         return $this;
    }

    public function setCategory(Category $category) {
        $this->category = $category;
        
         return $this;
    }
    
    
    /*afficher le nom de la category de l'article*/
    public function getCategoryName(){
        if(!is_null($this->category)){
            return $this->category->getName();
        }
        
        return '';
    }
  
    public function getCategoryId(){
        
        if(!is_null($this->category)){
            return $this->category->getId();
        }
        
        return '';
    }
    
    
    

}
