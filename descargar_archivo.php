<?php
if (isset($_GET['id'])) {

    $conexion = new PDO("mysql:host=localhost;dbname=proyecto_fct;charset=UTF8", "root", "");
    $id_archivo = $_GET['id'];
    
    $consulta = $conexion->prepare("SELECT nombre_archivo, tipo_archivo, datos_archivo FROM archivos WHERE id = ?");
    $consulta->bindParam(1, $id_archivo);
    $consulta->execute();
    $consulta->bindColumn(1, $nombre_archivo);
    $consulta->bindColumn(2, $tipo_archivo);
    $consulta->bindColumn(3, $datos_archivo);
    
    if ($consulta->fetch(PDO::FETCH_BOUND)) {
        header("Content-Type: $tipo_archivo");
        header("Content-Disposition: attachment; filename=$nombre_archivo");
        echo $datos_archivo;
        exit;
    } else {
        echo "Archivo no encontrado.";
    }
}
?>