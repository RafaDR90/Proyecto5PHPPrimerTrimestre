<?php
use Utils\Utils;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['identificado']):?>
    <h2>EDITAR CUENTA</h2>
    <?php if (isset($editado)):?>
    <p class="exito"><?=$editado?></p>
    <?php unset($editado);
    elseif(isset($error)):?>
    <p class="error"><?=$error?></p>
    <?php unset($error);
    elseif(isset($noCoincide)):?>
        <p class="error">Las contrase&ntilde;as no coinciden</p>
        <?php unset($noCoincide);
    endif;?>
    </header>
    <main>
    <?php if (isset($editaDatos)):?>
    <div class="loginContainer">
        <form action="<?=BASE_URL?>Usuario/editar/" method="post">
            <label for="nombre">Nombre</label>
            <input type="text" name="datos[nombre]" value="<?=$_SESSION['identificado']->nombre?>">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="datos[apellidos]" value="<?=$_SESSION['identificado']->apellidos?>">
            <label for="email">Email</label>
            <input type="email" name="datos[email]" value="<?=$_SESSION['identificado']->email?>">
            <input type="submit" value="Editar">
        </form>
    </div>
    <?php elseif(isset($editaPassword)):?>
    <div class="loginContainer">
        <?php if (isset($error)):?>
        <p class="error"><?=$error?></p>
        <?php unset($error);
        endif;
        ?>
        <form action="<?=BASE_URL?>Usuario/editar/" method="post">
            <label for="password">Nueva contrase&ntilde;a</label>
            <input type="password" id="password" name="password[password1]">
            <label for="password">Repite la contrase&ntilde;a</label>
            <input type="password" id="confirm_password" name="password[password2]">
            <input type="submit" value="Editar">
        </form>
    </div>
    <?php else:?>

    <div class="opciones">
        <h4>&iquest;Que deseas editar&quest;</h4>
        <a href="<?=BASE_URL?>Usuario/editar/?editDatos=true">Editar datos</a>
        <a href="<?=BASE_URL?>Usuario/editar/?editaPassword=true">Editar Contraseña</a>
    </div>
<?php endif;
    else:
    $_SESSION['error']='Debe identificarse para editar su cuenta';
    header('Location:'.BASE_URL);
endif;
