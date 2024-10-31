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
    ?>

    <div class="navbar">
        <a href="principal.php"><img src="img/fabicon_blanco.png" alt="">CURSOSUR</a>
        <div class="navbar-links" id="navbarLinks">
            <a href="perfil.php">Ver perfil</a>

            <?php
            
            $usuario = $_SESSION['usuario'];

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
        echo ("<div class='curso_mas' >");
        echo ("<button onclick='mostrarNuevoCurso()'>+ Añadir nuevo curso</button>");
        echo ("</div>");
    }  

    ?>

    <div class="nuevo_curso">
        <form action="" method="POST">

        <?php   

            $nombre_curso = "";
            $descripcion_curso = "";

            if(isset($_POST['crear_curso'])){

                $nombre_curso = $_POST['nombre_curso'];
                $descripcion_curso = $_POST['descripcion_curso'];
                $usuario = $_SESSION['usuario'];

                $inicio_valido = true;

                if ($nombre_curso == ""){
                    $inicio_valido = false;
                }
                
                if ($descripcion_curso == ""){
                    $inicio_valido = false;
                }

                if (strlen($descripcion_curso) < 20){
                    $inicio_valido = false;
                }

                if ($inicio_valido == true){
                    $consulta = $conexion->prepare("INSERT INTO cursos (nombre, descripcion, profesor) VALUES (?,?,?)");
                    $consulta->bindParam(1, $nombre_curso);
                    $consulta->bindParam(2, $descripcion_curso);
                    $consulta->bindParam(3, $usuario);

                    $consulta->execute();   

                    header("Location: principal.php");
                    exit();
                }
                  
            }
                    
            ?>

            <h3>Introduzca el nombre del curso:</h3>
            <input type="text" name="nombre_curso" required/>

            <h3>Introduzca un descripcion para el curso:</h3>
            <textarea type="text" name="descripcion_curso" required minlength="20"></textarea>

            </br>

            <button type="submit" name="crear_curso" value="crear_curso">Crear nuevo curso</button>
        </form>
    </div>

    <?php
            if(isset($_POST['borrar_cursos'])) {
                $id_a_borrar = $_POST['borrar_cursos'];
        
                $stmt = $conexion->prepare('DELETE FROM cursos WHERE id = :id');
                $stmt->bindParam(':id', $id_a_borrar);
                $stmt->execute();
            }

            if(isset($_POST['acceder_curso'])) {

                $id_curso = $_POST['acceder_curso'];
                
                session_start();

                $_SESSION['curso'] = $id_curso;
                header("Location: cursos.php");
                exit();
            }

            $resultado = $conexion->query('select nombre, profesor, descripcion, id from cursos;');

            echo "<h1 class = 'encabezados_centrados'>Cursos</h1>";

            while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) { 

                if ($consulta->rowCount() > 0) {

                    echo "<form method='post' action='' class='cursos_container2'>";
                    echo "<button class='primero' type='submit' name='acceder_curso' value='".$registro['id']."'>";
            
                    echo "<h1>".$registro['nombre']."</h1>";
                    echo ("<br/>");
                    echo "<h4>".$registro['descripcion']."</h4>";
                    echo ("<br/>");
                    echo "<h4>Profesor: ( ".$registro['profesor']." )</h4>";

                    echo "</button>";
                    echo "<div class='segundo'>";
            
                    echo "<th><button type='submit' name='borrar_cursos' value='".$registro['id']."'><img src='img/papelera.png' alt='papelera' ></button></th>";
                        
                    echo "</div>";
                    echo "</form>";

                } else {

                    echo "<form method='post' action='' class='cursos_container'>";
                    echo "<button type='submit' name='acceder_curso' value='".$registro['id']."'>";
            
                    echo "<h1>".$registro['nombre']."</h1>";
                    echo ("<br/>");
                    echo "<h4>".$registro['descripcion']."</h4>";
                    echo ("<br/>");
                    echo "<h4>Profesor:( ".$registro['profesor'].")</h4>";

                    echo "</button >";
                    echo "</form>";

                }

                echo ("<br/>");

            }


    ?>

    <script src="js/mostrar_curso.js"></script>
    <script src="js/desplegar.js"></script>

</body>
</html>