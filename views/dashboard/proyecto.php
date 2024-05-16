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

        <form class="selected">
            <div class="option">
                
                <input name="option-dashboard" type="radio" value="activas" id="option-activas" checked="checked">
                <label for="option-activas">Activas</label>
            </div>
            <div class="option">
                
                <input name="option-dashboard" type="radio" value="inactivas" id="option-inactivas" >
                <label for="option-inactivas">Eliminadas</label>
            </div>
            <div class="option">
                
                <input name="option-dashboard" type="radio" value="todas" id="option-todas" >
                <label for="option-todas">Todas</label>

            </div>
        </form>

        <ul id="listado-tareas" class="listado-tareas">

        </ul>
    </div>

<?php 
    include_once __DIR__ . '/footer-dashboard.php'; 
?>