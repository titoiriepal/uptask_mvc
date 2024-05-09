<?php 
     
namespace Model;

class Proyecto extends ActiveRecord{
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id','proyecto','url', 'propietarioId', 'activo'];

    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;
    public $activo;

    public function __construct($args = []){
        
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
        $this->activo = $args['activo'] ?? 1;
        
    }

    public function validar(){
        if(!$this->proyecto){
            self::$alertas['error'][] = 'Añade un nombre para el proyecto.';
        }else if( strlen($this->proyecto) > 60){
            self::$alertas['error'][] = 'El nombre del proyecto no debe superar los 60 caracteres';
        }

        return self::$alertas;
    }
}