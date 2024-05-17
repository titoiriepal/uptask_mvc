<?php 
    include_once __DIR__ . '/header-dashboard.php'; 
?>
    <div class="contenedor-sm">
        <div class="contenedor-nueva-tarea">
            <button
                type="button"
                class="agregar-tarea"
                id="agregar-tarea"
            >&#43;Nueva tarea </button>
        </div>

        <div class="menu-tareas">
            
            <form class="selected">
                <div class="option">
                    
                    <input name="option-dashboard" type="radio" value="activas" id="option-activas" checked="checked">
                    <label for="option-activas">Pendientes</label>
                </div>
                <div class="option">
                    
                    <input name="option-dashboard" type="radio" value="completas" id="option-inactivas" >
                    <label for="option-inactivas">Completas</label>
                </div>
                <div class="option">
                    
                    <input name="option-dashboard" type="radio" value="todas" id="option-todas" >
                    <label for="option-todas">Todas</label>

                </div>

                <div class="option">
                    
                    <input name="option-dashboard" type="radio" value="borradas" id="option-borradas" >
                    <label for="option-borradas">Eliminadas</label>

                </div>
            </form>
        </div>

        <ul id="listado-tareas" class="listado-tareas">

        </ul>
    </div>

<?php 
    include_once __DIR__ . '/footer-dashboard.php'; 
?>