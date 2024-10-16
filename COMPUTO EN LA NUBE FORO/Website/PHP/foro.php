<?php
session_start();
require 'db.php'; // Conexión a la base de datos

// Comprobar si el usuario está logueado
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Verificar si el usuario ya ha creado un post
$user_post = null;
if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_post = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todos los posts del foro
$stmt = $conn->query("SELECT posts.post_id, posts.content, users.username, posts.created_at 
                      FROM posts 
                      JOIN users ON posts.user_id = users.user_id 
                      ORDER BY posts.created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro - GameStation UADEO Mx</title>
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
                        <a class="nav-link" href="index.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="foro.php">Foro</a>
                    </li>
                </ul>
                <?php if ($user_id): ?>
                    <a href="logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="login.html" class="btn btn-outline-light">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Sección del Foro -->
    <section class="container mt-5">
        <h2 class="text-center mb-4">Foro de Discusión</h2>

        <!-- Mostrar botón de "Crear Post" o "Editar Post" según si el usuario tiene un post -->
        <div class="d-flex justify-content-between mb-4">
            <?php if ($user_id): ?>
                <?php if (!$user_post): ?>
                    <a href="create_post.html" class="btn btn-success">Crear Post</a>
                <?php else: ?>
                    <a href="edit_post.php?post_id=<?php echo $user_post['post_id']; ?>" class="btn btn-warning">Editar Mi Post</a>
                <?php endif; ?>
            <?php else: ?>
                <p>Inicia sesión para crear o editar tu post.</p>
            <?php endif; ?>
        </div>

        <!-- Lista de Posts -->
        <div class="row">
            <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($post['username']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($post['content']); ?></p>
                                <small class="text-muted">Publicado el <?php echo date('d M Y, H:i', strtotime($post['created_at'])); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay posts disponibles.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3 mt-5">
        &copy; 2024 GameStation UADEO Mx. Todos los derechos reservados.
    </footer>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
