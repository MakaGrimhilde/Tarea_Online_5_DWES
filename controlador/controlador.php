<?php

require "../modelo/modelo.php";


    $modelo = new modelo();

    
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){

        if(isset($_GET['id'])){

            $modelo->getId();
            
        } else {

            $modelo->get();
        }

    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $modelo->post();
    }
    

?>