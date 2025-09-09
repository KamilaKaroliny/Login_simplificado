<?php 
include('../../includes/db.php'); 

session_start();
if (!isset($_SESSION["user_pk"])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];

$sql = "DELETE FROM times WHERE id=$id";
if ($mysqli->query($sql)) {
    header("Location: read.php");
    exit;
} else {
    echo "<div class='alert alert-danger mt-3'>Erro: " . $conn->error . "</div>";
}
?>