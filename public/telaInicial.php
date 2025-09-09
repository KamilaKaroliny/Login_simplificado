<?php
session_start();
if (!isset($_SESSION["user_pk"])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <a href="../../includes/db.php"></a>
    <link rel="stylesheet" href="../../style/style.css">
    <a href="jogadores/create.php"></a>
    <a href="jogadores/delete.php"></a>
    <a href="jogadores/read.php"></a>
    <a href="jogadores/update.php"></a>
    <a href="partidas/create.php"></a>
    <a href="partidas/delete.php"></a>
    <a href="partidas/read.php"></a>
    <a href="partidas/update.php"></a>
    <a href="times/create.php"></a>
    <a href="times/delete.php"></a>
    <a href="times/read.php"></a>
    <a href="times/update.php"></a>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <title>CRUD FUTEBOL</title>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../assets/icons/logo.png" alt="Logo" width="40" class="d-inline-block align-text-top me-2">
            CRUD Futebol
        </a>
        <p id="data"></p>
    </div>
</nav><br>

<div class="container login-options text-center">
    <h1 class="mb-5">Escolha o que deseja analisar!</h1>
    <div class="d-grid gap-5 col-7 mx-auto">
        <button class="btn btn-success btn-login" onclick="window.location.href='jogadores/read.php'">
            Jogadores
        </button>
        <button class="btn btn-success btn-login" onclick="window.location.href='partidas/read.php'">
            Partidas
        </button>
        <button class="btn btn-success btn-login" onclick="window.location.href='times/read.php'">
            Times
        </button>
        <button class="btn btn-success btn-login" onclick="window.location.href='../login.php?logout=1'">
            Logout
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('../includes/footer.php'); ?>