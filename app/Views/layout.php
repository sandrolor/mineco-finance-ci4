<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title><?= esc($title ?? 'Sistema Mineco') ?></title>

    <!-- Principal CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos customizados para esse template -->
    <link href="navbar-top-fixed.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="<?= site_url('dashboard') ?>">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('grupocontas') ?>">Grupo Contas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('contas') ?>">Contas</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="<?= site_url('grupocategorias') ?>">Grupo Categorias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('categorias') ?>">Categorias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('movimento') ?>">Movimento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('transferencias') ?>">Transferência</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('relatorios/saldo-contas') ?>">Saldos Contas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('relatorios/resultado-categorias') ?>">Resultado</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="<?= site_url('auth/logout') ?>">Sair</a>
                </li>
            </ul>

        </div>
    </nav>

    <main role="main" class="container">
        <div class="container mt-5">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
</body>

</html>