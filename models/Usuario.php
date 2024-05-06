<?php 
     

namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['nombre', 'email', 'password', 'token', 'confirmado', 'activo'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $token;
    public $confirmado;
    public $activo;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = strtolower($args['email'] ?? '');
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->activo = $args['activo'] ?? 1;
    }

    public function validarNuevoUsuario(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'Añade un nombre para el usuario.';
        }else if( strlen($this->nombre) > 60){
            self::$alertas['error'][] = 'El nombre no debe superar los 60 caracteres';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'Debes introducir un email';
        }else if( strlen($this->email) > 60){
            self::$alertas['error'][] = 'El email no puede superar los 60 caracteres';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'Introduce un password';
        }else if(strlen($this->password) < 6){
            self::$alertas['error'][]= "La contraseña debe tener al menos 6 carácteres";
        }else if ($this->password !== $this->password2){
            self::$alertas['error'][] = 'Los passwords no coinciden';
        }


        return self::$alertas;
    }

    //Hashea el password
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //Generamos el token
    public function crearToken() {
        $this->token = md5(uniqid());
    }
}