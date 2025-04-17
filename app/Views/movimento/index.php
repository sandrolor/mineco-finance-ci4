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

    <form method="get" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Data Inicial</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= esc($start_date) ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Data Final</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= esc($end_date) ?>">
            </div>
            <div class="col-md-3">
                <label for="conta_id" class="form-label">Conta</label>
                <select id="conta_id" name="conta_id" class="form-control">
                    <option value="">Todas</option>
                    <?php foreach ($contas as $conta): ?>
                        <option value="<?= $conta['id'] ?>" <?= isset($_GET['conta_id']) && $_GET['conta_id'] == $conta['id'] ? 'selected' : '' ?>>
                            <?= esc($conta['nomeconta']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="categoria_id" class="form-label">Categoria</label>
                <select id="categoria_id" name="categoria_id" class="form-control">
                    <option value="">Todas</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= isset($_GET['categoria_id']) && $_GET['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                            <?= esc($categoria['nomecategoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <a href="<?= site_url('movimento/create') ?>" class="btn btn-success mb-3">Novo Movimento</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Tabela de Movimentos -->
    <?php if (!empty($movimentos)): ?>
        <div class="table-responsive">
            <?php
            $dataAtual = '';
            $saldoDia = 0;
            foreach ($movimentos as $movimento):
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
                <?php endif;

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
                    <div class="d-flex gap-2">
                        <div class="fw-bold <?= $valorClass ?>">
                            <?= number_format($movimento['valor'], 2, ',', '.') ?>
                        </div>
                        <!-- Botões de Ação -->
                        <div>
                            <a href="<?= site_url('movimento/edit/' . $movimento['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= site_url('movimento/delete/' . $movimento['id']) ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Saldo final do último dia -->
            <div class="text-end fw-bold mt-2">
                Saldo do dia: R$ <?= number_format($saldoDia, 2, ',', '.') ?>
            </div>
            <div class="text-end fw-bold mt-2">
                Total: R$ <strong><?= number_format(array_sum(array_column($movimentos, 'valor')), 2, ',', '.') ?></strong>
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
</div>

<?= $this->endSection() ?>