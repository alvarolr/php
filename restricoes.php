<?php
// Conexão com o banco
$mysqli = new mysqli("localhost", "root", "", "escola_db");

// Verifica erro
if ($mysqli->connect_errno) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

// Inserção
$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $idade = (int)$_POST["idade"];

    $stmt = $mysqli->prepare("INSERT INTO alunos (email, idade) VALUES (?, ?)");
    $stmt->bind_param("si", $email, $idade);

    if ($stmt->execute()) {
        $mensagem = "Aluno cadastrado com sucesso!";
    } else {
        $mensagem = "Erro: " . $stmt->error;
    }
    $stmt->close();
}

// Consulta alunos
$alunos = $mysqli->query("SELECT * FROM alunos");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Alunos</title>
</head>
<body>
    <h1>Cadastro de Alunos</h1>

    <form method="POST">
        Email: <input type="email" name="email" required><br>
        Idade: <input type="number" name="idade" required><br>
        <button type="submit">Cadastrar</button>
    </form>

    <p><strong><?= $mensagem ?></strong></p>

    <h2>Lista de Alunos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Idade</th>
        </tr>
        <?php while($aluno = $alunos->fetch_assoc()): ?>
        <tr>
            <td><?= $aluno["id"] ?></td>
            <td><?= $aluno["email"] ?></td>
            <td><?= $aluno["idade"] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
