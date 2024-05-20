<?php 
    include_once __DIR__ . '/header-dashboard.php'; 
?>


<div class="contenedor-sm">
    <?php 
        include_once __DIR__ . '/../templates/alertas.php'
    ?>

<a href="/perfil" class="enlace">Actualizar Perfil</a>

    <form class="formulario" method="POST">

        <div class="campo">
            <label for="password_old">Password actual:</label>
            <input 
                type="password" 
                name="password_old" 
                placeholder="Tu Password actual" 
            />
        </div>
        <div class="campo">
            <label for="password">Nuevo Password:</label>
            <input 
                type="password" 
                name="password" 
                placeholder="Tu nuevo Password" 
            />
        </div>
        <div class="campo">
            <label for="password2">Confirmar Nuevo Password:</label>
            <input 
                type="password" 
                name="password2" 
                placeholder="Repite tu nuevo Password" 
            />
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>


<?php 
    include_once __DIR__ . '/footer-dashboard.php'; 
?>