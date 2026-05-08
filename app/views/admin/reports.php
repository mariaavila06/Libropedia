<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="checkout-hero">
    <section class="hero-text">
        <h1>Reportes</h1>
        <p>Reportes de ventas y estadísticas.</p>
    </section>
</main>

<section class="checkout-section">
    <div class="checkout-layout admin-layout">
        <div class="checkout-left">
            <h2 class="checkout-title">Historial de Ventas</h2>
            <table class="admin-table">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Libro</th>
                    <th>Cliente</th>
                    <th>Costo (Bs)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($venta['fecha_venta'])); ?></td>
                        <td><?php echo date('H:i', strtotime($venta['fecha_venta'])); ?></td>
                        <td><?php echo htmlspecialchars($venta['libro_nombre'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($venta['autor'], ENT_QUOTES, 'UTF-8'); ?>)</td>
                        <td><?php echo htmlspecialchars($venta['usuario_nombre'] . ' ' . $venta['apellido'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo number_format($venta['precio_bs'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h2 class="checkout-title" style="margin-top:2rem;">Libros Más Vendidos</h2>
            <canvas id="topSellingChart" width="400" height="200"></canvas>

            <h2 class="checkout-title" style="margin-top:2rem;">Ventas Mensuales</h2>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de ventas mensuales
    const salesData = <?php echo json_encode($salesByMonth); ?>;
    const labels = salesData.map(item => item.mes);
    const sales = salesData.map(item => parseFloat(item.total_ventas));
    const incomes = salesData.map(item => parseFloat(item.total_ingresos));

    const ctxSales = document.getElementById('salesChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Ventas',
                data: sales,
                backgroundColor: 'rgba(215, 155, 90, 0.5)',
                borderColor: 'rgba(215, 155, 90, 1)',
                borderWidth: 1
            }, {
                label: 'Ingresos Totales (Bs)',
                data: incomes,
                backgroundColor: 'rgba(195, 130, 58, 0.5)',
                borderColor: 'rgba(195, 130, 58, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de libros más vendidos
    const topSellingData = <?php echo json_encode($topSelling); ?>;
    const topLabels = topSellingData.map(item => item.nombre + ' (' + item.autor + ')');
    const topSales = topSellingData.map(item => parseInt(item.ventas));

    const ctxTop = document.getElementById('topSellingChart').getContext('2d');
    new Chart(ctxTop, {
        type: 'bar',
        data: {
            labels: topLabels,
            datasets: [{
                label: 'Ventas',
                data: topSales,
                backgroundColor: 'rgba(215, 155, 90, 0.5)',
                borderColor: 'rgba(215, 155, 90, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Barras horizontales
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>

