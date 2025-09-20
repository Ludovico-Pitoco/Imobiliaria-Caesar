<?php
session_start();
if(isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $erros = [];

    if (empty($usuario) || empty($email) || empty($telefone) || empty($_POST['senha'])) {
        $erros[] = "Todos os campos são obrigatórios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido.";
    }

    if (empty($erros)) {
        $sql = "INSERT INTO conta (usuario, email, senha, telefone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $usuario, $email, $senha, $telefone);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Cadastro realizado com sucesso! <a href='login.php'>Faça login</a></p>";
        } else {
            echo "<p style='color:red;'>Erro: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        foreach ($erros as $erro) {
            echo "<p style='color:red;'>$erro</p>";
        }
    }
}
?>

<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<h2 align="center" style="margin-top:20px;">Cadastro</h2>
<form method="POST" class="auth-form">
    <input type="text" name="usuario" placeholder="Usuário" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="text" name="telefone" placeholder="Telefone" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Cadastrar</button>
</form>
<a href="login.php" class="link-auth">Já tenho conta</a>

<div align="center" style="margin-top:20px;">
<a href="index.php"><button class="btn-olhada">Dar uma olhada nos imóveis</button></a>
</div>

