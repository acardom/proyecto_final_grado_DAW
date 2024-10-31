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
    <main>

        <div class="container_login">

            <div class="container_back">

                <div class="container_back_login">
                    <h3>Ya tienes una cuenta</h3>
                    <p>Inicia sesion para entrar en la pagina</p>
                    <button id="btn_login">Iniciar sesión</button>
                </div>

                <div class="container_back_register">
                    <h3>¿Aun no tienes cuenta?</h3>
                    <p>Registrate para que puedas iniciar sesión</p>
                    <button id="btn_registro">Registrarse</button>
                </div>

            </div>

            <div class="container_login_register">

                <form action="index.php" class="formulario_login" method="POST">

                <h2>Iniciar sesión</h2>

                <?php   

                session_start();

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

                    if(isset($_POST['Login'])){

                        

                        $Usuario = $_POST['Usuario_inicio'];
                        $Contraseña = $_POST['contraseña_inicio'];

                        $inicio_valido = true;

                        echo "<div class='errores'>";
                        

                        if ($Usuario == ""){
                            echo "<li>Campo usuario vacío. </li>";
                            $inicio_valido = false;
                        }
                        
                        if ($Contraseña == ""){
                            echo "<li>Campo contraseña vacío. </li>";
                            $inicio_valido = false;
                        }

                        if ($inicio_valido == true){

                            $comprobacion_inicio = true;

                            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :Usuario and Contraseña = :Contra");
                            $consulta->bindParam(':Usuario', $Usuario);
                            $consulta->bindParam(':Contra', $Contraseña);
                            $consulta->execute();

                            if ($consulta->rowCount() > 0) {
                                
                            }else{
                                $comprobacion_inicio = false;
                                echo"<li>Usuario o contraseña incorrecto. </li>";
                            }

                            if ($comprobacion_inicio == true){

                                session_start();

                                $_SESSION['usuario'] = $Usuario;
                                header("Location: principal.php");
                                exit();

                            }

                        }

                        echo "</div>";

                        
                    }

                    session_destroy();
                        
                ?>

                    <input type="text" placeholder="Usuario" name="Usuario_inicio">

                    <div class="password-container">
                        <input type="password" placeholder="Contraseña" name="contraseña_inicio" id="password" class="password-input">
                            <div class="show-password" onclick="togglePasswordVisibility()">
                                &#128065;
                            </div>
                    </div>

                    <script src="js/ver_contraseña.js"> </script>

                    <button type="submit" name="Login">Entrar</button>

                </form>


                <form action="" class="formulario_register" method="POST">

                    <h2>Registrarse</h2>

                    <?php

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

                    if(isset($_POST['Registrarse'])){

                        $Nombre = $_POST['Nombre_completo'];
                        $Correo = $_POST['Correo'];
                        $Usuario_crear = $_POST['Usuario'];
                        $contraseña_crear = $_POST['Contraseña'];
                        $contraseña_rep = $_POST['rep_cont'];
                        $rol = strval($_POST['Rol']);

                        if ($rol == "false"){
                            $profesor = false;
                        }else{
                            $profesor = true;
                        }


                        $registro_valido = true;

                        echo "<div class='errores'>";

                        if ($Nombre == ""){
                            echo "<li>Campo nombre vacío. </li>";
                            $registro_valido = false;
                        }
                        
                        if ($Correo == ""){
                            echo "<li>Campo correo vacío. </li>";
                            $registro_valido = false;
                        }

                        if (!(preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/", $Correo))) {
                            echo "<li>Dirección de correo no válida. </li>";
                            $registro_valido = false;
                        }

                        if (strlen($Usuario_crear) < 2){
                            echo "<li>Campo usuario demasiado corto. </li>";
                            $registro_valido = false;
                        }

                        if ($contraseña_crear == ""){
                            echo "<li>Campo contraseña vacío. </li>";
                            $registro_valido = false;
                        }

                        if (!(preg_match("/^(?=.*\d)(?=.*[!@#$%^&*])/", $contraseña_crear))) {
                            echo "<li>La contraseña debe contener numeros y simbolos </li>";
                            $registro_valido = false;
                        }elseif ($contraseña_rep == ""){
                            echo "<li>Campo repetir contraseña vacío. </li>";
                            $registro_valido = false;
                        }elseif($contraseña_rep != $contraseña_crear){
                            echo "<li>Campo repetir contraseñadebe coincidir con la contraseña. </li>";
                            $registro_valido = false;
                        }

                        if (!(isset($_POST['Check']) && $_POST['Check'] == 'on')) {
                            echo "<li>Debe aceptar los términos y servicios. </li>";
                            $registro_valido = false;
                        }

                        

                        if ($registro_valido == true){

                            $comprobacion = true;

                            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :Usuario_crear");
                            $consulta->bindParam(':Usuario_crear', $Usuario_crear);
                            $consulta->execute();

                            if ($consulta->rowCount() > 0) {
                                echo"<li>El usuario ya fue usado. </li>";
                                $comprobacion = false;
                            }

                            $consulta2 = $conexion->prepare("SELECT * FROM usuarios WHERE correo = :Correo");
                            $consulta2->bindParam(':Correo', $Correo);
                            $consulta2->execute();

                            if ($consulta2->rowCount() > 0) {
                                echo"<li>El correo ya fue usado. </li>";
                                $comprobacion = false;
                            }

                            

                            if ($comprobacion == true){

                                $consulta = $conexion->prepare("INSERT INTO usuarios (nombre, correo, usuario, contraseña, profesor) VALUES (?,?,?,?,?)");
                                $consulta->bindParam(1, $Nombre);
                                $consulta->bindParam(2, $Correo);
                                $consulta->bindParam(3, $Usuario_crear);
                                $consulta->bindParam(4, $contraseña_crear);
                                $consulta->bindParam(5, $profesor);
                                $consulta->execute();

                            }

                        }
                        echo "</div>";

                    }
                        
                ?>

                    <input type="text" placeholder="Nombre Completo" min="3" name="Nombre_completo">
                    <input type="text" placeholder="Correo electronico" name="Correo">
                    <input type="text" placeholder="Usuario" name="Usuario">
                    

                    <div class="password-container">
                        <input type="password" placeholder="Contraseña" name="Contraseña" id="password" class="password-input">
                        <input type="password" placeholder="Repetir contraseña" name="rep_cont" id="password" class="password-input">
                              
                    </div>


                    <script src="js/ver_contraseña.js"> </script>


                    <div class="Tipo_usuario">

                        <input type="radio" name="Rol" id="Alumno" value="false"  checked/>
                        <label for="Alumno">Alumno</label>
                        <input type="radio" name="Rol" id="Profesor" value="true"/>
                        <label for="Profesor">Profesor</label>

                    </div>

                    <div class="terminos">
                        
                        <input type="checkbox" id="check" name="Check">
                        <label for="check">Acepto los terminos y servicios.<a href="terminos y sevicios.html">Saber mas</a></label>
                        
 
                    </div>
                    
                    <button type="submit" name="Registrarse" value="Registrarse">Registrarse</button>

                </form>

            </div>

        </div>

    </main>

    <script src="js/script.js"></script>

</body>
</html>