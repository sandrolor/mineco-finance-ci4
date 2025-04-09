<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <div class="p-5 bg-light rounded text-center">
        <h2>Bem-vindo, <?= session('username') ?>!</h2>
        <h3>Sistema de Controle Financeiro.</h3>
    </div>
</div>
<?= $this->endSection() ?>