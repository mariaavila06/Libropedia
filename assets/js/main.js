document.addEventListener('DOMContentLoaded', () => {
    const loginBtn = document.getElementById('btn-login');
    const registerBtn = document.getElementById('btn-register');
    const loginModal = document.getElementById('modal-login');
    const registerModal = document.getElementById('modal-register');
    const cartBtn = document.getElementById('btn-cart');
    const cartModal = document.getElementById('modal-cart');
    const closeButtons = document.querySelectorAll('[data-close-modal]');

    function openModal(modal) {
        if (modal) {
            modal.classList.add('active');
        }
    }

    function closeModal(modal) {
        if (modal) {
            modal.classList.remove('active');
        }
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            openModal(loginModal);
        });
    }

    if (registerBtn) {
        registerBtn.addEventListener('click', () => {
            openModal(registerModal);
        });
    }

    if (cartBtn) {
        cartBtn.addEventListener('click', () => {
            openModal(cartModal);
        });
    }

    closeButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-close-modal');
            const modal = document.getElementById(targetId);
            closeModal(modal);
        });
    });

    [loginModal, registerModal, cartModal].forEach((modal) => {
        if (!modal) return;
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

});

