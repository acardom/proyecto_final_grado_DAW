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

    <script src="js/desplegar.js"></script>

    <div class="tabla_listado_alumnos">
        <table>
            
                <thead>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Usuario</th>
                </thead>

                <?php
                    try {
                        $mysql ="mysql:host=localhost;dbname=proyecto_fct;charset=UTF8";
                        $user = "root";
                        $password = "";
                        $opciones = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
                        $conexion = new PDO($mysql, $user, $password);
                    } catch (PDOException $e) {
                        echo "<p>" .$e->getMessage()."</p>";
                        exit();
                    }

                    echo "<form method='post' action=''>";

                    $resultado = $conexion->query('select nombre, correo, usuario from usuarios where profesor = 0;');
                    while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
        
                        echo "<tbody>";
                        echo "<th class='listado_datos'>".$registro['nombre']."</th>";
                        echo "<th class='listado_datos'>".$registro['correo']."</th>";
                        echo "<th class='listado_datos'>".$registro['usuario']."</th>";
                        
                        echo "<input type='hidden' name='idusuario' value='" . $registro['usuario'] . "'>";
                        echo "<th><button type='submit' class='estilo_boton borrar-usuario' name='borrarusuario_".$registro['usuario']."' ><img src='img/papelera.png' alt='papelera' ></button></th>";
                        
                        echo "</tbody>";
                    }

                    echo "</form>";

                    if (!empty($_POST)) {
                        foreach ($_POST as $key => $value) {
                            
                            if (strpos($key, 'borrarusuario_') !== false) {
                                $idusuario = str_replace('borrarusuario_', '', $key);
                                try {
                                    $consulta = $conexion->prepare("DELETE FROM usuarios WHERE usuario = :idusuario");
                                    $consulta->bindParam(':idusuario', $idusuario, PDO::PARAM_STR);
                                    $consulta->execute();
                                } catch (PDOException $e) {
                                    echo "Error al intentar borrar el registro: " . $e->getMessage();
                                }
                            }
                        }
                        
                        header("Location: $_SERVER[PHP_SELF]");
                    }
                    $conexion = null;   
                ?>
            
        </table>
    </div>
    
</body>
</html> 