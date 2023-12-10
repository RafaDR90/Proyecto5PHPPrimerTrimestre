<?php
use Utils\Utils;
use JasonGrimes\Paginator;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Assuming $entradas is your array of entries
$totalItems = count($entradas);
$itemsPerPage = 6; // Set the number of items per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$urlPattern = BASE_URL.'entrada/mostrarEntradasPorId/?page=(:num)';

$paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

// Get the subset of entries for the current page
$start = ($currentPage - 1) * $itemsPerPage;
$end = $start + $itemsPerPage;
$currentPageEntries = array_slice($entradas, $start, $itemsPerPage);


?>
<h2><?php if(isset($_SESSION['categoriaNombre'])) echo $_SESSION['categoriaNombre']?></h2>
<?php if (isset($_SESSION['error'])) :?>
    <p class="error"><?= $_SESSION['error'] ?></p>
    <?php
    Utils::deleteSession('error');
elseif (isset($_SESSION['exito'])): ?>
    <p class="exito"><?=$_SESSION['exito']?></p>
    <?php
    Utils::deleteSession('exito');
endif;?>
</header>
<main>
    <div id="categoriasContainer">
        <ul>
            <li class="listaAddCategoria"><a class="linkAddCategoria" href="<?=BASE_URL?>Entrada/addEntrada/"><img class="imgAddCategoria" src="./Img/add.png" alt="Añadir"> Añadir entrada</a></li>
            <?php foreach ($currentPageEntries as $entrada):
                $fecha = new DateTime($entrada->getDate());
                foreach ($usuarios as $usuario):
                    if ($entrada->getUsuarioId() == $usuario->getId()):
                        $nombreUsuario = $usuario->getNombre();
                    endif;
                endforeach;
                ?>
                <li class="entradalista">
                    <p class="entradaIzq">
                        <span class="tituloEntrada"><b><?php echo $entrada->getTitulo(); ?></b></span>
                        <span class="titularEntrada">Autor:<u><?php echo $nombreUsuario; ?></u></span>
                    </p>
                    <p class="entradaMid"><?php echo $entrada->getDescripcion(); ?></p>
                    <p class="entradaDer">
                        <span class="fechaEntrada"><?php echo $fecha->format('d-m-Y');?></span>
                        <span class="iconos">
                    <?php if (isset($_SESSION['identificado'])):?>
                        <a class="icono" href="<?=BASE_URL?>Entrada/editEntrada/?idEntrada=<?=$entrada->getId()?>"><img src="./Img/editar.png" alt="Editar"></a>
                        <a class="icono" href="<?=BASE_URL?>Entrada/deleteEntrada/?idEntrada=<?=$entrada->getId()?>"><img src="./Img/papelera.png" alt="Eliminar"></a>
                    <?php endif;?>
                </span>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="paginationLinksContainer">
            <?php if ($paginator->getNumPages() > 1): ?>
                <ul class="pagination">
                    <?php if ($paginator->getPrevUrl()): ?>
                        <li><a href="<?php echo $paginator->getPrevUrl(); ?>">&laquo; Previous</a></li>
                    <?php endif; ?>
                    <?php foreach ($paginator->getPages() as $page): ?>
                        <?php if ($page['url']): ?>
                            <li <?php echo $page['isCurrent'] ? 'class="active"' : ''; ?>>
                                <a href="<?php echo $page['url']; ?>"><?php echo $page['num']; ?></a>
                            </li>
                        <?php else: ?>
                            <li class="disabled"><span><?php echo $page['num']; ?></span></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($paginator->getNextUrl()): ?>
                        <li><a href="<?php echo $paginator->getNextUrl(); ?>">Next &raquo;</a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
