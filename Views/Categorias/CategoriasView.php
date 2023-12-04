<?php

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

?>
<h2>CATEGORIAS</h2>
</header>
<main>
<div id="categoriasContainer">
    <ul>
        <li class="listaAddCategoria"><a class="linkAddCategoria" href=""><img class="imgAddCategoria" src="./Img/add.png" alt="Añadir"> Añadir categoria</a></li>
    <?php foreach ($categorias as $categoria): ?>
        <li class="categoria"><a class="linkCategoriaName" href="<?php BASE_URL ?>Entrada/mostrarEntradasPorId/?idCategoria=<?php echo $categoria->getID() ?>"><b><?php echo $categoria->getNombre() ?></b></a>
            <a class="icono" href=""><img src="./Img/add.png" alt="Añadir"></a>
            <a class="icono" href=""><img src="./Img/editar.png" alt="Editar"></a>
            <a class="icono" href=""><img src="./Img/papelera.png" alt="Eliminar"></a></li>
        <?php endforeach; ?>
    </ul>
    <div class="paginationLinksContainer"><?php $pagination->render();?></div>
</div>

