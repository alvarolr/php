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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tarefa"]) && empty($_POST["id_editar"])) {
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



//Atualizar tarefa (quando está editando)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_editar"])){
    $id = $_POST["id_editar"];
    $descricao = $_POST["tarefa"];
    $conn->query("UPDATE tarefas SET descricao='$descricao' WHERE id=$id");

}


//Buscar tarefa para edição
$tarefa_editar = null;
if (isset($_GET["edit"])){
    $id = $_GET["edit"];
    $res = $conn->query("SELECT * FROM tarefas WHERE id=$id");
    if($res->num_rows > 0){
        $tarefa_editar = $res->fetch_assoc();
    }
}



?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tarefas (PHP)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista de Tarefas</h1>
    <form method="POST">
        <input type="text" name="tarefa" placeholder="Digite uma tarefa" required 
        value="<?php if ($tarefa_editar) echo $tarefa_editar['descricao']?>">

        <?php if ($tarefa_editar): ?>
            <input type="hidden" name="id_editar" value="<?php echo $tarefa_editar['id']; ?>">
            
            <button type="submit"> Atualizar </button>
            <button><a href="index.php"> cancelar</a></button>
        <?php else: ?>
            <button type="submit">Adicionar</button>
        <?php endif; ?>
    </form>

    <?php
       /*
       if ($tarefa_editar) {
            echo '<input type="hidden" name="id_editar" value="' . $tarefa_editar['id'] . '">';
            echo '<button type="submit">Atualizar</button>';
            echo '<a href="index.php">Cancelar</a>';
        } else {
             echo '<button type="submit">Adicionar</button>';
             }
        */
    ?>


    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?PHP echo $row["descricao"]; ?> 
                <a href="?edit=<?= $row["id"]; ?>">Editar</a>
                <a href="?delete=<?= $row["id"]; ?>">Remover</a>
            </li>
        <?php endwhile; ?>
    </ul>

</body>
</html>

<?php $conn->close(); ?>
