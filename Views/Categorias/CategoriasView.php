<?php
use Utils\Utils;
// let's paginate data from an array...
$countries = array(
    // array of countries
);

// how many records should be displayed on a page?
$records_per_page = 6;
// instantiate the pagination object
$pagination = new Zebra_Pagination();

// the number of total records is the number of records in the array
$pagination->records(count($categorias));

// records per page
$pagination->records_per_page($records_per_page);

// here's the magic: we need to display *only* the records for the current page
$categorias = array_slice(
    $categorias,
    (($pagination->get_page() - 1) * $records_per_page),
    $records_per_page
);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<h2>CATEGORIAS</h2>
<?php if (isset($_SESSION['error'])) :?>
    <p class="error"><?= $_SESSION['error'] ?></p>
<?php
    Utils::deleteSession('error');
elseif (isset($_SESSION['exito'])): ?>
    <p class="exito"><?= $_SESSION['exito'] ?></p>
<?php
    Utils::deleteSession('exito');
endif; ?>
</header>
<main>
<div id="categoriasContainer">
    <ul>
        <li class="listaAddCategoria"><a class="linkAddCategoria" href="<?=BASE_URL?>Categoria/addCategoria/"><img class="imgAddCategoria" src="./Img/add.png" alt="Añadir"> Añadir categoria</a></li>
    <?php foreach ($categorias as $categoria): ?>
        <li class="categoria"><a class="linkCategoriaName" href="<?= BASE_URL ?>Entrada/mostrarEntradasPorId/?categoriaId=<?=$categoria->getId()?>&categoriaNombre=<?=$categoria->getNombre()?>"><b><?php echo $categoria->getNombre() ?></b></a>
            <?php if (isset($_SESSION['identificado'])):
            if ($_SESSION['identificado']->rol=='admin'):?>
            <a class="icono" href="<?=BASE_URL?>Categoria/editarCategoria/?idCategoria=<?= $categoria->getId() ?>"><img src="./Img/editar.png" alt="Editar"></a>
            <a class="icono" href="<?=BASE_URL?>Categoria/eliminarCategoriaPorId/?idCategoria=<?= $categoria->getId() ?>"><img src="./Img/papelera.png" alt="Eliminar"></a></li>
        <?php endif;
        endif;
        endforeach; ?>
    </ul>
    <div class="paginationLinksContainer"><?php $pagination->render();?></div>
</div>

