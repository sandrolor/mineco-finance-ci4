<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Movimentos</h2>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <?= $error ?><br>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    <!-- Formulário de busca -->
    <form method="get" class="mb-3">
        <div class="row g-2 align-items-end">
            <!-- Campo de Busca -->
            <div class="col-md-3">
                <label for="search" class="form-label">Histórico</label>
                <input type="text" id="search" name="search" value="<?= esc($search) ?>" class="form-control" placeholder="Ex: Salário...">
            </div>

            <!-- Filtro de Data Inicial -->
            <div class="col-md-2">
                <label for="start_date" class="form-label">Data Inicial</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= esc($start_date) ?>">
            </div>

            <!-- Filtro de Data Final -->
            <div class="col-md-2">
                <label for="end_date" class="form-label">Data Final</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= esc($end_date) ?>">
            </div>

            <!-- Filtro de Conta -->
            <div class="col-md-2">
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

            <!-- Filtro de Categoria -->
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

            <!-- Botões [B] e [A] -->
            <div class="col-md-1 d-flex flex-column justify-content-end gap-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                <a href="<?= site_url('movimento/create') ?>" class="btn btn-success w-100" autofocus>Incluir</a>
            </div>
        </div>
    </form>

    <!-- Tabela de Movimentos -->
    <?php if (!empty($movimentos)): ?>
        <div class="text-end fw-bold mt-2">
            Saldo anterior: R$ <?= number_format($saldo_anterior, 2, ',', '.') ?>
        </div>
        <div class="table-responsive">
            <?php
            $dataAtual = '';
            $saldo_atual = $saldo_anterior;
            foreach ($movimentos as $movimento):
                // Agrupamento por data
                if ($dataAtual !== $movimento['data_mov']):
                    if ($dataAtual !== ''): ?>
                        <!-- Exibir saldo diário -->
                        <div class="text-end fw-bold mt-2">
                            Saldo do dia: R$ <?= number_format($saldo_atual, 2, ',', '.') ?>
                        </div>
                    <?php endif;
                    $dataAtual = $movimento['data_mov'];
                    ?>
                    <h5 class="bg-light p-2 text-primary">
                        <?= date('d/m/Y', strtotime($dataAtual)) ?>
                    </h5>
                <?php endif;

                // Atualizar saldo do dia
                $saldo_atual += $movimento['valor'];
                // Definir cor para o valor
                $valorClass = $movimento['valor'] > 0 ? 'text-success' : ($movimento['valor'] < 0 ? 'text-danger' : 'text-warning');
                ?>

                <!-- Linha do movimento -->
                <div class="row align-items-center border-bottom py-2">
                    <!-- Coluna 1: Histórico -->
                    <div class="col-md-6 fw-bold">
                        <?= esc($movimento['historico']) ?>
                    </div>

                    <!-- Coluna 2: Conta e Categoria -->
                    <div class="col-md-4 text-muted">
                        <?= esc($movimento['nome_conta']) ?> | <?= esc($movimento['nome_categoria']) ?>
                    </div>

                    <!-- Coluna 3: Valor (alinhado à direita com Flexbox) -->
                    <div class="col-md-1 d-flex justify-content-end fw-bold <?= $valorClass ?>">
                        <?= number_format($movimento['valor'], 2, ',', '.') ?>
                    </div>

                    <!-- Coluna 4: Botões de Ação -->
                    <div class="col-md-1 text-end">
                        <a href="<?= site_url('movimento/edit/' . $movimento['id']) ?>" class="btn btn-warning btn-sm me-2">A</a>
                        <a href="<?= site_url('movimento/delete/' . $movimento['id']) ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Tem certeza que deseja excluir?')">E</a>
                    </div>
                </div>

            <?php endforeach; ?>

            <!-- Saldo final do último dia -->
            <div class="text-end fw-bold mt-2">
                Saldo do Atual: R$ <?= number_format($saldo_atual, 2, ',', '.') ?>
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