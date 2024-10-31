<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURSOSUR</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="img/fabicon.png">
</head>
<body>

<?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
        exit();
    }

    if (!isset($_SESSION['curso'])) {
        header("Location: principal.php");
        exit();
    }
?>

<div class="navbar">
        <a href="principal.php"><img src="img/fabicon_blanco.png" alt="">CURSOSUR</a>
        <div class="navbar-links" id="navbarLinks">
            <a href="perfil.php">Ver perfil</a>

            <?php
            
            $usuario = $_SESSION['usuario'];
            $id_curso = $_SESSION['curso'];

            try{
                $mysql ="mysql:host=localhost;dbname=proyecto_fct;charset=UTF8";
                $user = "root";
                $password = "";
                $opciones = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
                $conexion = new PDO($mysql, $user, $password);
            }catch (PDOException $e){
                echo "<p>" .$e->getMessage()."</p>";
                exit();
            }

            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :Usuario and profesor = true");
            $consulta->bindParam(':Usuario', $usuario);
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                echo ("<a href='listado_alumnos.php'>Alumnos</a>");
            }

            ?>

            <a href="logout.php">Cerrar sesión</a>
        </div>
        <button onclick="toggleNavbar()">&#9776;</button>
    </div>

    <?php

    if ($consulta->rowCount() > 0) {
        echo ("<div class='archivo_mas' >");
        echo ("<button onclick='mostrarNuevoarchivo()'>+ Añadir nuevo archivo</button>");
        echo ("</div>");
    }  

    ?>

    <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['crear_archivo']) && !empty($_FILES['archivo']['name'])) {
        $archivo_nombre = $_FILES['archivo']['name'];
        $archivo_tipo = $_FILES['archivo']['type'];
        $archivo_datos = file_get_contents($_FILES['archivo']['tmp_name']);

        $insertar_archivo = $conexion->prepare("INSERT INTO archivos (nombre_archivo, tipo_archivo, datos_archivo, id_curso) VALUES (?, ?, ?, ?)");
        $insertar_archivo->bindParam(1, $archivo_nombre);
        $insertar_archivo->bindParam(2, $archivo_tipo);
        $insertar_archivo->bindParam(3, $archivo_datos);
        $insertar_archivo->bindParam(4, $id_curso);
        $insertar_archivo->execute();

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();

        // Resto de tu código para la subida y almacenamiento del archivo
    } 
}
    ?>




    <div class="nuevo_archivo">
        <form action="" method="POST" enctype="multipart/form-data">

            </br>

            <input type="file" name="archivo" id="archivo" required>

            </br> 

            <button type="submit" name="crear_archivo" value="crear_archivo">Añadir nuevo archivo</button>
        </form>
    </div>

    <?php

    if ($consulta->rowCount() > 0) {
        echo ("<div class='curso_mas' >");
        echo ("<button onclick='mostrarNuevoCurso()'>+ Añadir nuevo examen</button>");
        echo ("</div>");
    }  

    if(isset($_POST['crear_examen'])){

        $nombre_examen = $_POST['nombre_examen'];
        $usuario = $_SESSION['usuario'];
        $id = $_SESSION['curso'];

        $inicio_valido = true;

        if ($nombre_examen == ""){
            $inicio_valido = false;
            echo "<div class='errores'>";
            echo "<li>El campo nombre no puede estar vacio</li>";
            echo "</div>";
        }

        if ($inicio_valido == true){
            $consulta = $conexion->prepare("INSERT INTO examenes (id_curso, nombre) VALUES (?,?)");
            $consulta->bindParam(1, $id);
            $consulta->bindParam(2, $nombre_examen);

            $consulta->execute();   

            header("Location: cursos.php");
            exit();
        }
          
    }

    ?>

    <div class="nuevo_curso">
        <form action="" method="POST">


            <h3>Introduzca el nombre del examen:</h3>
            <input type="text" name="nombre_examen" required/>

            </br>

            <button type="submit" name="crear_examen" value="crear_examen">Añadir nuevo examen</button>
        </form>
    </div>

    <?php

    if(isset($_POST['descargar_archivo'])) {
        $id = $_POST['descargar_archivo'];

        header("Location: descargar_archivo.php?id=$id");
    }

    if(isset($_POST['borrar_archivo'])) {

        $id = $_POST['borrar_archivo'];
        
        $stmt = $conexion->prepare('DELETE FROM archivos WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
    }

    $consulta_archivos = $conexion->prepare("SELECT id, nombre_archivo FROM archivos where id_curso = ".$id_curso."");
    $consulta_archivos->execute();

    if ($consulta_archivos->rowCount() > 0) {
        
        echo "<h1 class = 'encabezados_centrados'>Archivos</h1>";

        while ($fila = $consulta_archivos->fetch(PDO::FETCH_ASSOC)) {
            $id_archivo = $fila['id'];
            $nombre_archivo = $fila['nombre_archivo'];

            if ($consulta->rowCount() > 0) {

                echo "<form method='post' action='' class='cursos_container2'>";

                    echo "<div class='segundo'>";
                    echo "<th><button type='submit' name='descargar_archivo' value='".$id_archivo."'><img src='img/descargas.png' alt='papelera' ></button></th>";
                    echo "</div>";

                    echo "<div class='tercero'>";
                    echo "<h1>".$nombre_archivo."</h1>";
                    echo "</div>";

                    echo "<div class='segundo'>";
                    echo "<th><button type='submit' name='borrar_archivo' value='".$id_archivo."'><img src='img/papelera.png' alt='papelera' ></button></th>";   
                    echo "</div>";

                echo "</form>";


            }else{

                echo "<form method='post' action='' class='cursos_container2'>";

                    echo "<div class='segundo'>";
                    echo "<th><button type='submit' name='descargar_archivo' value='".$id_archivo."'><img src='img/descargas.png' alt='papelera' ></button></th>";
                    echo "</div>";

                    echo "<div class='primero'>";
                    echo "<h1>".$nombre_archivo."</h1>";
                    echo "</div>";

                echo "</form>";

            }    

        }

        echo "</ul>";
        echo "</div>";
        }


        if(isset($_POST['borrar_examen'])) {

            $id = $_POST['borrar_examen'];
            
            $stmt = $conexion->prepare('DELETE FROM examenes WHERE id_examen = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
        }

        if(isset($_POST['crear_examen'])) {

            $id = $_POST['crear_examen'];

            $enunciado = $_POST['enunciado'];

            $incorrecta_primera = $_POST['incorrecta_primera'];
            $incorrecta_segunda = $_POST['incorrecta_segunda'];
            $incorrecta_tercera = $_POST['incorrecta_tercera'];

            $correcta = $_POST['correcta'];
            
            $inicio_valido = true;

            if (($enunciado == "") and ($incorrecta_primera == "") and ($incorrecta_segunda == "") and ($incorrecta_tercera == "") and ($correcta == "")){
                $inicio_valido = false;
            }

            if ($inicio_valido == true){

                $consulta = $conexion->prepare("INSERT INTO preguntas (enunciado, erronea_1, erronea_2, erronea_3, correcta, id_examen) VALUES (?,?,?,?,?,?)");
                $consulta->bindParam(1, $enunciado);
                $consulta->bindParam(2, $incorrecta_primera);
                $consulta->bindParam(3, $incorrecta_segunda);
                $consulta->bindParam(4, $incorrecta_tercera);
                $consulta->bindParam(5, $correcta);
                $consulta->bindParam(6, $id);

                $consulta->execute();   

                header("Location: cursos.php");
                exit();

            }
          
            
        }

        if(isset($_POST['acceder_examen'])) {

            $id_examen = $_POST['acceder_examen'];
            
            session_start();

            $_SESSION['examen'] = $id_examen;
            header("Location: examen.php");
            exit();
        }


    $consulta_archivos = $conexion->prepare("SELECT nombre, id_examen FROM examenes where id_curso = ".$id_curso."");
    $consulta_archivos->execute();

    if ($consulta_archivos->rowCount() > 0) {
        
        echo "<h1 class = 'encabezados_centrados'>Examenes</h1>";

        while ($fila = $consulta_archivos->fetch(PDO::FETCH_ASSOC)) {
            $id_examen = $fila['id_examen'];
            $nombre_examen = $fila['nombre'];

            if ($consulta->rowCount() > 0) {

                echo "<form method='post' action='' class='cursos_container2'>";


                    echo "<button class='tercero_2' type='submit' name='acceder_examen' value='".$id_examen."'>";
                    echo "<h1>".$nombre_examen."</h1>";

                    $consulta = $conexion->prepare("SELECT nota FROM notas WHERE id_usuario = '".$_SESSION['usuario']."' AND id_examen = ".$id_examen."");
                    $consulta->execute();

                    if ($consulta->rowCount() > 0) {

                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        $nota = $resultado['nota'];
                        
                        echo "Mayor nota: ".$nota;
                        
                    } else {

                    }

                    echo "</button>";

                    echo "<div class='cuarto'>";
                    echo "<th><button type='button' name='agregar_examen' value='".$id_archivo."' class='mostrar-nuevo-curso' data-examen-id='".$id_examen."'><img src='img/mas.png' alt='papelera' ></button></th>";
                    echo "</div>";

                    echo "<div class='cuarto'>";
                    echo "<th><button type='submit' name='borrar_examen' value='".$id_examen."'><img src='img/papelera.png' alt='papelera' ></button></th>";   
                    echo "</div>";

                echo "</form>";

                echo "<div class='nuevo_curso' id='nuevo_curso_".$id_examen."' style='display: none;'>";
                    echo "<form action='' method='POST'>";

                        echo "<h3>Introduzca el enunciado de la pregunta:</h3>";
                        echo "<textarea type='text' name='enunciado' required minlength='10'></textarea>";
                        echo "<br/>";

                        echo "<h3>Introduzca las respuestas incorrectas:</h3>";
                        echo "<input type='text' name='incorrecta_primera' required/>";
                        echo "<input type='text' name='incorrecta_segunda' required/>";
                        echo "<input type='text' name='incorrecta_tercera' required/>";
                        echo "<br/>";

                        echo "<h3>Introduzca la respuesta correcta:</h3>";
                        echo "<input type='text' name='correcta' required/>";
                        echo "<br/>";

                        echo "<button type='submit' name='crear_examen' value='".$id_examen."'>Añadir nueva pregunta al examen</button>";
                    echo "</form>";
                echo "</div>";

            }else{



                echo "<form method='post' action='' class='cursos_container'>";
                echo "<button type='submit' name='acceder_examen' value='".$id_examen."'>";
            
                echo "<h1>".$nombre_examen."</h1>";
                
                $consulta = $conexion->prepare("SELECT nota FROM notas WHERE id_usuario = '".$_SESSION['usuario']."' AND id_examen = ".$id_examen."");
                    $consulta->execute();

                    if ($consulta->rowCount() > 0) {

                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        $nota = $resultado['nota'];
                        
                        echo "Mayor nota: ".$nota;
                        
                    } else {

                    }

                echo "</button >";
                echo "</form>";

            }    

        }

        echo "</ul>";
        echo "</div>";

        echo ("<br/>");
        }

    
    ?>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        var mostrarBotones = document.querySelectorAll('.mostrar-nuevo-curso');

        mostrarBotones.forEach(function (boton) {
            boton.addEventListener('click', function () {
                var examenId = this.getAttribute('data-examen-id');
                var divNuevoCurso = document.getElementById('nuevo_curso_' + examenId);

                if (divNuevoCurso.style.display === 'none' || divNuevoCurso.style.display === '') {
                    divNuevoCurso.style.display = 'block';
                } else {
                    divNuevoCurso.style.display = 'none';
                }
            });
        });
    });
</script>

<script>

    var notaMedia = <?php echo isset($_SESSION['nota_media']) ? $_SESSION['nota_media'] : 'null'; ?>;
    if (notaMedia !== null) {
        alert('Calificacion obtenida: ' + notaMedia);
    }


<?php unset($_SESSION['nota_media']); ?>
</script>

    <script src="js/mostrar_curso.js"></script>
    <script src="js/desplegar.js"></script>
    
</body>
</html> 