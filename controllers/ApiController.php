<?php 
     
namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class ApiController{

    public static function allProyects(){

        $propietarioId = $_POST['propietarioId'];
        $proyectos = Proyecto::belongsTo('propietarioId', $propietarioId);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($proyectos, JSON_PRETTY_PRINT);
    }

    public static function indexTarea(){
        
    }

    public static function crearTarea(){
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            session_start();

            $proyecto = Proyecto::where('url', $_POST['url']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarrea'
                ];
                header("Content-type: application/json; charset=utf-8");
                echo json_encode($respuesta, JSON_PRETTY_PRINT);
                return;
            }

            //Todo bien, instanciar y crear la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();
            if($resultado['resultado']) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $resultado['id'],
                    'mensaje' => 'Tarea agregada con éxito'
                ];
            }else{
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarrea'
                ];
            }

            header("Content-type: application/json; charset=utf-8");
            echo json_encode($respuesta, JSON_PRETTY_PRINT);
        }
    }

    public static function actualizarTarea(){
        if($_SERVER["REQUEST_METHOD"] === "POST"){
        
        }
    }

    public static function eliminarTarea(){
        if($_SERVER["REQUEST_METHOD"] === "POST"){
        
        }        
    }
}