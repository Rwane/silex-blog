<?php


namespace Repository;


use Entity\Category;


class CategoryRepository extends RepositoryAbstract {
   
    public function findAll(){
        $dbCategories = $this->db->fetchAll('SELECT * FROM category ORDER BY id');
        $Categories = [];
        
        foreach ($dbCategories as $dbCategory){
            $Categories[] = $this->buildEntity($dbCategory);
        }
        
        return $Categories;
    }
    
    public function find($id){
        $dbCategory = $this->db->fetchAssoc(
                'SELECT * FROM category WHERE id = :id',
                [
                    ':id' => $id
                ]
            );
            
            if(!empty($dbCategory)){
                return $this->buildEntity($dbCategory);
            }
    }
    
    public function save(Category $category){
        $data =[
            'name' => $category->getName()
        ];
        if($category->getId()){
            $this->db->update('category', $data, ['id'=>$category->getId()]);
        } else{
            $this->db->insert('category', $data);
            $category->setId($this->db->lastInsertId());
        }
    }
    
    
    /*
     * @param category $category
     */
    public function delete(category $category){
        $this->db->delete('category', ['id' => $category->getid()]);
    }
    
    /*
     * @param array $data
     * @return Category
     */
    private function buildEntity(array $data){
        $category = new Category();
        
        $category
            ->setId($data['id'])
            ->setname($data['name'])
        ;
        
        return $category;
        
    }
}
