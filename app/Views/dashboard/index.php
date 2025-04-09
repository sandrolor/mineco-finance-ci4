<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <div class="p-5 bg-light rounded">
        <h2 class="text-center">Bem-vindo, <?= session('username') ?>!</h2>
        <h3 class="text-center">Sistema de Controle Financeiro.</h3>
    </div>
</div>
<?= $this->endSection() ?>