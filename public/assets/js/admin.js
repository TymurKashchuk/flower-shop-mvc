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

    // -------------------------------------------------------------------------
    // 6. Review Reply Modal with Blurred Backdrop
    // -------------------------------------------------------------------------
    const replyModal = document.getElementById('reviewReplyModal');
    const replyBackdrop = document.getElementById('reviewReplyBackdrop');
    const replyForm = document.getElementById('reviewReplyForm');
    const replyAuthor = document.getElementById('modalReplyAuthor');
    const replyProduct = document.getElementById('modalReplyProduct');
    const replyRating = document.getElementById('modalReplyRating');
    const replyQuote = document.getElementById('modalReplyQuote');
    const replyTextarea = document.getElementById('modalReplyTextarea');

    function openReplyModal(btn) {
        if (!replyModal || !replyBackdrop) return;

        const userName = btn.getAttribute('data-user-name') || 'Анонім';
        const productName = btn.getAttribute('data-product-name') || '—';
        const rating = parseInt(btn.getAttribute('data-rating') || '5', 10);
        const reviewText = btn.getAttribute('data-review-text') || '';
        const replyText = btn.getAttribute('data-reply-text') || '';
        const actionUrl = btn.getAttribute('data-action-url') || '';

        if (replyForm) {
            replyForm.action = actionUrl;
        }

        if (replyAuthor) replyAuthor.textContent = userName;
        if (replyProduct) replyProduct.textContent = productName;
        if (replyRating) {
            replyRating.innerHTML = Array.from({length: 5}, (_, i) => `
                <svg viewBox="0 0 20 20" fill="${i < rating ? 'currentColor' : '#e2e8f0'}" width="15" height="15" style="vertical-align:middle">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            `).join('');
        }
        if (replyQuote) replyQuote.textContent = reviewText;
        if (replyTextarea) {
            replyTextarea.value = replyText;
        }

        replyBackdrop.classList.add('active');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            replyTextarea?.focus();
            if (replyTextarea) {
                replyTextarea.selectionStart = replyTextarea.selectionEnd = replyTextarea.value.length;
            }
        }, 150);
    }

    function closeReplyModal() {
        if (!replyBackdrop) return;
        replyBackdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.btn-open-reply-modal').forEach(btn => {
        btn.addEventListener('click', () => openReplyModal(btn));
    });

    document.querySelectorAll('[data-close-reply-modal]').forEach(btn => {
        btn.addEventListener('click', closeReplyModal);
    });

    replyBackdrop?.addEventListener('click', (e) => {
        if (e.target === replyBackdrop) {
            closeReplyModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && replyBackdrop?.classList.contains('active')) {
            closeReplyModal();
        }
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

