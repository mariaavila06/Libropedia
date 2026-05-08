<?php require __DIR__ . '/partials/header.php'; ?>

<main class="checkout-hero">
    <section class="hero-text">
        <h1>Compra Realizada</h1>
        <p>Gracias por tu compra. Tu pedido ha sido procesado exitosamente.</p>
    </section>
</main>

<section class="checkout-section">
    <div class="checkout-layout">
        <div class="checkout-left">
            <h2 class="checkout-title">Libros Comprados</h2>
            <ul class="cart-items">
                <?php 
                $invoice = $_SESSION['last_invoice'] ?? [];
                if (!empty($invoice['items'])) {
                    foreach ($invoice['items'] as $item): ?>
                        <li class="cart-item">
                            <div>
                                <div class="cart-item-name">
                                    <?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="cart-item-qty">
                                    Autor: <?php echo htmlspecialchars($item['autor'], ENT_QUOTES, 'UTF-8'); ?> | Cantidad: <?php echo (int) $item['cantidad']; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; 
                } else {
                    echo '<p>No hay libros en esta compra.</p>';
                }
                ?>
            </ul>

            <div class="invoice-actions">
                <button class="btn-primary" onclick="openInvoiceModal()">Ver Factura</button>
                <a href="index.php?action=downloads" class="btn-primary">Descargar Libros</a>
                <a href="index.php?action=store" class="btn-secondary">Volver a la Tienda</a>
            </div>
        </div>

        <aside class="checkout-right">
            <h3 class="checkout-summary-title">Información de Pago</h3>
            <p>Método de pago: <?php echo htmlspecialchars($_POST['metodo_pago'] ?? 'No especificado', ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Estado: Pagado</p>
            <p>Los libros están disponibles en tu sección de descargas.</p>
        </aside>
    </div>
</section>

<!-- Modal de Factura -->
<div class="modal-overlay" id="invoice-modal">
    <div class="modal-content">
        <div class="invoice-container ticket-fiscal">
            <div class="ticket-header">
                <div class="bold-text">SENIAT</div>
                <div class="bold-text">LIBRERÍA LA LIBROPEDIA</div>
                <div>Factura: <?php echo $_SESSION['last_invoice']['id'] ?? 'N/A'; ?></div>
                <div><?php echo date('d/m/Y H:i', strtotime($_SESSION['last_invoice']['fecha'] ?? 'now')); ?></div>
            </div>

            <div class="separator">---------------- Datos del Cliente ----------------</div>
            <div class="customer-data">
                NOMBRE: <?php echo strtoupper($_SESSION['user_name'] ?? 'USUARIO'); ?><br>
                CI: <?php echo $_SESSION['user_ci'] ?? 'N/A'; ?>
            </div>

            <div class="separator">------------------- F A C T U R A -------------------</div>

            <table class="items-table">
                <tbody>
                    <?php 
                    $invoice = $_SESSION['last_invoice'];
                    foreach($invoice['items'] as $item): 
                        $precioSinIva = $item['subtotal_bs'] / 1.16;
                    ?>
                    <tr>
                        <td colspan="2"><?php echo strtoupper($item['nombre'] . " - " . $item['autor']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $item['cantidad']; ?>.000 X</td>
                        <td><?php echo number_format($precioSinIva, 2, ',', '.'); ?> T16.00% <?php echo number_format($item['subtotal_bs'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="separator-dots">......................................................</div>

            <div class="totals-section">
                <div class="row"><span>SUBTTL. Bs</span> <span><?php echo number_format($invoice['subtotalBs'], 2, ',', '.'); ?></span></div>
                <div class="row"><span>IVA 16%</span> <span><?php echo number_format($invoice['ivaBs'], 2, ',', '.'); ?></span></div>
                <div class="row bold-text"><span>A PAGAR</span> <span><?php echo number_format($invoice['totalConIvaBs'], 2, ',', '.'); ?></span></div>
                <div class="row"><span>PUNTO DE VENTA</span> <span><?php echo number_format($invoice['totalConIvaBs'], 2, ',', '.'); ?></span></div>
                <div class="row bold-text"><span>TOTAL Bs.</span> <span><?php echo number_format($invoice['totalConIvaBs'], 2, ',', '.'); ?></span></div>
            </div>

            <div class="tax-breakdown">
                <div class="row"><span>Exentos =</span> <span>0,00</span></div>
                <div class="row"><span>BI G16% = <?php echo number_format($invoice['subtotalBs'], 2, ',', '.'); ?></span> <span>IVA G16% = <?php echo number_format($invoice['ivaBs'], 2, ',', '.'); ?></span></div>
            </div>

            <div class="footer-thanks">
                Gracias por tu compra - NRO: <?php echo rand(1000000, 9999999); ?>
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn-primary" onclick="printInvoice()">Imprimir</button>
            <button class="btn-secondary" onclick="closeInvoiceModal()">Cerrar</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 400px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }
    .ticket-fiscal {
        font-family: monospace;
        font-size: 12px;
        line-height: 1.2;
    }
    .bold-text { font-weight: bold; }
    .separator { text-align: center; margin: 10px 0; }
    .separator-dots { font-size: 10px; }
    .row { display: flex; justify-content: space-between; margin: 2px 0; }
    .modal-actions { text-align: center; margin-top: 20px; }
    .modal-actions .btn-primary, .modal-actions .btn-secondary {
        margin: 5px;
        display: inline-block;
        text-decoration: none;
        padding: 10px 20px;
    }
    @media print {
        body * { visibility: hidden; }
        .invoice-container, .invoice-container * { visibility: visible; }
        .invoice-container { position: absolute; left: 0; top: 0; }
        .modal-actions { display: none; }
    }
</style>

<script>
    function openInvoiceModal() {
        document.getElementById('invoice-modal').classList.add('active');
    }
    function closeInvoiceModal() {
        document.getElementById('invoice-modal').classList.remove('active');
    }
    function printInvoice() {
        window.print();
    }
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>