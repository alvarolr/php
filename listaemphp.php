<?php


/*
SQL necessário para a tarefa
CREATE DATABASE tarefas_db;
USE tarefas_db;

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL
);
*/


$servername = "localhost";
$username = "root"; // Usuário padrão do XAMPP/MAMP
$password = ""; // Senha padrão (deixe vazia se não tiver senha)
$dbname = "tarefas_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: ");
}

// Adicionar tarefa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tarefa"])) {
    $tarefa = $_POST["tarefa"];
    $sql = "INSERT INTO tarefas (descricao) VALUES ('$tarefa')";
    $conn->query($sql);
}

// Remover tarefa
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM tarefas WHERE id=$id");
}

// Buscar tarefas
$result = $conn->query("SELECT * FROM tarefas");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tarefas (PHP)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista em PHP</h1>
    <form method="POST">
        <input type="text" name="tarefa" placeholder="Digite uma tarefa" required>
        <button type="submit">Adicionar</button>
    </form>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?PHP echo $row["descricao"]; ?> 
                <a href="?delete=<?= $row["id"]; ?>">Remover</a>
            </li>
        <?php endwhile; ?>
    </ul>

</body>
</html>

<?php $conn->close(); ?>
