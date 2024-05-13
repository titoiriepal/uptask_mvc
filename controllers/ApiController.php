<?php 
     
namespace Controllers;

use Model\Proyecto;

class ApiController{

    public static function allProyects(){

        $propietarioId = $_POST['propietarioId'];
        $proyectos = Proyecto::belongsTo('propietarioId', $propietarioId);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($proyectos, JSON_PRETTY_PRINT);
    }
}