<?php
require_once 'conexion/crud.php';
require_once 'respuestas.class.php';


class datos extends ConexionCrud{
    private $table= "on_interesados";
    private $table2="on_interesados_token" ;

    private $id_user="";
    private $usuario_tema="";
    private $token="";

    public function oninteresados($id,$token){
        $query = "SELECT * FROM " . $this->table . " tab1 INNER JOIN " . $this->table2 . " tab2 on tab1.id_estudiante=tab2.id_estudiante WHERE tab1.id_estudiante= '$id' and tab2.on_interesados_token='$token'";
        return parent::listar($query);
    }

    public function actualizarTema($json){// PUT toma los datos del formulario para editar un usuario
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos["token"])){
            return $_respuestas->error_401();
        }
        else{
            $this->token=$datos["token"];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['usuario_tema'])){
                    return $_respuestas->error_400();
                }else{
                    if(isset($datos["id"])) { $this->id_user = $datos['id']; }
                    if(isset($datos["usuario_tema"])) { $this->usuario_tema = $datos['usuario_tema']; }

                    $resp = $this->editarTema();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_user" => $this->id_user
                        );
                        return $respuesta;
                    }else{
                        return $resp;
                    }
                }
            }else{
                return $_respuestas->error_401("El token que envio es invalido o caducado");
            }
        }
    }

    private function editarTema(){// modelo para editar los datos del usuario
        date_default_timezone_set("America/Bogota");		
        $fecha = date('Y-m-d');
        $hora = date('h:i:s');
        $estado=0;

        $query= "UPDATE ".$this->table." SET 
        `usuario_tema`='" .$this->usuario_tema."'
        WHERE `id_user`='" . $this->id_user . "'"; 
  
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }
        else{
            return 0;
        }
    }

    private function buscarToken(){
        $query = "SELECT id_user,token,state_token FROM user_token WHERE token='" .$this->token. "' AND state_token='1'";
        $resp = parent::listar($query);
        if($resp){
            return $resp;
        }
        else{
            return 0;
        }
        
    }

}

?>