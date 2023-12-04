<?php use Utils\Utils; ?>
<h2>INICIAR SESION</h2>
</header>
<main>
    <div class="loginContainer">
<?php if(!isset($_SESSION['indentity'])): ?>
<form action="<?=BASE_URL?>usuario/login/" method="post">
    <p>
    <label for="email">Email</label>
    <input id="email" type="text" name="data[email]" required>
    </p>
    <p>
    <label for="password">Contraseña</label>
    <input id="password" type="password" name="data[password]" required>
    </p>
    <?php if (isset($_SESSION['error_login'])): ?>
    <p class="error">Email o contraseña incorrectos</p>
    <?php
    Utils::deleteSession('error_login');
    endif;?>
    <p>
    <input type="submit" value="Iniciar sesion">
    </p>
</form>
<?php else: ?>
<h3><?=$_SESSION['identity']->nombre?><?= $_SESSION['identity']->apellidos ?></h3>
<?php endif; ?>
</div>