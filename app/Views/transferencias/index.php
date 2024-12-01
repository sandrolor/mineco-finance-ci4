<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Transferências</h2>
    <a href="<?= site_url('transferencias/create') ?>" class="btn btn-success mb-3">Nova Transferência</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Data</th>
                <th>Conta Origem</th>
                <th>Conta Destino</th>
                <th>Valor</th>
                <th>Histórico</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($transferencias)) : ?>
                <?php foreach ($transferencias as $transf) : ?>
                    <tr>
                        <td><?= $transf['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($transf['data_mov'])) ?></td>
                        <td><?= esc($transf['conta_origem']) ?></td>
                        <td><?= esc($transf['conta_destino']) ?></td>
                        <td><?= number_format($transf['valor'], 2, ',', '.') ?></td>
                        <td><?= esc($transf['historico']) ?></td>
                        <td>
                            <a href="<?= site_url('transferencias/edit/' . $transf['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= site_url('transferencias/delete/' . $transf['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta transferência?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhuma transferência encontrada.</td>
                </tr>
            <?php endif; ?>
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
        <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>

    <?= $this->endSection() ?>