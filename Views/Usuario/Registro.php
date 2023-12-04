<h2>CREAR CUENTA</h2>
</header>
<main>
    <div class="registroContainer">
<?php use Utils\Utils; ?>
<?php if (isset($_SESSION['register']) && $_SESSION['register']=='complete'): ?>
<strong class="completado">Registro completado correctamente</strong>
<?php elseif (isset($_SESSION['register']) && $_SESSION['register']=='failed'): ?>
<strong class="fallido">Registro fallido, introduzca bien los datos</strong>
<?php endif; ?>
<?php Utils::deleteSession('register') ?>

<form action="<?=BASE_URL?>usuario/registro/" method="post">
    <p>
    <label for="nombre">Nombre</label>
    <input id="nombre" type="text" name="data[nombre]" required>
    </p>
    <p>
    <label for="apellidos">Apellidos</label>
    <input id="apellidos" type="text" name="data[apellidos]" required>
    </p>
    <p>
    <label for="email">Email</label>
    <input id="email" type="text" name="data[email]" required>
    </p>
    <p>
    <label for="password">Contraseña</label>
    <input id="password" type="password" name="data[password]" required>
    </p>
    <p>
    <input type="submit" value="Registrarse">
    </p>
</form>
</div>