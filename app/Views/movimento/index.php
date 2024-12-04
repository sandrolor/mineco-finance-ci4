<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1>Movimentos</h1>

<form method="get" action="<?= site_url('movimento') ?>" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col">
            <label for="data_inicial" class="form-label">Data Inicial</label>
            <input type="date" id="data_inicial" name="data_inicial" class="form-control" value="<?= esc($dataInicial) ?>">
        </div>
        <div class="col">
            <label for="data_final" class="form-label">Data Final</label>
            <input type="date" id="data_final" name="data_final" class="form-control" value="<?= esc($dataFinal) ?>">
        </div>
        <div class="col">
            <label for="conta_id" class="form-label">Conta</label>
            <select id="conta_id" name="conta_id" class="form-select">
                <option value="">Todas as contas</option>
                <?php foreach ($contas as $conta): ?>
                    <option value="<?= esc($conta['id']) ?>" <?= $contaSelecionada == $conta['id'] ? 'selected' : '' ?>>
                        <?= esc($conta['nomeconta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label for="categoria_id" class="form-label">Categoria</label>
            <select id="categoria_id" name="categoria_id" class="form-select">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= esc($categoria['id']) ?>" <?= $categoriaSelecionada == $categoria['id'] ? 'selected' : '' ?>>
                        <?= esc($categoria['nomecategoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </div>
</form>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row align-items-center mb-4">
    <div class="col-auto">
        <a href="<?= site_url('movimento/create') ?>" class="btn btn-success w-100">Novo Movimento</a>
    </div>
    <div class="col text-center">
        <!--<strong>Saldo anterior:</strong> R$ <?= number_format($saldoAnterior, 2, ',', '.') ?>-->
    </div>
    <div class="col text-end">
        <strong>Saldo anterior:</strong> R$ <?= number_format($saldoAnterior, 2, ',', '.') ?>
        <!--<strong>Saldo atual:</strong> R$ <?= number_format($saldoAtual, 2, ',', '.') ?>-->
    </div>
</div>

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
                    <!-- <div class="text-end fw-bold mt-2">
                        Saldo do dia: R$ <?= number_format($saldoDia, 2, ',', '.') ?>
                    </div> -->
                <?php endif;
                $dataAtual = $movimento['data_mov'];
                $saldoDia = 0; // Resetar o saldo do dia
                ?>
                <h6 class="bg-light p-2 text-primary">
                    <?= date('d/m/Y', strtotime($dataAtual)) ?>
                </h6>
            <?php endif; ?>

            <?php
            // Atualizar saldo do dia
            $saldoDia += $movimento['valor'];

            // Definir cor para o valor
            $valorClass = $movimento['valor'] > 0 ? 'text-success' : ($movimento['valor'] < 0 ? 'text-danger' : 'text-warning');
            ?>

            <!-- Linha do movimento -->
            <div>
                <div class="fw-normal">
                    <?= esc($movimento['historico']) ?>
                    <div class="row align-items-center mb-2">
                        <!-- Categoria -->
                        <div class="col-md-4 text-muted">
                            <?= esc($movimento['nomecategoria']) ?>
                        </div>
                        <!-- Conta -->
                        <div class="col-md-4 text-muted">
                            <?= esc($movimento['nomeconta']) ?>
                        </div>
                        <!-- Valor -->
                        <div class="col-md-1 fw-bold <?= $valorClass ?>">
                            <?= number_format($movimento['valor'], 2, ',', '.') ?>
                        </div>
                        <!-- Saldo -->
                        <div class="col-md-1 fw-bold text-end">
                            <?= number_format($movimento['saldo_acumulado'], 2, ',', '.') ?>
                        </div>
                        <!-- Editar e Excluir -->
                        <div class="col-md-2 text-end">
                            <a href="<?= site_url('movimento/edit/' . $movimento['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= site_url('movimento/delete/' . $movimento['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Saldo final do último dia -->
        <div class="text-end fw-bold mt-2">
            <strong>Saldo atual:</strong> R$ <?= number_format($saldoAtual, 2, ',', '.') ?>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        Nenhum movimento encontrado.
    </div>
<?php endif; ?>

<?= $this->endSection() ?>