<?php
include_once 'conexion.php';

$dbHost = getenv('DB_HOST');
$dbName = "cefire-webapp01b-database";
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contingut = $_POST['contingut'] ?? '';
    if (!empty($contingut)) {

        $conn = new DBConnection();
        $conn = $conn->connectDb($dbHost, $dbName, $dbUser, $dbPass);
        if ($conn) {
            try {
                // Preparar la consulta SQL
                $stmt = $conn->prepare("INSERT INTO prueba (contenido) VALUES (:contingut)");
                // Vincular el parámetro
                $stmt->bindParam(':contingut', $contingut, PDO::PARAM_STR);
                // Ejecutar la consulta
                $stmt->execute();
                echo "<h1>Contingut enviat correctament: " . htmlspecialchars($contingut) . "</h1>";
            } catch (PDOException $e) {
                // Manejar errores de la base de datos
                error_log('Error al insertar el contingut: ' . $e->getMessage());
                echo "<h1>Error al enviar el contingut: " . htmlspecialchars($e->getMessage()) . "</h1>";
            } finally {
                // Cerrar la conexión
                $conn = null;
            }
        }
    }
}

$id = $contingut = "";

$conn = new DBConnection();
$conn = $conn->connectDb($dbHost, $dbName, $dbUser, $dbPass);
if (!$conn) {
    echo "<h1>Error al conectar a la base de datos</h1>";   
} else {
    try {
        // Preparar la consulta SQL
        $stmt = $conn->prepare("SELECT id, contenido FROM prueba");
        // Ejecutar la consulta
        $stmt->execute();
        // Obtener los resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "<h1>Continguts disponibles:</h1>";
            foreach ($result as $row) {
                echo "<p>ID: " . htmlspecialchars($row['id']) . " - Contingut: " . htmlspecialchars($row['contenido']) . "</p>";
            }
        } else {
            echo "<h1>No hay continguts disponibles</h1>";
        }
    } catch (PDOException $e) {
        // Manejar errores de la base de datos
        error_log('Error al obtener los continguts: ' . $e->getMessage());
        echo "<h1>Error al obtener los continguts: " . htmlspecialchars($e->getMessage()) . "</h1>";
    } finally {
        // Cerrar la conexión
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <hr>
    <form action="index.php" method="post">
        <label for="contingut">contingut:</label>
        <input type="text" id="contingut" name="contingut" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>

