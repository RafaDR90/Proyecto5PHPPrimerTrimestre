<!DOCTYPE html>
<html lang="es">
<head>
    <base href="<?= BASE_URL ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="./Style/Normalize.css">
    <link rel="stylesheet" href="./Style/Header.css">
    <link rel="stylesheet" href="./Style/Footer.css">
    <link rel="stylesheet" href="./Style/Main.css">
    <link rel="stylesheet" href="./Style/CategoriasEntradas.css">
    <link rel="stylesheet" href="./Style/Registro.css">
    <link rel="stylesheet" href="./Style/Login.css">
    <link rel="stylesheet" href="./vendor/stefangabos/zebra_pagination/public/css/zebra_pagination.css" type="text/css">
</head>
<body>
    <header>
        <h1>My blog</h1>
        <nav>
            <a href="<?= BASE_URL ?>">Ver categorias</a>
            <?php if (isset($_SESSION['identificado'])): ?>
                <a href=<?= BASE_URL ?>usuario/editar/">Editar cuenta</a>
                <a href=<?= BASE_URL ?>usuario/logout/">Cerrar sesion</a>
                <?php if ($_SESSION['identificado']=='admin'): ?>
                    <a href="">Crear categoria</a>
                <?php endif; ?>
            <?php else: ?>
            <a href="<?= BASE_URL ?>usuario/identifica/">Identificate</a>
            <a href="<?= BASE_URL ?>Usuario/registro/">Crear cuenta</a>
            <?php endif; ?>
        </nav>
