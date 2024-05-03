<?php 
    
    
namespace Controllers;

class LoginController{

    public static function login(){
        echo "Desde Login";

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }
    }

    public static function logout(){
        echo "Desde Logout";

    }

    public static function crear(){
        echo "Desde Crear Usuario";

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }
    }

    public static function olvide(){
        echo "Desde recuperar password";

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
        }
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