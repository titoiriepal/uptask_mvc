<?php 
     
namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController{
    public static function index(Router $router) {
        session_start();

        isAuth();

        $router->render('dashboard/index',[
            'titulo' => 'Tus Proyectos',
            'class' => 'proyectos'
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
                    header("Location: /dashboard");
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

    public static function perfil(Router $router) {
        session_start();

        isAuth();
        

        $router->render('dashboard/perfil',[
            'titulo' => 'Tu perfil',
            'class' => 'perfil'
        ]);
    }
}