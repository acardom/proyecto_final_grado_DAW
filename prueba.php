<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subir Archivo</title>
</head>
<body>
  <h1>Subir Archivo</h1>
  <form action="procesar_archivo.php" method="post" enctype="multipart/form-data">
    <label for="archivo">Selecciona un archivo:</label>
    <input type="file" name="archivo" id="archivo" required>
    <br>
    <button type="submit">Subir Archivo</button>
  </form>
</body>
</html>