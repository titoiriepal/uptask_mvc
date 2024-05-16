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
        
        
        $proyectoUrl = $_GET['url'];
        if(!$proyectoUrl){
            header("Location: /dashboard");
        }
        $proyecto = Proyecto::where('url', $proyectoUrl);
        session_start();
        if(!$proyecto || $_SESSION['id'] !== $proyecto->propietarioId){
            $_SESSION = [];
            header("Location: /");
        }
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode(['tareas' => $tareas], JSON_PRETTY_PRINT);
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
                    'mensaje' => 'Tarea agregada con éxito',
                    'proyectoId' => $tarea->proyectoId
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
            //Validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['url']);
            session_start();
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarrea'
                ];
                header("Content-type: application/json; charset=utf-8");
                echo json_encode($respuesta, JSON_PRETTY_PRINT);
                return;
            }

            $tarea = new Tarea($_POST);
            $resultado = $tarea->guardar();

            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'tarea' => $tarea,
                    'mensaje' => 'Actualizado correctamente'
                ];
            }else{
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarrea'
                ];
            }
            header("Content-type: application/json; charset=utf-8");
            echo json_encode($respuesta, JSON_PRETTY_PRINT);
        }
    }

}