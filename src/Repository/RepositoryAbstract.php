<?php
namespace Repository;

use Doctrine\DBAL\Connection;




abstract class RepositoryAbstract {
    /*
     * @var \Doctrine\DBAL\Connection
     */
   protected $db;
   
   
   
   /*
    * @param Connection $db
    */
   public function __construct(Connection $db) {
       $this->db = $db;
   }

}
