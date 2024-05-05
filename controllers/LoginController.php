<?php 
    
    
namespace Controllers;

use MVC\Router;

class LoginController{

    public static function login(Router $router){

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }
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

        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta',
            'estilo' => 'crear'
        ]);
    }

    public static function olvide(Router $router){
        

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }

        $router->render('auth/olvide',[
            'titulo' => 'Olvidaste tu password',
            'estilo' => 'olvide'
        ]);
    }

    public static function reestablecer(){
        echo "Desde reestablecer password";

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }
    }

    public static function mensaje(){
        echo "Desde Mensaje";

    }

    public static function confirmar(){
        echo "Desde Confirmar";

    }
}