
<?php 
    include_once __DIR__ . '/header-dashboard.php'; 
?>
<input type="hidden" id="propietarioId" value="<?php echo $propietarioId?>">

<?php if(count($proyectos) !== 0 ){?>

        
        <form class="selected">
            <div class="option">
                
                <input name="option-dashboard" type="radio" value="activas" id="option-activas" checked="checked">
                <label for="option-activas">Activas</label>
            </div>
            <div class="option">
                
                <input name="option-dashboard" type="radio" value="inactivas" id="option-inactivas" >
                <label for="option-inactivas">Acabadas o Borradas</label>
            </div>
            <div class="option">
                
                <input name="option-dashboard" type="radio" value="todas" id="option-todas" >
                <label for="option-todas">Todas</label>

            </div>
        </form>

        <ul class="listado-proyectos">

        </ul>



<?php  }else{ ?>
        <p class="no-proyectos">Aún no tienes ningún proyecto. Por favor <a href="/crear-proyecto">Crea uno</a></p>


<?php }

    include_once __DIR__ . '/footer-dashboard.php'; 
?>

