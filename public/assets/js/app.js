const BASE_URL = document.querySelector('meta[name="base-url"]')?.content ?? '';

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function initProductPage() {
    const minus = document.getElementById('qty-minus');
    const plus = document.getElementById('qty-plus');
    const input = document.getElementById('quantity');

    if (!minus || !plus || !input) return;

    minus.addEventListener('click', function () {
        const val = parseInt(input.value) || 1;
        if (val > 1) input.value = val - 1;
    });

    plus.addEventListener('click', function () {
        const val = parseInt(input.value) || 1;
        const max = parseInt(input.max) || 99;
        if (val < max) input.value = val + 1;
    });
}

function initSearch() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    if (!searchInput || !searchResults) return;

    let timer;

    searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 2) {
            searchResults.classList.remove('active');
            searchResults.innerHTML = '';
            return;
        }

        timer = setTimeout(function () {
            fetch(BASE_URL + '/catalog/search?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    if (!data.products || data.products.length === 0) {
                        searchResults.innerHTML =
                            '<div class="search-item" style="color:var(--color-text-muted)">Нічого не знайдено</div>';
                    } else {
                        searchResults.innerHTML = data.products.map(p => `
                            <a href="${BASE_URL}/catalog/${escapeHtml(p.slug)}" class="search-item">
                                <span>${escapeHtml(p.name)}</span>
                                <span style="margin-left:auto;color:var(--color-pink);font-weight:600">
                                    ${parseFloat(p.price).toFixed(2)} грн
                                </span>
                            </a>
                        `).join('');
                    }
                    searchResults.classList.add('active');
                })
                .catch(() => searchResults.classList.remove('active'));
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('active');
            searchInput.value = '';
        }
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            searchResults.classList.remove('active');
            searchInput.value = '';
            searchInput.blur();
        }
    });
}

function initCart() {
    const cartList = document.getElementById('cart-list');
    if (!cartList) return;

    function updateUI(itemId, subtotal, total, count) {
        const subtotalEl = document.getElementById('subtotal-' + itemId);
        if (subtotalEl) subtotalEl.textContent = subtotal + ' грн';

        const totalEl = document.getElementById('summary-total');
        if (totalEl) totalEl.textContent = total + ' грн';

        const countEl = document.querySelector('.cart-count');
        if (countEl) countEl.textContent = count;
    }

    function removeItemFromDOM(itemId) {
        const row = document.getElementById('cart-item-' + itemId);
        if (row) row.remove();

        if (!cartList.querySelector('.cart-item')) {
            window.location.reload();
        }
    }

    function sendUpdate(itemId, quantity) {
        const formData = new FormData();
        formData.append('item_id', itemId);
        formData.append('quantity', quantity);

        fetch(BASE_URL + '/cart/update', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            body: formData,
        })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                const input = cartList.querySelector(`input[data-item-id="${itemId}"]`);
                const priceEl = document.querySelector(`#cart-item-${itemId} .cart-item__price-unit`);

                if (input && priceEl) {
                    const price = parseFloat(priceEl.textContent.replace(/[^\d.]/g, ''));
                    const qty = parseInt(input.value);
                    const subtotal = (price * qty).toFixed(2);
                    updateUI(itemId, subtotal, data.total, data.count);
                }

                if (quantity === 0) removeItemFromDOM(itemId);
            })
            .catch(() => alert('Помилка оновлення кошика. Спробуйте ще раз.'));
    }

    cartList.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action]');
        const removeBtn = e.target.closest('.cart-item__remove');

        if (btn) {
            const itemId = btn.dataset.itemId;
            const input = cartList.querySelector(`input[data-item-id="${itemId}"]`);
            if (!input) return;

            let qty = parseInt(input.value) || 1;
            const max = parseInt(input.max) || 99;

            if (btn.dataset.action === 'increase') qty = Math.min(qty + 1, max);
            if (btn.dataset.action === 'decrease') qty = Math.max(qty - 1, 0);

            input.value = qty;
            sendUpdate(itemId, qty);
        }

        if (removeBtn) {
            const itemId = removeBtn.dataset.itemId;
            const formData = new FormData();
            formData.append('item_id', itemId);

            fetch(BASE_URL + '/cart/remove', {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                body: formData,
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    removeItemFromDOM(itemId);
                    const totalEl = document.getElementById('summary-total');
                    if (totalEl) totalEl.textContent = data.total + ' грн';
                    const countEl = document.querySelector('.cart-count');
                    if (countEl) countEl.textContent = data.count;
                })
                .catch(() => alert('Помилка видалення товару. Спробуйте ще раз.'));
        }
    });

    cartList.addEventListener('change', function (e) {
        const input = e.target.closest('.quantity-control__input');
        if (!input) return;

        const itemId = input.dataset.itemId;
        let qty = parseInt(input.value) || 1;
        const max = parseInt(input.max) || 99;
        qty = Math.min(Math.max(qty, 1), max);
        input.value = qty;
        sendUpdate(itemId, qty);
    });
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('toast--visible'), 10);
    setTimeout(() => {
        toast.classList.remove('toast--visible');
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

function initAddToCart() {
    const form = document.getElementById('add-to-cart-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(BASE_URL + '/cart/add', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            body: formData,
        })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                const countEl = document.querySelector('.cart-count');
                if (countEl) countEl.textContent = data.count;

                showToast(data.message ?? 'Товар додано до кошика');
            })
            .catch(() => alert('Помилка. Спробуйте ще раз.'));
    });
}

function initUserMenu() {
    const trigger = document.querySelector('.user-menu__trigger');
    const dropdown = document.querySelector('.user-menu__dropdown');
    if (!trigger || !dropdown) return;

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        const expanded = trigger.getAttribute('aria-expanded') === 'true';
        trigger.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', function () {
        trigger.setAttribute('aria-expanded', 'false');
        dropdown.classList.remove('active');
    });

    dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
    });
}

function initCartCount() {
    fetch(BASE_URL + '/cart/count', {
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
        .then(r => r.json())
        .then(data => {
            const countEl = document.querySelector('.cart-count');
            if (countEl) countEl.textContent = data.count;
        })
        .catch(() => {});
}

document.addEventListener('DOMContentLoaded', () => {
    initProductPage();
    initSearch();
    initCart();
    initAddToCart();
    initUserMenu();
    initCartCount();
});