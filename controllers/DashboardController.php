<?php 
     
namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

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
            'propietarioId' => $_SESSION['id'],
            'script' => '<script src="/build/js/dashboard.js"></script>'
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
            'titulo' => $proyecto->proyecto,
            'script' => '<script src="/build/js/tareas.js"></script><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>'
        ]);

    }
    public static function perfil(Router $router) {
        session_start();

        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $nombre = $usuario->nombre;
            $mail = $usuario->email;
            $usuario->sincronizar($_POST);
    
            if($usuario->nombre === $nombre && $usuario->email === $mail){
                Usuario::setAlerta('error', 'No se realizó ningún cambio en el usuario');
                $alertas = Usuario::getAlertas();

            }else{
                $alertas = $usuario->validarPerfil();
            }
            

            if(empty($alertas)){

                $existeMail = Usuario::where('email', $usuario->email);
                if($existeMail && $existeMail->id !== $usuario->id){
                    Usuario::setAlerta('error', 'El Mail introducido ya corresponde a otro usuario');
                }else{
                    $resultado = $usuario->guardar();
                    if($resultado){
                        
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        
                        Usuario::setAlerta('exito','Cambios guardados correctamente');
                    }else{
                        Usuario::setAlerta('error','Error al guardar los cambios');
                    }
                }
                
            }
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil',[
            'titulo' => 'Tu perfil',
            'class' => 'perfil',
            'alertas' => $alertas,
            'usuario' => $usuario

        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();

        isAuth();
        $alertas = [];

        

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            
            $usuarioPwd = new Usuario($_POST);
            $alertas = $usuarioPwd->validarPassword();

            if(empty($alertas)){
                $passwordOld = $_POST['password_old'];
                $usuario = Usuario::find($_SESSION['id']);
                if(!password_verify($passwordOld, $usuario->password)){
                    Usuario::setAlerta('error','El Password actual introducido no es correcto');
                }else{
                    $usuario->password = $usuarioPwd->password;
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito','Password cambiado con éxito');
                    }else{
                        Usuario::setAlerta('error','Sucedió un error y no se pudo cambiar el password');
                    }
                }
            }
            
        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar_password',[
            'titulo' => 'Cambia tu password',
            'class' => 'perfil',
            'alertas' => $alertas
            

        ]);
    }
}