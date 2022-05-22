<?php

class modelo {

    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $db = "bdblogapi";


    public function __construct(){

        $this->conectar();

    }

    public function conectar(){


        try{

            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $ex) {

            return $ex->getMessage();

        }
            
    }

   public function get(){

        try{

            $sql = $this->conexion->prepare("SELECT * FROM entradas;");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit();

        } catch(PDOException $ex){

            return $ex->getMessage();
        }

   }

   public function getId(){

        try{

            $sql = $this->conexion->prepare("SELECT * FROM entradas where id=:id;");
            $sql->bindValue(':id', $_GET['id']);
            $sql->execute();
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
            exit();

        } catch(PDOException $ex){

            return $ex->getMessage();
        }

   }

   public function post(){

        try{

            $entrada = $_POST;
            $sql = "INSERT INTO entradas VALUES (NULL, :usuario, :categoria, :titulo, :imagen, :descripcion, :fecha);";
            $query = $this->conexion->prepare($sql);
            bindAllValues($query, $entrada);
            $query->execute();
            $entradaId = $this->conexion->lastInsertId();
    
            if($entradaId){
    
                $entrada['id'] = $entradaId;
                header("HTTP/1.1 200 OK");
                echo json_encode($entrada);
                exit();
            }


        } catch(PDOException $ex){

            return $ex->getMessage();

        }

   }

}

/**
 * función para obtener los parámetros a la hora de realizar una petición PUT
 *
 * @param [type] $entrada
 * @return void
 */
function getParams($entrada){

    $filtroParam = [];

    foreach($entrada as $param => $valor){

        $filtroParam[] = "$param=:$param";
    }

    return implode(", ", $filtroParam);
}

/**
 * función para asociar parámetros a un sql en caso de ser necesario (PUT, GET POR UNA ID O DELETE)
 *
 * @param [type] $query
 * @param [type] $param
 * @return void
 */
function bindAllValues($query, $param){

    foreach($param as $p => $valor){

        $query->bindValue(':'.$p, $valor);
    }

    return $query;
}

?>