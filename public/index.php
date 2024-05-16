<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\ApiController;
use Controllers\DashboardController;
use Controllers\LoginController;
use MVC\Router;
$router = new Router();

/**************Usuarios y sesiones ***************/

//LOGIN
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

//CREAR CUENTA
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

//RECUPERAR CUENTA
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

//REESTABLECER PASSWORD
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

//CONFIRMACIÓN DE CUENTA
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);

/******************Zona privada / Control de proyectos ************************/

$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear-proyecto', [DashboardController::class, 'crear']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/perfil', [DashboardController::class, 'perfil']);


/*********************ZONA API *************************************************/

$router->post('/api/proyectos', [ApiController::class, 'allProyects']);

/**************API PARA TAREAS ******************/

$router->get('/api/tareas', [ApiController::class, 'indexTarea']);
$router->post('/api/tarea', [ApiController::class, 'crearTarea']);
$router->post('/api/tarea/actualizar', [ApiController::class, 'actualizarTarea']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();