<?php

require "../modelo/modelo.php";

/**
 * Clase Controlador, mediante la cual se podrá acceder a las funciones de la clase Modelo
 */
class controlador{

    private $modelo;

    /**
     * Constructor de la clase Controlador, inicializa el objeto modelo
     */
    public function __construct(){

        $this->modelo = new modelo(); 
    }

    /**
     * función que permite obtener un registro o varios de la base de datos por la petición GET
     *
     * @return void
     */
    public function get(){

        //si se recibe un parámetro GET 'id'
        if(isset($_GET['id'])){

            $this->modelo->getId(); //se llama a la función get por la id
            
        } else { //si no, se accede a la función get global

            $this->modelo->get(); 
        } 

    }

    /**
     * función que permite insertar un registro en la base de datos por medio de POST
     *
     * @return void
     */
    public function post(){

        $this->modelo->post();
        
    }

    /**
     * función que permite actualizar un registro concreto a modo de petición PUT
     *
     * @return void
     */
    public function put(){

        $this->modelo->put();
        
    }

    /**
     * función que permite eliminar un registro mediante su id (petición DELETE)
     *
     * @return void
     */
    public function delete(){

        $this->modelo->delete();
    }
}

?>