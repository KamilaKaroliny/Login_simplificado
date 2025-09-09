<?php 
include('../../includes/db.php');
include('../../includes/header.php'); 

// --- Filtros ---
$nomeFiltro = $_GET['nome'] ?? '';
$cidadeFiltro = $_GET['cidade'] ?? '';

// --- Paginação ---
$pagina = $_GET['pagina'] ?? 1;
$itens_por_pagina = 10;
$offset = ($pagina - 1) * $itens_por_pagina;

// Contar total de registros com filtros
$sqlCount = "SELECT COUNT(*) as total FROM times WHERE 1=1";
$paramsCount = [];
$typesCount = "";

// Filtro por nome
if ($nomeFiltro) {
    $sqlCount .= " AND nome LIKE ?";
    $paramsCount[] = "%$nomeFiltro%";
    $typesCount .= "s";
}

// Filtro por cidade
if ($cidadeFiltro) {
    $sqlCount .= " AND cidade LIKE ?";
    $paramsCount[] = "%$cidadeFiltro%";
    $typesCount .= "s";
}

$stmtCount = $mysqli->prepare($sqlCount);
if (!empty($paramsCount)) {
    $stmtCount->bind_param($typesCount, ...$paramsCount);
}
$stmtCount->execute();
$total_registros = $stmtCount->get_result()->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $itens_por_pagina);

// --- Consulta principal ---
$sql = "SELECT * FROM times WHERE 1=1";
$params = [];
$types = "";

// Filtro por nome
if ($nomeFiltro) {
    $sql .= " AND nome LIKE ?";
    $params[] = "%$nomeFiltro%";
    $types .= "s";
}

// Filtro por cidade
if ($cidadeFiltro) {
    $sql .= " AND cidade LIKE ?";
    $params[] = "%$cidadeFiltro%";
    $types .= "s";
}

// Ordenação por ID decrescente
$sql .= " ORDER BY id ASC LIMIT ?, ?";
$params[] = $offset;
$params[] = $itens_por_pagina;
$types .= "ii";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Times</h2>
        <a class="btn btn-success" href="create.php">➕ Cadastrar Time</a>
    </div>

    <!-- Formulário de Filtros -->
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" name="nome" class="form-control" placeholder="Filtrar por nome"
                   value="<?= htmlspecialchars($nomeFiltro) ?>">
        </div>
        <div class="col-md-4">
            <input type="text" name="cidade" class="form-control" placeholder="Filtrar por cidade"
                   value="<?= htmlspecialchars($cidadeFiltro) ?>">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <!-- Tabela -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['cidade']) ?></td>
                    <td>
                        <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('Deseja realmente excluir o time <?= $row['nome'] ?>?')">
                           Excluir
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&nome=<?= $nomeFiltro ?>&cidade=<?= $cidadeFiltro ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include('../../includes/footer.php'); ?>
<link rel="stylesheet" href="../../style/style.css">