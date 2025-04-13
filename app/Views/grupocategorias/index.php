<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Grupos de Categorias</h2>

    <form method="get" class="d-flex mb-3">
        <?= csrf_field() ?>
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar grupo..." value="<?= esc(service('request')->getGet('search')) ?>">
        <button class="btn btn-primary">Buscar</button>
    </form>

    <a href="<?= site_url('grupocategorias/create') ?>" class="btn btn-success mb-3">Novo Grupo</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Grupo de Categorias</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($grupos as $grupo): ?>
                <tr>
                    <td><?= $grupo['id'] ?></td>
                    <td><?= esc($grupo['nomegrupo']) ?></td>
                    <td>
                        <a href="<?= site_url('grupocategorias/edit/' . $grupo['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="<?= site_url('grupocategorias/delete/' . $grupo['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir este grupo?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-end">
                    <small class="text-muted">
                        Total de registros: <?= $pager->getTotal() ?>
                    </small>
                </td>
            </tr>
        </tfoot>
    </table>

    <p>Total de registros: <?= $total ?></p>
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>

<?= $this->endSection() ?>