<?php
session_start();
require_once('db-connect.php');
$id = $_SESSION['generate_receipt_id'] ?? "";

$invoice_settings_qry = $conn->query("SELECT * FROM `settings_tbl`")->fetch_all(MYSQLI_ASSOC);
$invoice_settings = array_column($invoice_settings_qry, 'meta_value', 'meta_field');

$invoice_data = $conn->query("SELECT * FROM `invoices_tbl` where md5(`id`) = '{$id}'")->fetch_array();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo Imprimible de Factura | Generador de Factura en PHP</title>
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

<body class="printable-receipt">
    <div class="px-3 py-3">
        <div class="text-end fw-bolder"><small>Código de Factura: <?= $invoice_data['invoice_code'] ?? "" ?></small></div>
        <div class="lh-1 my-3">
            <h3 class="text-center fw-bolder my-2"><?= $invoice_settings['store_name'] ?></h3>
            <div class="text-center text-body-emphasis"><small><?= $invoice_settings['store_address'] ?? "" ?></small></div>
            <div class="text-center text-body-emphasis"><small><?= $invoice_settings['store_contact'] ?? "" ?></small></div>
        </div>
        <div class="d-flex w-100 justify-content-between">
            <div><small>Cliente: <?= $invoice_data['customer'] ?? "" ?></small></div>
            <div><small>Fecha/Hora: <?= date("Y-m-d g:i A", strtotime($invoice_data['created_at'] ?? "")) ?></small></div>
        </div>
        <hr class="dashed">
        <table class="w-100">
            <colgroup>
                <col width="15%">
                <col width="15%">
                <col width="50%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">CANT</th>
                    <th class="text-center">Unidades</th>
                    <th class="">Producto</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $items_qry = $conn->query("SELECT * FROM `invoice_meta_tbl` where `invoice_id` = '{$invoice_data['id']}' ");
                foreach ($items_qry->fetch_all(MYSQLI_ASSOC) as $item) :
                ?>
                    <tr>
                        <td class="text-center"><?= $item['qty'] ?></td>
                        <td class="text-center"><?= $item['unit'] ?></td>
                        <td class=""><?= $item['item'] ?> @ <small>(<?= number_format($item['price'], 2) ?>)</small></td>
                        <td class="text-end"><?= number_format($item['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr class="dashed">
        <div class="d-flex w-100 justify-content-between">
            <div class="text-dark fw-bold text">Sub-Total</div>
            <div class="text-end"><?= number_format($invoice_data['total_amount'], 2) ?></div>
        </div>
        <div class="d-flex w-100 justify-content-between">
            <div class="text-dark fw-bold text">Descuento (<?= $invoice_data['discount_percentage'] ?>)</div>
            <div class="text-end"><?= number_format($invoice_data['discount_amount'], 2) ?></div>
        </div>
        <div class="d-flex w-100 justify-content-between">
            <div class="text-dark fw-bold text">Total</div>
            <div class="text-end fw-bold"><?= number_format($invoice_data['total_amount'] - $invoice_data['discount_amount'], 2) ?></div>
        </div>
        <div class="d-flex w-100 justify-content-between">
            <div class="text-dark fw-bold text">Dinero entregado</div>
            <div class="text-end fw-bold"><?= number_format($invoice_data['tendered_amount'], 2) ?></div>
        </div>
        <div class="d-flex w-100 justify-content-between">
            <h4 class="text-dark fw-bold text">Cambio</h4>
            <h4 class="text-end fw-bold"><?= number_format(($invoice_data['tendered_amount'] - ($invoice_data['total_amount'] - $invoice_data['discount_amount'])), 2) ?></h4>
        </div>
        <hr class="dashed">
        <div class="my-4">
            <div class="text-center lh-1"><?= str_replace("\n", "<br>", $invoice_settings['footer_note']) ?></div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.print()
            setTimeout(function() {
                window.close()
            }, 500)
        }, 300)
    </script>
</body>

</html>

<?php
$conn->close();
if (isset($_SESSION['generate_receipt_id']))
    unset($_SESSION['generate_receipt_id']);
?>