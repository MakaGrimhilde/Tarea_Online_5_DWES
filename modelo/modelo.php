<?php

/**
 * Clase modelo. Se encarga de gestionar el acceso a la base de datos y a realizar operaciones sobre ella
 */
class modelo {

    //variables necesarias para realizar la conexión a la base de datos mediante PDO
    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $db = "bdblogapi";

    /**
     * constructor de la clase modelo. Ejecuta directamente el método conectar con la base de datos
     */
    public function __construct(){

        $this->conectar();

    }

    /**
     * función que conecta a la base de datos bdblogapi mediante PDO
     *
     * @return void
     */
    public function conectar(){


        try{

            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $ex) {

            return $ex->getMessage();

        }
            
    }

    /**
     * Función que obtiene todos los registros de la base de datos (PETICIÓN GET)
     *
     * @return void
     */
    public function get(){

        try{

            //conexión con la base de datos y preparación de la sentencia SELECT *
            $sql = $this->conexion->prepare("SELECT * FROM entradas;");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll()); //devuelve todos los registros en forma de string en el JSON
            exit();

        } catch(PDOException $ex){

            return $ex->getMessage();
        }

    }

    /**
     * Función que obtiene un registro mediante su campo id (PETICIÓN GET)
     *
     * @return void
     */
    public function getId(){

        try{

            //conexión con la base de datos y preparación de la sentencia SELECT
            $sql = $this->conexion->prepare("SELECT * FROM entradas where id=:id;");
            $sql->bindValue(':id', $_GET['id']); //se asocia el valor id del SQL con el id que se pasa por parámetro
            $sql->execute();
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetch(PDO::FETCH_ASSOC)); //devuelve lo que obtenga el SQL en forma de string en el JSON
            exit();

        } catch(PDOException $ex){

            return $ex->getMessage();
        }

    }

    /**
     * Función que permite insertar un registro a la base de datos (PETICIÓN POST)
     *
     * @return void
     */
    public function post(){

        try{

            $entrada = $_POST; //se almacena lo que se reciba mediante método POST
            
            //Sentencia INSERT
            $sql = "INSERT INTO entradas VALUES (NULL, :usuario, :categoria, :titulo, :imagen, :descripcion, :fecha);";
            $query = $this->conexion->prepare($sql); //conexión con la bd y preparación de la sentencia SQL 
            
            //En el bucle foreach se asocia todo lo que se reciba por POST con la sentencia SQL INSERT
            foreach($entrada as $e => $valor){

                $query->bindValue(':'.$e, $valor);
            }

            $query->execute();
            //mediante lastInsertId se obtiene el id del último registro insertado en la tabla
            $entradaId = $this->conexion->lastInsertId();
    
            if($entradaId){ //con el id del registro se genera el JSON con todos los valores insertados
    
                $entrada['id'] = $entradaId;
                header("HTTP/1.1 200 OK");
                echo json_encode($entrada);
                exit();
            }

        } catch(PDOException $ex){

            return $ex->getMessage();
        }

    }

    /**
     * Función que permite actualizar un registro de la tabla de la base de datos (PETICIÓN PUT)
     *
     * @return void
     */
    public function put(){

        try{

            $entrada = $_GET; //se almacena lo que se reciba mediante método POST
            $entradaId = $entrada['id']; //se obtiene la id del registro en cuestión mediante GET
            //se almacena en $campos todos los parámetros recibidos por $_GET con la función getParametros
            $campos = getParametros($entrada); 

            $sql = "UPDATE entradas SET $campos WHERE id='$entradaId';"; //sentencia SQL UPDATE
            $query = $this->conexion->prepare($sql); //conexión con la bd y preparación de la sentencia SQL 

            //En el bucle foreach se asocian todos los parámetros que se reciban mediante GET con la sentencia SQL UPDATE
            foreach($entrada as $e => $valor){

                $query->bindValue(':'.$e, $valor);
            }

            $query->execute();
            header("HTTP/1.1 200 OK");
            exit();

        } catch(PDOException $ex){

            return $ex->getMessage();
        }
        
    }

    /**
     * Función que elimina un registro de la tabla de la base de datos (PETICIÓN DELETE)
     *
     * @return void
     */
    public function delete(){

        try{

            $id = $_GET['id']; //se obtiene el id mediante método GET

            //se prepara la sentencia SQL para realizar un DELETE en la tabla
            $query = $this->conexion->prepare("DELETE FROM entradas where id=:id;");
            $query->bindValue(':id', $id); //se asocia la id recibida por GET con el id del SQL
            $query->execute();
            header("HTTP/1.1 200 OK");
            exit();

        } catch(PDOException $ex){

            return $ex->getMessage();
        }
    }

}

/**
 * función para obtener todos los parámetros a la hora de realizar una petición PUT
 *
 * @param string $entrada
 * @return void
 */
function getParametros($entrada){

    $filtroParam = [];

    foreach($entrada as $param => $valor){

        $filtroParam[] = "$param=:$param";
    }

    return implode(", ", $filtroParam);
}

?>