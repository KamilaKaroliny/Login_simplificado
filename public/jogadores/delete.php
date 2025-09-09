<?php 
include('../../includes/db.php');
include('../../includes/header.php');

$id = $_GET['id'];

$sql = "DELETE FROM jogadores WHERE id=$id";
if ($mysqli->query($sql)) {
    echo "Jogador excluído!";
} else {
    echo "Erro: " . $mysqli->error;
}
?>
<a href="read.php">Voltar para lista</a>