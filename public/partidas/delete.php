<?php
include('../../includes/db.php');
include('../../includes/header.php');

// Verifica se veio ID pela URL
if (!isset($_GET['id'])) {
    echo "<p>ID da partida não informado.</p>";
    exit;
}

$id = $_GET['id'];

// Busca a partida no banco
$stmt = $conn->prepare("SELECT p.id, p.data_jogo, t1.nome AS time_casa, t2.nome AS time_fora 
                        FROM partidas p
                        JOIN times t1 ON p.time_casa_id = t1.id
                        JOIN times t2 ON p.time_fora_id = t2.id
                        WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$partida = $result->fetch_assoc();

if (!$partida) {
    echo "<p>Partida não encontrada.</p>";
    exit;
}

// Se enviou o formulário para excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM partidas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: read.php");
    exit;
}
?>

<h2>Excluir Partida</h2>
<p>Deseja realmente excluir a partida: 
    <strong><?= $partida['time_casa'] ?> x <?= $partida['time_fora'] ?></strong> 
    do dia <strong><?= $partida['data_jogo'] ?></strong>?
</p>

<form method="post">
    <button type="submit" class="btn btn-danger">Excluir</button>
    <a href="read.php" class="btn btn-secondary">Cancelar</a>
</form>