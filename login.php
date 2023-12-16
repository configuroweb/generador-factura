<?php
session_start();
if (isset($_SESSION['cashier']) && !empty($_SESSION['cashier'])) {
    header("location: home.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['cashier'] = $_POST['cashier'];
    header("location: ./");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceder | Generador de Facturas en PHP</title>
    <!-- Fontawesome CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Fontawesome CSS CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- jQuery CSS CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body class="login-page">
    <div class="page-title"><strong>Generador de Facturas en PHP - Acceder</strong></div>
    <div class="col-lg-4 col-md-8 col-sm-12 col-12 my-3">
        <div class="card shadow w-100">
            <div class="card-body">
                <div class="container-fluid">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label" for="cashier">Selecciona cajero:</label>
                            <select name="cashier" id="cashier" class="form-select" required="required">
                                <option value="" disabled selected> -- Seleccionar Usuari@ --</option>
                                <option>Cajero 1</option>
                                <option>Cajero 2</option>
                                <option>Cajero 3</option>
                                <option>Cajero 4</option>
                                <option>Cajero 5</option>
                            </select>
                        </div>
                        <div class="mb-3 w-100 d-flex justify-content-center">
                            <button class="btn btn-primary rounded-pill"><i class="fas fa-sign-in-alt"></i> Acceder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>

</html>