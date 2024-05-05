    <?php 
        include_once __DIR__ . '/../templates/authHeader.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>
        

        <form action="/" class="formulario" methdo="POST">

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu email"
                    name="mail"
                />
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu password"
                    name="password"
                />
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!--Contenedor-sm-->
</div>