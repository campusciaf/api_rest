<?php

require_once 'clases/respuestas.class.php';
require_once 'clases/oninteresados.class.php';
header("Access-Control-Allow-Origin: *");// quita el bloqueo cros 
header("Access-Control-Allow-Methods: PUT, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, autorizacion, X-Requested-With, Content-Type, Accept, Access-Control-Request-MethodAccess-Control-Allow-Headers,Authorization");
header('content-type: application/json');


$_respuestas = new respuestas;
$_datos = new datos;

if($_SERVER["REQUEST_METHOD"] == "GET"){
  
    if(isset($_GET['id']) && isset($_GET['token'])){//metodo para obterner un dato por id
        
        $id_user=$_GET["id"];
        $token=$_GET["token"];

        $datos = $_datos->oninteresados($id_user,$token);
        header('Content-Type: application/json');
        echo json_encode($datos);
        http_response_code(200);
    }


}else if($_SERVER["REQUEST_METHOD"] == "POST"){
    

}else if($_SERVER["REQUEST_METHOD"] == "PUT"){
    $postBody= file_get_contents("php://input");// recibimos los datos enviados del formulario
    $datosArray = $_cuenta->actualizarTema($postBody);// enviamos esto al manejador
    header('Content-Type: application/json');// devolvemos una respuesta

        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
}

else if($_SERVER["REQUEST_METHOD"] == "DELETE"){

}

else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);

}




?>