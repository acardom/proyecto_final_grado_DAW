<?php
    if(isset($_POST['Registrarse'])){
        if(empty($nombre)){
            echo"<p class='error'>* Agrega tu nombre </p>"
        }
    }
?>