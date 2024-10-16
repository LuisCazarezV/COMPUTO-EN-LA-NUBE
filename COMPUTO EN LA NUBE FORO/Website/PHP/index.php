<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GameStation UADEO Mx - Inicio</title>
  <link rel="stylesheet" href="/bootstrap/CSS/bootstrap.min.css">
  <link rel="stylesheet" href="/CSS/style.css">
</head>
<body>
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">GameStation UADEO Mx</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Noticias</a>
          </li>
          <?php if (isset($_SESSION['username'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="amigos.php">Amigos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="perfil.php">Perfil</a>
            </li>
          <?php endif; ?>
        </ul>
        <?php if (isset($_SESSION['username'])): ?>
          <span class="navbar-text me-2">Bienvenido, <?php echo $_SESSION['username']; ?></span>
          <a href="PHP/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-light">Iniciar Sesión</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- Sección de Noticias -->
  <section class="container mt-5">
    <h2 class="text-center mb-4">Últimas Noticias</h2>
    <div id="carouselNoticias" class="carousel slide" data-bs-ride="carousel">
      <!-- Carrusel de noticias aquí -->
    </div>
  </section>
</body>
</html>
