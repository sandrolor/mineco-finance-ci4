<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Categorias</h2>

    <form method="get" class="d-flex mb-3">
        <?= csrf_field() ?>
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar categoria..." value="<?= esc($search) ?>">
        <button class="btn btn-primary">Buscar</button>
    </form>

    <a href="<?= site_url('categorias/create') ?>" class="btn btn-success mb-3">Nova Categoria</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Grupo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= esc($categoria['id']) ?></td>
                    <td><?= esc($categoria['nomecategoria']) ?></td>
                    <td><?= esc($categoria['grupo_nomegrupo'] ?? 'Sem Grupo') ?></td>
                    <td>
                        <a href="<?= site_url('categorias/edit/' . $categoria['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="<?= site_url('categorias/delete/' . $categoria['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
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

    <div>
        <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
</div>

<?= $this->endSection() ?>