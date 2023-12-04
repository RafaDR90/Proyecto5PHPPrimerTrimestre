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
$pagination->records(count($entradas));

// records per page
$pagination->records_per_page($records_per_page);

// here's the magic: we need to display *only* the records for the current page
$entradas = array_slice(
    $entradas,
    (($pagination->get_page() - 1) * $records_per_page),
    $records_per_page
);
?>
<h2><?php echo $_GET['nombreCategoria']?></h2>
</header>
<main>
<div id="categoriasContainer">
    <ul>
        <li class="listaAddCategoria"><a class="linkAddCategoria" href=""><img class="imgAddCategoria" src="./Img/add.png" alt="Añadir"> Añadir entrada</a></li>
        <?php foreach ($entradas as $entrada):
            $fecha=new DateTime($entrada->getDate());
            foreach ($usuarios as $usuario):
                if ($entrada->getUsuarioId()==$usuario->getId()):
                    $nombreUsuario=$usuario->getNombre();
                endif;
                endforeach;
            ?>
            <li class="entradalista"><p class="entradaIzq"><span class="tituloEntrada"><b><?php echo $entrada->getTitulo(); ?></b></span><span class="titularEntrada">Autor:<u><?php echo $nombreUsuario; ?></u></span></p>
                <p class="entradaMid"><?php echo $entrada->getDescripcion(); ?></p>
                <p class="entradaDer"><span class="fechaEntrada"><?php echo $fecha->format('d-m-Y');?></span><span class="iconos"><a class="icono" href=""><img src="./Img/editar.png" alt="Editar"></a>
                <a class="icono" href=""><img src="./Img/papelera.png" alt="Eliminar"></a></span></p>
                </li>
        <?php endforeach; ?>
    </ul>
    <div class="paginationLinksContainer"><?php $pagination->render();?></div>
</div>

