<?php 

// 1) Conexão
include('../includes/db.php');

// 2) cadastro
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $action = $_POST["action"] ?? "";
    $user = trim($_POST["username"] ?? "");
    $pass = trim($_POST["password"] ?? "");

    } elseif ($action === "cadastrar") {
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

                // insere novo usuário no banco
                $senha = $pass; // senha real do usuário
                $stmt = $mysqli->prepare("INSERT INTO usuarios (username, senha) VALUES (?, ?)");
                $stmt->bind_param("ss", $user, $senha);

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


?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Login Simples</title>
<link rel="stylesheet" href="../style/style.css">
</head>
<body>

<?php if (!empty($_SESSION["user_pk"])):
   header("Location: telaInicial.php");
?>

<?php else: ?>
  
  <div class="card">
    <h3>Criar conta</h3>
    <?php if ($msg): ?><p class="msg"><?= $msg ?></p><?php endif; ?>
    <form method="post">
      <input type="hidden" name="action" value="cadastrar">
      <input type="text" name="username" placeholder="Novo usuário" required> <br><br>
      <input type="password" name="password" placeholder="Nova senha" required> <br><br>
      <button onclick="window.location.href='../login.php'">
        Cadastrar
      </button>
    </form>
  </div>

<?php endif; ?>

</body>
</html>