<?php 
     
namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController{
    public static function index(Router $router) {
        session_start();

        isAuth();
        //Obtenemos todos los proyectos del usuario que inició sesion.
        $proyectos = Proyecto::belongsTo('propietarioID', $_SESSION['id']);


        $router->render('dashboard/index',[
            'titulo' => 'Tus Proyectos',
            'class' => 'proyectos',
            'proyectos' => $proyectos,
            'propietarioId' => $_SESSION['id']
        ]);
    }

    public static function crear(Router $router) {
        session_start();

        isAuth();
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validar();
            if(empty($alertas)){
                //Generar una url única
                
                $proyecto->url = md5(uniqid());

                //Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                $resultado = $proyecto->guardar();

                if($resultado){
                    header("Location: /proyecto?url=" .  $proyecto->url);
                    //header("Location: /proyecto?url={$proyecto->url}");

                }else{
                    Proyecto::setAlerta('error', 'Error al guardar el proyecto');
                }
            }
        }   

        $alertas = Proyecto::getAlertas();

        $router->render('dashboard/crear',[
            'titulo' => 'Nuevo Proyecto',
            'class' => 'crear',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){

        session_start();
        isAuth();

        $url = $_GET['url'];
        

        if (!$url){
            header("Location: /dashboard");
        }
        $proyecto = Proyecto::where('url', $url);
        
        
        if(!$proyecto || $proyecto->activo === '0'){
            header("Location: /dashboard");
        }

        //Revisar que la persona que visita el proyecto es quién lo creo
        if($proyecto->propietarioId !== $_SESSION['id']){
            header("Location: /dashboard");
        }






        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);

    }
    public static function perfil(Router $router) {
        session_start();

        isAuth();
        

        $router->render('dashboard/perfil',[
            'titulo' => 'Tu perfil',
            'class' => 'perfil'
        ]);
    }
}