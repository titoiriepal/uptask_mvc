<?php 
    
    
namespace Controllers;

use MVC\Router;

class LoginController{

    public static function login(Router $router){

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }

        //Mostrar la vista
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesion',
            'estilo' => 'login'
        ]);
    }

    public static function logout(){
        echo "Desde Logout";

    }

    public static function crear(Router $router){

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }

        //Mostrar la vista
        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta',
            'estilo' => 'crear'
        ]);
    }

    public static function olvide(Router $router){
        

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }

        //Mostrar la vista
        $router->render('auth/olvide',[
            'titulo' => 'Olvidaste tu password',
            'estilo' => 'olvide'
        ]);
    }

    public static function reestablecer(Router $router){

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }

        //Mostrar la vista
        $router->render('auth/reestablecer',[
            'titulo' => 'Reestablecer Password',
            'estilo' => 'reestablecer'
        ]);
}

    public static function mensaje(Router $router){


        //Mostrar la vista
        $router->render('auth/mensaje',[
            'titulo' => 'Cuenta creada',
            'estilo' => 'mensaje'
        ]);
    }

    public static function confirmar(Router $router){
        

        //Mostrar la vista
        $router->render('auth/confirmar',[
            'titulo' => 'Cuenta activada',
            'estilo' => 'confirmar'
        ]);
    }
}