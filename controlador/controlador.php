<?php

require "../modelo/modelo.php";

class controlador{

    private $modelo;

    public function __construct(){

        $this->modelo = new modelo();
    }

    public function get(){

        if(isset($_GET['id'])){

            $this->modelo->getId();
            
        } else {

            $this->modelo->get();
        } 

    }

    public function post(){

        $this->modelo->post();
        
    }

    public function put(){

        $this->modelo->put();
        
    }

    public function delete(){

        $this->modelo->delete();
    }
}


 

    
     
    

?>