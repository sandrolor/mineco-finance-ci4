<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Movimentos</h2>

    <form method="get" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar movimento..." value="<?= esc($search) ?>">
        <button class="btn btn-primary">Buscar</button>
    </form>

    <a href="<?= site_url('movimento/create') ?>" class="btn btn-success mb-3">Novo Movimento</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Data</th>
                <th>Histórico</th>
                <th>Conta</th>
                <th>Categoria</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentos as $movimento): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($movimento['data_mov'])) ?></td>
                    <td><?= esc($movimento['historico']) ?></td>
                    <td><?= esc($movimento['conta_nome']) ?></td>
                    <td><?= esc($movimento['categoria_nome'] ?? '-') ?></td>
                    <td><?= number_format($movimento['valor'], 2, ',', '.') ?></td>
                    <td>
                        <a href="<?= site_url('movimento/edit/' . $movimento['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="<?= site_url('movimento/delete/' . $movimento['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
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

    <div class="d-flex justify-content-between">
        <p>Total de registros: <?= $total ?></p>
        <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
</div>

<?= $this->endSection() ?>