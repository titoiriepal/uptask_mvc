<?php 
        include_once __DIR__ . '/../templates/authHeader.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Reestablece tu password</p>
        

        <form action="/reestablecer" class="formulario" methdo="POST">

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu password"
                    name="password"
                />
            </div>

            <div class="campo">
                <label for="password2">Repite el Password</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Confirma tu Password"
                    name="password2"
                />
            </div>

            <input type="submit" class="boton" value="Guardar Password">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!--Contenedor-sm-->
</div>