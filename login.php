<?php
session_start();
if(isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once "conexao.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM conta WHERE usuario = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario'] = $user['usuario'];
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Usuário ou senha incorretos.</p>";
    }

    $stmt->close();
}
?>

<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<h2 align="center" style="margin-top:20px;">Login</h2>
<form method="POST" class="auth-form">
    <input type="text" name="usuario" placeholder="Usuário ou E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>
<a href="cadastro.php" class="link-auth">Criar conta</a>

<div align="center" style="margin-top:20px;">
<a href="index.php"><button class="btn-olhada">Dar uma olhada nos imóveis</button></a>
</div>
