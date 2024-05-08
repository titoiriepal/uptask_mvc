<?php 
     
namespace Controllers;

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

        $router->render('dashboard/crear',[
            'titulo' => 'Nuevo Proyecto',
            'class' => 'crear'
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