<?php 
    
    
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){

        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail;

            if(empty($alertas)){
                //Verificamos que el usuario exista:
                $usuario = Usuario::where('email', $auth->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }else{
                    //Comprobamos el password
                    if(!password_verify($auth->password, $usuario->password)){
                        Usuario::setAlerta('error', 'Contraseña incorrecta');
                    }else{
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('Location: /proyectos');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        //Mostrar la vista
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesion',
            'estilo' => 'login',
            'alertas' => $alertas,
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');

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
        
        $alertas = [];


        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
            $usuario = New Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);
                if(!$usuario || $usuario->activo === '0' || $usuario->confirmado === '0'){
                    Usuario::setAlerta('error', 'No existe ningún usuario activo confirmado con ese mail');
                    
                }else{
                    //Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //Actualizar el usuario
                    $usuario->guardar();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstruccionesPassword();
                    //Imprimir el mensaje
                    $usuario::setAlerta('exito', 'Te hemos enviado un email con las instrucciones para cambiar tu password. Por favor revisa tu correo');

                }
            $alertas = Usuario::getAlertas();
            }
        }

        //Mostrar la vista
        $router->render('auth/olvide',[
            'titulo' => 'Olvidaste tu password',
            'estilo' => 'olvide',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router){

        $alertas = [];
        $mostrar = true;
        $token   = s($_GET['token']);
        if (!$token){
            header('Location: /');
        }
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)){
            Usuario::setAlerta('error', 'El token proporcionado no es valido'); 
            $mostrar = false;
        }


        if($_SERVER["REQUEST_METHOD"] === "POST"){
            if($usuario){
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarPassword();

                if(empty($alertas)){
                    //Borrar el Password 2
                    unset($usuario->password2);
                    //Borrar el token
                    $usuario->token    = null;
                    //Hashear el password
                    $usuario->password = password_hash($usuario->password , PASSWORD_BCRYPT);
                    //Guardar los cambios en BD
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Password Reestablecido con éxito');
                    }else{
                        Usuario::setAlerta('error','Error al guardar la información, intentalo más tarde');
                    }
                    $mostrar = false;

    
                }
                
            }
            
        }

        $alertas = Usuario::getAlertas();
        //Mostrar la vista
        $router->render('auth/reestablecer',[
            'titulo' => 'Reestablecer Password',
            'estilo' => 'reestablecer',
            'alertas' => $alertas,
            'mostrar' => $mostrar
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