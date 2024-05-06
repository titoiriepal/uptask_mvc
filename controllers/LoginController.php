<?php 
    
    
namespace Controllers;

use Classes\Email;
use Model\Usuario;
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

        $usuario = new Usuario();
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevoUsuario();     

            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);
                
                if($existeUsuario){//Revisamos si existe el usuario en la base de datos
                    Usuario::setAlerta('error', 'El correo ' . $usuario->email . ' ya está registrado en la base de datos');
                    $alertas = Usuario::getAlertas();
                }else{ //Creamos un nuevo usuario
                    //Hashear el Password
                    $usuario->hashPassword();
                    //Borrar el password2
                    unset($usuario->password2); 
                    //Crear un token único
                    $usuario->crearToken();

                    $resultado = $usuario->guardar();
                    
                    if($resultado){
                                            //Enviar el email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion();
                        header('Location: /mensaje');
                    }

                    
                }
            }


        }
 
        //Mostrar la vista
        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta',
            'estilo' => 'crear',
            'usuario' => $usuario,
            'alertas' => $alertas
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

        $alertas = [];

        $token   = s($_GET['token']) ??  '';//leemos el token de la url
        if(!$token){ // Si no hay token lo redireccionamos a la página principal
            header('Location: /');
        }
        $usuario = Usuario::where('token', $token);//Buscamos al usuario por su token
        if (empty($usuario)){ //Si no hay ningún usuario con ese token mostramos un mensaje de alerta
            Usuario::setAlerta('error', 'El token proporcionado no es valido'); 
        }else{ //Si hay un usuario con ese token...
            $usuario->confirmado = 1; //Confirmamos al usuario
            $usuario->token = null; //Borramos el token
            $resultado = $usuario->guardar(); //Guardamos el usuario
            if ($resultado){ //Usuario guardado correctamente
                Usuario::setAlerta('exito', 'Usuario confirmado correctamente'); 
            }else{ //Fallo al guardar al usuario
                Usuario::setAlerta('error', 'Error en el proceso de confirmación'); 
            }
        }
        

        $alertas = Usuario::getAlertas();//Conseguimos las alertas y las pasamos a la vista.

        //Mostrar la vista
        $router->render('auth/confirmar',[
            'titulo' => 'Cuenta activada',
            'estilo' => 'confirmar',
            'alertas' => $alertas
        ]);
    }
}