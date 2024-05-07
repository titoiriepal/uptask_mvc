<?php 
        include_once __DIR__ . '/../templates/authHeader.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso a UpTask</p>
        
        <?php 
            include_once __DIR__ . '/../templates/alertas.php';
        ?>

        <form action="/olvide" class="formulario" method="POST">

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Introduce Tu email"
                    name="email"
                />
            </div>


            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>

        <div class="acciones">
            <a href="/">Iniciar sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
            
        </div>
    </div><!--Contenedor-sm-->
</div>