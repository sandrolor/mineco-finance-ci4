<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Movimentos</h2>

    <!-- Formulário de busca -->
    <form method="get" action="<?= site_url('movimentos') ?>" class="mb-4">
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
        <?php
        $dataAtual = '';
        $saldoDia = 0;
        ?>
        <div class="table-responsive">
            <?php foreach ($movimentos as $movimento): ?>
                <?php
                // Agrupamento por data
                if ($dataAtual !== $movimento['data_mov']):
                    if ($dataAtual !== ''): ?>
                        <!-- Exibir saldo diário -->
                        <div class="text-end fw-bold mt-2">
                            Saldo do dia: R$ <?= number_format($saldoDia, 2, ',', '.') ?>
                        </div>
                    <?php endif;
                    $dataAtual = $movimento['data_mov'];
                    $saldoDia = 0; // Resetar o saldo do dia
                    ?>
                    <h5 class="bg-light p-2 text-primary">
                        <?= date('d/m/Y', strtotime($dataAtual)) ?>
                    </h5>
                <?php endif; ?>

                <?php
                // Atualizar saldo do dia
                $saldoDia += $movimento['valor'];

                // Definir cor para o valor
                $valorClass = $movimento['valor'] > 0 ? 'text-success' : ($movimento['valor'] < 0 ? 'text-danger' : 'text-warning');
                ?>

                <!-- Linha do movimento -->
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <div class="fw-bold"><?= esc($movimento['historico']) ?></div>
                        <div class="text-muted">
                            <?= esc($movimento['nome_conta']) ?> - <?= esc($movimento['nome_categoria']) ?>
                        </div>
                    </div>
                    <div class="fw-bold <?= $valorClass ?>">
                        <?= number_format($movimento['valor'], 2, ',', '.') ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Saldo final do último dia -->
            <div class="text-end fw-bold mt-2">
                Saldo do dia: R$ <?= number_format($saldoDia, 2, ',', '.') ?>
            </div>
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

    <div class="d-flex justify-content-between">
        <p>Total de registros: <?= $total ?></p>
        <nav>
            <ul class="pagination">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>&search=<?= esc($search) ?>">Anterior</a>
                    </li>
                <?php endif; ?>
                <?php if ($currentPage * $perPage < $total): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>&search=<?= esc($search) ?>">Próxima</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<?= $this->endSection() ?>