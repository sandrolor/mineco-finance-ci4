<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2><?= isset($movimento) ? 'Editar Movimento' : 'Novo Movimento' ?></h2>

    <form action="<?= isset($movimento) ? site_url('movimento/update/' . $movimento['id']) : site_url('movimento/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="row">
            <div class="col-2">
                <div class="mb-3">
                    <label for="data_mov" class="form-label">Data</label>
                    <input type="date" id="data_mov" name="data_mov" class="form-control" value="<?= isset($movimento) ? esc($movimento['data_mov']) : '' ?>" required>
                </div>
            </div>

            <div class="col-10">
                <div class="mb-3">
                    <label for="historico" class="form-label">Histórico</label>
                    <input type="text" id="historico" name="historico" class="form-control" maxlength="255" value="<?= isset($movimento) ? esc($movimento['historico']) : '' ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="conta_id" class="form-label">Conta</label>
                    <select id="conta_id" name="conta_id" class="form-control" required>
                        <option value="">Selecione</option>
                        <?php foreach ($contas as $conta): ?>
                            <option value="<?= $conta['id'] ?>" <?= isset($movimento) && $movimento['conta_id'] == $conta['id'] ? 'selected' : '' ?>>
                                <?= esc($conta['nomeconta']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Operação</label>
                    <select id="tipo" name="tipo" class="form-control" required>
                        <option value="Receita" <?= isset($movimento) && $movimento['tipo'] == 'Receita' ? 'selected' : '' ?>>Receita</option>
                        <option value="Despesa" <?= isset($movimento) && $movimento['tipo'] == 'Despesa' ? 'selected' : '' ?>>Despesa</option>
                        <!-- <option value="Transferência" <?= isset($movimento) && $movimento['tipo'] == 'Transferência' ? 'selected' : '' ?>>Transferência</option> -->
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select id="categoria_id" name="categoria_id" class="form-control">
                        <option value="">Selecione</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id'] ?>" <?= isset($movimento) && $movimento['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                                <?= esc($categoria['nomecategoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="number" id="valor" name="valor" step="0.01" class="form-control" value="<?= isset($movimento) ? esc($movimento['valor']) : '' ?>" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?= isset($movimento) ? 'Atualizar' : 'Salvar' ?></button>
    </form>
</div>

<?= $this->endSection() ?>