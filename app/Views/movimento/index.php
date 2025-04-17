<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Movimentos</h2>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <?= $error ?><br>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    <!-- Formulário de busca -->
    <form method="get" action="<?= site_url('movimento') ?>" class="mb-4">
        <?= csrf_field() ?>
        <div class="input-group">
            <input type="text" name="search" value="<?= esc($search) ?>" class="form-control" placeholder="Buscar por histórico ou categoria...">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <a href="<?= site_url('movimento/create') ?>" class="btn btn-success mb-3">Novo Movimento</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Tabela de Movimentos -->
    <?php if (!empty($movimentos)): ?>
        <div class="text-start fw-bold mb-3">
            Saldo anterior: R$ <?= number_format($saldo_anterior, 2, ',', '.') ?>
        </div>
        <?php
        $saldoAtual = $saldo_anterior;
        foreach ($movimentos as $movimento):
            $saldoAtual += $movimento['valor'];
        ?>
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>
                    <div class="fw-bold"><?= esc($movimento['historico']) ?></div>
                    <div class="text-muted">
                        <?= esc($movimento['nome_conta']) ?> - <?= esc($movimento['nome_categoria']) ?>
                    </div>
                </div>
                <div class="fw-bold <?= $movimento['valor'] > 0 ? 'text-success' : 'text-danger' ?>">
                    <?= number_format($movimento['valor'], 2, ',', '.') ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="text-end fw-bold mt-3">
            Saldo atual: R$ <?= number_format($saldoAtual, 2, ',', '.') ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Nenhum movimento encontrado.
        </div>
    <?php endif; ?>
    <tfoot>
        <tr>
            <td colspan="5" class="text-end">
                <small class="text-muted">
                    Total de registros: <?= esc($total) ?>
                </small>
            </td>
        </tr>
    </tfoot>
    </table>
</div>

<?= $this->endSection() ?>