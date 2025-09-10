<?php
// login.php

// 1) Conexão
include('./includes/db.php');

session_start();

// 2) Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// 3) Login
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $action = $_POST["action"] ?? "";
    $user = trim($_POST["username"] ?? "");
    $pass = trim($_POST["password"] ?? "");

    // LOGIN
    if ($action === "login") {
        // busca usuário no banco
        $stmt = $mysqli->prepare("SELECT pk, username, senha FROM usuarios WHERE username=?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_assoc();
        $stmt->close();

        // verifica se encontrou usuário e se a senha bate
        if ($dados && $pass === $dados["senha"]) {
            $_SESSION["user_pk"] = $dados["pk"];
            $_SESSION["username"] = $dados["username"];
            header("Location: ./public/telaInicial.php"); // envia para a tela inicial
            exit;
        } else {
            $msg = "Usuário ou senha incorretos!";
        }
     }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Login Simples</title>
<link rel="stylesheet" href="./style/style.css">
</head>
<body>

<?php if (!empty($_SESSION["user_pk"])):
   header("Location: ./public/telaInicial.php");
?>

<?php else: ?>

  <div class="card">
    <h3>Login</h3>
    <?php if ($msg): ?><p class="msg"><?= $msg ?></p><?php endif; ?>
    <form method="post">
      <input type="hidden" name="action" value="login">
      <input type="text" name="username" placeholder="Usuário" required> <br><br>
      <input type="password" name="password" placeholder="Senha" required> <br><br>
      <button type="submit">Entrar</button>
    </form>

    <div class="flex">
      <p><small>Ainda não tem conta?</small></p>
      <a href="./public/cadastro.php" class="left" >Cadastre-se</a>
    </div>

    <p><small>Dica: admin / 123 </small></p>
  </div>

<?php endif; ?>

</body>
</html>