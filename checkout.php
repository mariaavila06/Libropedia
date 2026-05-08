<?php require __DIR__ . '/partials/header.php'; ?>

<main class="checkout-hero">
    <section class="hero-text">
        <h1>Checkout</h1>
        <p>Revisa tu pedido y selecciona el método de pago.</p>
    </section>
</main>

<section class="checkout-section">
    <?php if (empty($items)): ?>
        <p class="empty-state">Tu carrito está vacío.</p>
    <?php else: ?>
        <div class="checkout-layout">
            <div class="checkout-left">
                <h2 class="checkout-title">Finalizar Compra</h2>
                <p class="checkout-subtitle">Revisa tu pedido y elige tu método de pago</p>

                <div class="checkout-preferences">
                    <h3>Preferencias</h3>
                    <label class="checkbox-row">
                        <input type="checkbox" checked>
                        <span>Recibir notificaciones y promociones</span>
                    </label>
                </div>

                <div class="checkout-methods">
                    <h3>Métodos de Pago</h3>

                    <form class="checkout-payment-form" id="checkout-form" action="index.php?action=process_checkout" method="post">
                        <label class="payment-option">
                            <input type="radio" name="metodo_pago" value="pagomovil" required>
                            <div class="payment-content">
                                <span class="payment-title">Pago Móvil</span>
                                <span class="payment-subtitle">Transferencia instantánea</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metodo_pago" value="transferencia" required>
                            <div class="payment-content">
                                <span class="payment-title">Transferencia Bancaria</span>
                                <span class="payment-subtitle">Entre bancos</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metodo_pago" value="debito" required>
                            <div class="payment-content">
                                <span class="payment-title">Tarjeta de Débito</span>
                                <span class="payment-subtitle">Venezolana</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metodo_pago" value="credito" required>
                            <div class="payment-content">
                                <span class="payment-title">Tarjeta de Crédito</span>
                                <span class="payment-subtitle">Venezolana</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metodo_pago" value="internacional" required>
                            <div class="payment-content">
                                <span class="payment-title">Tarjeta Internacional</span>
                                <span class="payment-subtitle">Visa, MasterCard, etc.</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metodo_pago" value="paypal" required>
                            <div class="payment-content">
                                <span class="payment-title">Paypal</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metodo_pago" value="zelle" required>
                            <div class="payment-content">
                                <span class="payment-title">Zelle</span>
                            </div>
                        </label>

                        <div class="checkout-extra">
                            <h3>Información Adicional</h3>
                            <div class="form-group">
                                <label for="comentarios">Comentarios (opcional)</label>
                                <textarea id="comentarios" rows="3" placeholder="Indícanos algo especial sobre tu pedido..." maxlength="30"></textarea>
                            </div>
                        </div>

                        <div class="checkout-actions">
                            <a href="index.php?action=store" class="btn-secondary checkout-back">Volver al carrito</a>
                            <button type="submit" class="btn-primary checkout-pay">Procesar pago</button>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="checkout-right">
                <h3 class="checkout-summary-title">Resumen de tu Pedido</h3>

                <div class="checkout-summary-items">
                    <?php foreach ($items as $item): ?>
                        <div class="summary-item">
                            <div>
                                <div class="summary-item-name">
                                    <?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="summary-item-qty">
                                    x<?php echo (int) $item['cantidad']; ?>
                                </div>
                            </div>
                            <div class="summary-item-price" data-book-pdf="<?php echo htmlspecialchars($item['pdf_path'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo number_format($item['subtotal_bs'], 2, ',', '.'); ?> Bs
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="checkout-summary-totals" id="checkout-summary"
                     data-total-bs="<?php echo htmlspecialchars($totalConIvaBs, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span><?php echo number_format($subtotalBs, 2, ',', '.'); ?> Bs</span>
                    </div>
                    <div class="summary-row">
                        <span>IVA (16%):</span>
                        <span><?php echo number_format($ivaBs, 2, ',', '.'); ?> Bs</span>
                    </div>
                    <div class="summary-row summary-row-strong">
                        <span>Total VES:</span>
                        <span><?php echo number_format($totalConIvaBs, 2, ',', '.'); ?> Bs</span>
                    </div>
                    <div class="summary-row">
                        <span>Tasa promedio (Bs/USD):</span>
                        <span id="rate-usd-text">Cargando...</span>
                    </div>
                    <div class="summary-row">
                        <span>Total USD (pago):</span>
                        <span id="total-usd-text">Cargando...</span>
                    </div>
                </div>

                <p class="checkout-rate-warning" id="rate-warning" style="display:none;">
                    Error al cargar tipo de cambio. Usando solo total en bolívares.
                </p>
            </aside>
        </div>
        <!-- Modal de factura estilo ticket -->
        <div class="invoice-backdrop" id="invoice-modal">
            <div class="invoice-modal" id="invoice-content">
                <div class="invoice-header">
                    <div class="invoice-center-line">
                        SENIAT
                    </div>
                    <div class="invoice-center-line invoice-company">
                        LIBRERÍA LA LIBROPEDIA
                    </div>
                    <div class="invoice-center-line invoice-small">
                        AV. PRINCIPAL DE LA LECTURA, LOCAL 7<br>
                        CARACAS - VENEZUELA
                    </div>
                    <div class="invoice-center-line invoice-small">
                        RIF: J-00000000-0
                    </div>
                </div>
                <div class="invoice-body">
                    <div class="invoice-row">
                        <span id="invoice-date"></span>
                        <span>Factura: <span id="invoice-number"></span></span>
                    </div>
                    <div class="invoice-separator">
                        ------------- Datos del Consumidor -------------
                    </div>
                    <div class="invoice-row">
                        <span>Nombre:</span>
                        <span><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'Invitado'; ?></span>
                    </div>
                    <div class="invoice-row">
                        <span>RIF/CI:</span>
                        <span>-</span>
                    </div>
                    <div class="invoice-separator">
                        -------------------- F A C T U R A --------------------
                    </div>
                    <div class="invoice-row invoice-header-row">
                        <span>ARTÍCULO</span>
                        <span>VALOR (Bs)</span>
                    </div>
                    <div class="invoice-items">
                        <?php foreach ($items as $item): ?>
                            <div class="invoice-item">
                                <div class="invoice-item-left">
                                    <div>
                                        <?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="invoice-small">
                                        Cant: <?php echo (int) $item['cantidad']; ?> &nbsp;
                                        Vl_unit: <?php echo number_format($item['precio_bs'], 2, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="invoice-item-right">
                                    <?php echo number_format($item['subtotal_bs'], 2, ',', '.'); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="invoice-dotted-line"></div>
                    <div class="invoice-row">
                        <span>SUBTTL. Bs</span>
                        <span><?php echo number_format($subtotalBs, 2, ',', '.'); ?></span>
                    </div>
                    <div class="invoice-row">
                        <span>IVA 16%</span>
                        <span><?php echo number_format($ivaBs, 2, ',', '.'); ?></span>
                    </div>
                    <div class="invoice-row invoice-strong">
                        <span>A PAGAR</span>
                        <span><?php echo number_format($totalConIvaBs, 2, ',', '.'); ?> Bs</span>
                    </div>
                    <div class="invoice-dotted-line"></div>
                    <div class="invoice-row invoice-small">
                        <span>Exentos = 0,00</span>
                        <span>IVA G16% = <?php echo number_format($ivaBs, 2, ',', '.'); ?></span>
                    </div>
                </div>
                <div class="invoice-footer">
                    <p class="invoice-small">Vuelva Siempre</p>
                    <div class="invoice-actions">
                        <button type="button" class="btn-secondary" id="invoice-download-book">Descargar libro</button>
                        <button type="button" class="btn-secondary" id="invoice-print">Imprimir / PDF</button>
                        <button type="button" class="btn-primary" id="invoice-close">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>

