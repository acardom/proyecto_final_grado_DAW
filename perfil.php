<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURSOSUR</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="img/fabicon.png">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

    <div class="container_card">
        <div class="card_perfil">
            <div class="content_card">
                <span></span>

                <div class="img">

                    <?php
                        if ($consulta->rowCount() > 0) {
                            echo ("<img src='img/profesor.png' alt=''>");
                        }else{
                            echo ("<img src='img/colegial.png' alt=''>");
                        }
                    ?>

                </div>

                    <?php

                        if(isset($_POST['borrar_perfil'])) {

                            try {
                                
                                $stmt = $conexion->prepare('DELETE FROM usuarios WHERE usuario = :usuario');
                                $stmt->bindParam(':usuario', $usuario);
                                $stmt->execute();

                                header("Location: logout.php");
                                exit();

                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }

   
                        }

                        if(isset($_POST['modificar_guardar'])) {

                            $Nombre_nuevo = $_POST['nuevoNombre'];
                            $Correo_nuevo = $_POST['nuevoCorreo'];
                            $contraseña_nueva = $_POST['nuevaContrasena'];
                            
                            

                                if ($Nombre_nuevo == ""){
                                    
                                }else{
                                    $consulta = $conexion->prepare("UPDATE usuarios SET nombre = ? WHERE usuario = ?;");
                                    $consulta->bindParam(1, $Nombre_nuevo);
                                    $consulta->bindParam(2, $usuario);
                                    $consulta->execute();
                                }

                                if ($Correo_nuevo == ""){
                                    
                                }elseif(!(preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/", $Correo_nuevo))){
                                    echo "<div class='errores'>";
                                    echo "<li>Dirección de correo no válida. </li>";
                                    echo "</div>";
                                }else{
                                    $consulta = $conexion->prepare("UPDATE usuarios SET correo = ? WHERE usuario = ?;");
                                    $consulta->bindParam(1, $Correo_nuevo);
                                    $consulta->bindParam(2, $usuario);
                                    $consulta->execute();
                                }

                                if ($contraseña_nueva == ""){
                                    
                                }elseif(!(preg_match("/^(?=.*\d)(?=.*[!@#$%^&*])/", $contraseña_nueva))){
                                    echo "<div class='errores'>";
                                    echo "<li>La contraseña debe contener numeros y simbolos </li>";
                                    echo "</div>";
                                }else{
                                    $consulta = $conexion->prepare("UPDATE usuarios SET contraseña = ? WHERE usuario = ?;");
                                    $consulta->bindParam(1, $contraseña_nueva);
                                    $consulta->bindParam(2, $usuario);
                                    $consulta->execute();
                                }

                            
   
                        }

                        

                        $consulta = $conexion->prepare('SELECT nombre, correo, usuario, contraseña FROM usuarios WHERE usuario = ?');
                        $consulta->bindParam(1, $usuario);
                        $consulta->execute();

                        while ($registro = $consulta->fetch(PDO::FETCH_ASSOC)) {

                            echo ("<form class='texto_perfil' method='post' action=''>");

                            echo ("<h4>Hola " . $registro['usuario'] . "</h4>");
                            echo ("<h6> Nombre: " . $registro['nombre'] . "</h6>");
                            echo ("<h6> Correo: " . $registro['correo'] . "</h6>");

                            echo ("<div class = 'contraseña_perfil'><h6 class='contraseña_puntos'> Contraseña: " . str_repeat('*', strlen($registro['contraseña'])) . " </h6> <div class='tachado' onclick='mostrarreal()'>&#128065;</div> </div>");
                           
                            echo ("<div class = 'contraseña_perfil'><h6 class='contraseña_real'> Contraseña: " . $registro['contraseña'] . "</h6> <div class='mostrar' onclick='mostraroculta()'>&#128065;</div> </div>");

                            echo ("<div class='camposModificacion' ;'>");

                            echo ("<label for='nuevoNombre'>Nuevo Nombre:</label><br>");
                            echo ("<input type='text' name='nuevoNombre' > <br>");

                            echo ("<label for='nuevoCorreo'>Nuevo Correo:</label><br>");
                            echo ("<input type='text' name='nuevoCorreo' > <br>");

                            echo ("<label for='nuevaContrasena'>Nueva Contraseña:</label><br>");
                            echo ("<input type='password' name='nuevaContrasena' > <br>");

                            echo ("</div>");

                            echo ("<button type='button' name='modificar_perfil' class='modificar_perfil' onclick='modificar_perfil_ocultar()'>Modificar Perfil</button>");
                            echo ("<button  name='modificar_guardar' class='modificar_guardar' onclick='modificar_perfil_mostrar()'>Actualizar datos</button>");

                            echo ("<button name='borrar_perfil' class='borrar_perfil'>Eliminar Perfil</button>");

                            echo ("</form>");
                        }
                        

                    ?>

            </div>
        </div>
    </div>

    <script src="js/botones_perfil.js"></script>    
    <script src="js/mostrar_curso.js"></script>
    <script src="js/desplegar.js"></script>

</body>
</html>