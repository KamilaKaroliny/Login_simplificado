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
    // Qual ação foi enviada? ("login" ou "register")
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
        if ($dados && password_verify($pass, $dados["senha"])) {
            $_SESSION["user_pk"] = $dados["pk"];
            $_SESSION["username"] = $dados["username"];
            header("Location: ./public/telaInicial.php"); // envia para dashboard
            exit;
        } else {
            $msg = "Usuário ou senha incorretos!";
        }

    // CADASTRO
    } elseif ($action === "register") {
        if (!empty($user) && !empty($pass)) {
            // verificar se já existe usuário com esse nome
            $stmt = $mysqli->prepare("SELECT pk FROM usuarios WHERE username=?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $msg = "Usuário já existe!";
            } else {
                $stmt->close();

                // gera hash da senha para armazenar com segurança
                $senhaHash = password_hash($pass, PASSWORD_DEFAULT);

                // insere novo usuário no banco
                $stmt = $mysqli->prepare("INSERT INTO usuarios (username, senha) VALUES (?, ?)");
                $stmt->bind_param("ss", $user, $senhaHash);

                if ($stmt->execute()) {
                    $msg = "Usuário cadastrado com sucesso! Agora faça login.";
                } else {
                    $msg = "Erro ao cadastrar: " . $stmt->error;
                }
            }
            $stmt->close();
        } else {
            $msg = "Preencha todos os campos!";
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
    <p><small>Dica: admin / 123 </small></p>
  </div>

  <div class="card">
    <h3>Criar conta</h3>
    <?php if ($msg): ?><p class="msg"><?= $msg ?></p><?php endif; ?>
    <form method="post">
      <input type="hidden" name="action" value="register">
      <input type="text" name="username" placeholder="Novo usuário" required> <br><br>
      <input type="password" name="password" placeholder="Nova senha" required> <br><br>
      <button type="submit">Cadastrar</button>
    </form>
  </div>
<?php endif; ?>

</body>
</html>