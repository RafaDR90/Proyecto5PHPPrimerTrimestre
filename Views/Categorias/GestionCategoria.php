<h2>EDITAR CATEGORIA</h2>
</header>
<main>
<?php
if (isset($add)){
    $action=BASE_URL.'Categoria/addCategoria/';
    $value='Añadir';
}elseif (isset($_GET['idCategoria'])){
    $action=BASE_URL.'Categoria/editarCategoria/?idCategoria='.$_GET['idCategoria'];
    $value='Editar';
}else {
    header('Location: ' . BASE_URL . 'Categoria/mostrarCategorias/?error='.urlencode('No se ha encontrado la categoria'));
}
?>
    <div class="loginContainer">
        <form class="editarCategorias" action="<?= $action ?>" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php if($value=='Editar') echo $categoriaEdit['nombre'] ?>">
            <input type="submit" value="<?=$value?>">
        </form>
    </div>
<?php unset($categoriaEdit);