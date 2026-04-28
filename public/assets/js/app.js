const BASE_URL = document.querySelector('meta[name="base-url"]')?.content ?? '';

document.addEventListener('DOMContentLoaded', function () {
    const minus = document.getElementById('qty-minus');
    const plus  = document.getElementById('qty-plus');
    const input = document.getElementById('quantity');

    if (minus && plus && input) {
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

    const searchInput   = document.getElementById('search-input');
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
                            <a href="${BASE_URL}/catalog/${p.slug}" class="search-item">
                                <span>${p.name}</span>
                                <span style="margin-left:auto;color:var(--color-pink);font-weight:600">
                                    ${parseFloat(p.price).toFixed(2)} грн
                                </span>
                            </a>
                        `).join('');
                    }
                    searchResults.classList.add('active');
                })
                .catch(() => {
                    searchResults.classList.remove('active');
                });
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
});