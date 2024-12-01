<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logue-se no sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body class="text-center">
    <main class="form-signin">
        <form method="post" action="<?= site_url('auth/login') ?>">
			<?= csrf_field() ?> <!-- Adiciona o campo CSRF -->
            <img class="mb-4" src="<?php echo base_url('assets/img/code-igniter-logo.png') ?>" alt="" width="72" height="72">
            <h1 class="h3 mb-3 fw-normal">Por favor, logue-se</h1>
            <label for="username" class="visually-hidden">Usuário</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Usuário" required autofocus>
            <label for="inputPassword" class="visually-hidden">Senha</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Senha" required>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2024-2024 - MinEco - Sandro Luiz Reis</p>
        </form>
        <?php $msg = session()->getFlashData('error') ?>
        <?php if (!empty($msg)) : ?>
            <div class="alert alert-danger">
                <?php echo $msg ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>