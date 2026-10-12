/**
 * Admin Panel JavaScript Module (admin.js)
 * Clean interactive utilities for sidebar, search, filters, image preview, alerts
 */
document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // 1. Mobile Sidebar Drawer & Backdrop
    // -------------------------------------------------------------------------
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminBackdrop');
    const toggleBtn = document.getElementById('adminSidebarToggle');

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('open');
        backdrop?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('open');
        backdrop?.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggleBtn?.addEventListener('click', () => {
        if (sidebar?.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    backdrop?.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar?.classList.contains('open')) {
            closeSidebar();
        }
    });

    // Close mobile sidebar when clicking a link inside it on narrow screens
    sidebar?.querySelectorAll('.admin-nav__item').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                closeSidebar();
            }
        });
    });

    // -------------------------------------------------------------------------
    // 2. Alert Dismissal & Auto-Fadeout
    // -------------------------------------------------------------------------
    document.querySelectorAll('.admin-alert').forEach(alert => {
        const closeBtn = alert.querySelector('.admin-alert__close');
        
        function dismiss() {
            alert.style.transition = 'opacity 0.25s ease, transform 0.25s ease, margin 0.25s ease, max-height 0.25s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-6px)';
            setTimeout(() => alert.remove(), 260);
        }

        closeBtn?.addEventListener('click', dismiss);

        // Auto dismiss after 5 seconds if not hovered
        let timeout = setTimeout(dismiss, 5000);
        alert.addEventListener('mouseenter', () => clearTimeout(timeout));
    });

    // -------------------------------------------------------------------------
    // 3. Live Table Search (Real-time filtering without page reloads)
    // -------------------------------------------------------------------------
    document.querySelectorAll('[data-admin-search]').forEach(input => {
        const targetSelector = input.getAttribute('data-admin-search');
        const table = document.querySelector(targetSelector);
        if (!table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        input.addEventListener('input', () => {
            const query = input.value.trim().toLowerCase();
            const rows = tbody.querySelectorAll('tr:not(.admin-table-empty-row)');
            let visibleCount = 0;

            rows.forEach(row => {
                // If row is a child row or reply form row, handle accordingly
                if (row.hasAttribute('data-ignore-search')) return;

                const text = row.innerText.toLowerCase();
                const matches = !query || text.includes(query);
                row.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
            });

            // Empty state row if no results match
            let emptyMsgRow = tbody.querySelector('.admin-table-empty-row');
            if (visibleCount === 0 && query) {
                if (!emptyMsgRow) {
                    emptyMsgRow = document.createElement('tr');
                    emptyMsgRow.className = 'admin-table-empty-row';
                    const colCount = table.querySelectorAll('thead th').length || 6;
                    emptyMsgRow.innerHTML = `<td colspan="${colCount}" style="text-align:center;padding:32px;color:var(--admin-text-muted);">Нічого не знайдено за запитом "<strong>${escapeHtml(query)}</strong>"</td>`;
                    tbody.appendChild(emptyMsgRow);
                } else {
                    emptyMsgRow.style.display = '';
                    emptyMsgRow.querySelector('td').innerHTML = `Нічого не знайдено за запитом "<strong>${escapeHtml(query)}</strong>"`;
                }
            } else if (emptyMsgRow) {
                emptyMsgRow.style.display = 'none';
            }
        });
    });

    // -------------------------------------------------------------------------
    // 4. Status Filter Pills for Tables
    // -------------------------------------------------------------------------
    document.querySelectorAll('[data-filter-group]').forEach(group => {
        const targetSelector = group.getAttribute('data-filter-group');
        const table = document.querySelector(targetSelector);
        if (!table) return;

        const buttons = group.querySelectorAll('.admin-filter-btn');
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter') || 'all';
                const rows = table.querySelectorAll('tbody tr:not(.admin-table-empty-row)');

                rows.forEach(row => {
                    const rowStatus = row.getAttribute('data-status');
                    if (filter === 'all' || rowStatus === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });

    // -------------------------------------------------------------------------
    // 5. Live Image Preview
    // -------------------------------------------------------------------------
    document.querySelectorAll('[data-image-preview-input]').forEach(input => {
        const previewBoxId = input.getAttribute('data-image-preview-input');
        const box = document.getElementById(previewBoxId);
        if (!box) return;

        const img = box.querySelector('img');
        const placeholder = box.querySelector('.admin-image-preview-placeholder');

        function updatePreview() {
            const url = input.value.trim();
            if (url) {
                if (img) {
                    img.src = url;
                    img.style.display = 'block';
                    img.onerror = () => {
                        img.style.display = 'none';
                        if (placeholder) placeholder.style.display = 'flex';
                    };
                    img.onload = () => {
                        img.style.display = 'block';
                        if (placeholder) placeholder.style.display = 'none';
                    };
                }
                if (placeholder) placeholder.style.display = 'none';
            } else {
                if (img) {
                    img.src = '';
                    img.style.display = 'none';
                }
                if (placeholder) placeholder.style.display = 'flex';
            }
        }

        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    function escapeHtml(str) {
        return str.replace(/[&<>'"]/g, tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag] || tag));
    }
});
