
<?php 
    include_once __DIR__ . '/header-dashboard.php'; 

    if(count($proyectos) !== 0 ){?>

        <ul class="listado-proyectos">
            <?php 
                foreach($proyectos as $proyecto){
                if($proyecto->activo){
                ?>
                <li class="proyecto">
                    <a href="/proyecto?url=<?php echo $proyecto->url?>"><?php echo $proyecto->proyecto?></a>
                </li>
            
            <?php 
                }
            } 
            ?>
        </ul>



<?php  }else{ ?>
        <p class="no-proyectos">Aún no tienes ningún proyecto. Por favor <a href="/crear-proyecto">Crea uno</a></p>
    <?php }
    include_once __DIR__ . '/footer-dashboard.php'; 
?>