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

session_start();

$usuario = $_SESSION['usuario'];
$id_curso = $_SESSION['curso'];
$id_examen = $_SESSION['examen'];


$consulta_preguntas = $conexion->prepare("SELECT * FROM preguntas where id_examen = :id");
$consulta_preguntas->bindParam(':id', $id_examen);
$consulta_preguntas->execute();
$preguntas = $consulta_preguntas->fetchAll(PDO::FETCH_ASSOC);

function mezclarOpciones($opciones) {
    shuffle($opciones);
    return $opciones;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURSOSUR</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="img/fabicon.png">
</head>
<body>

<form action="" method="post">

<div class="aviso">
    <div class='errores'>
        <p>Las preguntas mal contestadas restan una bien mientras sino la respondes no resta</p>
    </div>
</div>

<?php

if (isset($_POST['submit'])) {
    $puntuacion = 0;

    foreach ($preguntas as $pregunta) {
        $id_pregunta = $pregunta['id_pregunta'];

        if (isset($_POST['respuesta_'.$id_pregunta])) {
            $respuesta_usuario = $_POST['respuesta_'.$id_pregunta];

            if ($respuesta_usuario === $pregunta['correcta']) {
                $puntuacion++;
            } else {
                $puntuacion--;
            }
        }
    }

    $puntuacion = max(0, $puntuacion);

    $nota_media = round(($puntuacion/count($preguntas))*10,2);

    echo "<h1 class = 'encabezados_centrados' >Tu calificación es de: $nota_media $usuario $id_examen</h1>";


    $consulta = $conexion->prepare("SELECT nota, id_nota FROM notas WHERE id_usuario = '".$_SESSION['usuario']."' AND id_examen = ".$_SESSION['examen']."");
    $consulta->execute();

    
if ($consulta->rowCount() > 0) {
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    $nota = $resultado['nota'];
    $id_nota = $resultado['id_nota'];

    if ($nota < $nota_media) {
        // Verificar si la actualización es necesaria
        $consulta_update = $conexion->prepare("UPDATE notas SET nota = :nota_media WHERE id_nota = :id");
        $consulta_update->bindParam(':id', $id_nota);
        $consulta_update->bindParam(':nota_media', $nota_media);
        $consulta_update->execute();

        echo "<h1>Nota actualizada</h1>";
    } else {
        echo "<h1>No es necesario actualizar la nota</h1>";
    }
} else {

    
    // Si no hay registros, insertar uno nuevo
    $consulta_insert = $conexion->prepare("INSERT INTO notas (nota, id_examen, id_usuario) VALUES (?,?,?)");
    $consulta_insert->bindParam(1, $nota_media);
    $consulta_insert->bindParam(2, $id_examen);
    $consulta_insert->bindParam(3, $usuario);
    $consulta_insert->execute();

    echo "<h1>Nota insertada</h1>";
}

$_SESSION['nota_media'] = $nota_media;

header("Location: cursos.php");
exit();
}


foreach ($preguntas as $pregunta) {
    $enunciado = $pregunta['enunciado'];
    $correcta = $pregunta['correcta'];
    $erroneas = array($pregunta['erronea_1'], $pregunta['erronea_2'], $pregunta['erronea_3']);
    $opciones = mezclarOpciones(array_merge(array($correcta), $erroneas));

    echo "<div class = 'pregunta_examen'>";

    echo "<fieldset>";
    echo "<legend>$enunciado</legend>";

    foreach ($opciones as $opcion) {
        echo "</br>";
        echo "<label>";
        echo "<input type='radio' name='respuesta_".$pregunta['id_pregunta']."' value='".$opcion."'>";
        echo $opcion;
        echo "</label><br>";
    }
    echo "</br>";
    echo "</fieldset>";
    echo "</div>";
    echo "</br>";
}
?>

<div class="terminar">
    <input type="submit" name="submit" value="Terminar examen" class="boton_terminar">
</div>

</form>



</body>
</html>