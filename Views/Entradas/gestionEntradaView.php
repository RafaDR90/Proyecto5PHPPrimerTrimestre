<?php
if (isset($_SESSION['identificado'])&&(isset($_SESSION['categoriaId'])&&isset($_SESSION['categoriaNombre'])) || isset($entrada)):
    if (isset($entrada)):
        $submit='Editar';
        $action='Entrada/editEntrada/'?>
        <h2>Editar entrada <?=$entrada['titulo']?></h2>
    <?php else:
        $submit='a&ntilde;adir';
        $action='Entrada/addEntrada/'?>
        <h2>Entrada para <?=$_SESSION['categoriaNombre']?></h2>
    <?php endif;?>
    </header>
    <main>
    <div class="loginContainer">
    <form action="<?=BASE_URL.$action?>" method="post">
        <?php if (isset($entrada)):?>
        <input type="hidden" name="entrada[id]" value="<?=$entrada['id']??''?>">
        <?php endif;?>
        <label for="titulo">Titulo</label>
        <input type="text" name="entrada[titulo]" id="titulo" value="<?= $entrada['titulo']??'' ?>" required>
        <label for="descripcion">Descripcion</label>
        <textarea name="entrada[descripcion]" id="descripcion" cols="30" rows="10" required><?=$entrada['descripcion']??''?></textarea>
        <input type="submit" value="<?=$submit?>">
    </form>
    </div>
    <?php
else:
    $_SESSION['error']='Lo sentimos, parece que a habido alg&uacute;n problema';
    header('Location:'.BASE_URL);
endif;