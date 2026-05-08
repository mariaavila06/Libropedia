document.addEventListener('DOMContentLoaded', () => {
    const summary = document.getElementById('checkout-summary');
    if (summary) {
        const rateSpan = document.getElementById('rate-usd-text');
        const totalUsdSpan = document.getElementById('total-usd-text');
        const warning = document.getElementById('rate-warning');

        const totalBsRaw = summary.getAttribute('data-total-bs');
        const totalBs = parseFloat(totalBsRaw || '0');
        if (totalBs && totalBs > 0) {
            fetch('https://ve.dolarapi.com/v1/dolares/oficial')
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Respuesta no válida de la API');
                    }
                    return response.json();
                })
                .then((data) => {
                    const rate = data && typeof data.promedio === 'number'
                        ? data.promedio
                        : parseFloat(data.promedio ?? NaN);

                    if (!rate || rate <= 0) {
                        throw new Error('Tasa inválida');
                    }

                    const totalUsd = totalBs / rate;

                    const formatterBs = new Intl.NumberFormat('es-VE', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });

                    const formatterUsd = new Intl.NumberFormat('es-VE', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });

                    if (rateSpan) rateSpan.textContent = formatterBs.format(rate);
                    if (totalUsdSpan) totalUsdSpan.textContent = formatterUsd.format(totalUsd);
                })
                .catch(() => {
                    if (rateSpan) rateSpan.textContent = 'No disponible';
                    if (totalUsdSpan) totalUsdSpan.textContent = 'No disponible';
                    if (warning) warning.style.display = 'block';
                });
        } else {
            if (rateSpan) rateSpan.textContent = 'No disponible';
            if (totalUsdSpan) totalUsdSpan.textContent = 'No disponible';
        }
    }

    const form = document.getElementById('checkout-form');
    const invoiceModal = document.getElementById('invoice-modal');
    const invoiceDate = document.getElementById('invoice-date');
    const invoiceNumber = document.getElementById('invoice-number');
    const btnPrint = document.getElementById('invoice-print');
    const btnClose = document.getElementById('invoice-close');
    const btnDownloadBook = document.getElementById('invoice-download-book');

    if (form && invoiceModal) {
        form.addEventListener('submit', (event) => {
        });
    }

    if (btnClose && invoiceModal) {
        btnClose.addEventListener('click', () => {
            invoiceModal.classList.remove('active');
        });
    }

    if (btnPrint) {
        btnPrint.addEventListener('click', () => {
            window.print();
        });
    }

    if (btnDownloadBook) {
        btnDownloadBook.addEventListener('click', () => {
            window.location.href = 'index.php?action=downloads';
        });
    }
});

