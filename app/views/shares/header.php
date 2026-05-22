<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') : 'Bán hàng đa cấp'; ?></title>
    <link rel="stylesheet" href="/TH-MNM/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <header class="site-header glass-panel" style="padding: 1rem 1.5rem; margin-top: 0; margin-bottom: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <a href="/TH-MNM/" style="display:flex; align-items:center; gap:.65rem; color:var(--text-main); font-weight:700; font-size:1.1rem;">
                <i class="ph ph-storefront" style="font-size:1.4rem;"></i>
                Bán hàng đa cấp
            </a>
            <nav style="display:flex; gap:.5rem; flex-wrap:wrap;">
                <a class="btn btn-outline btn-water" href="/TH-MNM/"><i class="ph ph-house"></i>&nbsp;Trang chủ</a>
                <a class="btn btn-outline btn-water" href="/TH-MNM/Product/list"><i class="ph ph-package"></i>&nbsp;Sản phẩm</a>
                <a class="btn btn-outline btn-water" href="/TH-MNM/Category/list"><i class="ph ph-squares-four"></i>&nbsp;Danh mục</a>
                <button type="button" id="themeToggleBtn" class="btn btn-outline btn-water" style="min-width: 136px;">
                    <i class="ph ph-moon-stars"></i>&nbsp;<span>Dark mode</span>
                </button>
            </nav>
        </div>
    </header>
    <script>
        (function () {
            var root = document.body;
            var storageKey = 'thmnm-theme';
            var toggleBtn = document.getElementById('themeToggleBtn');

            function applyTheme(theme) {
                if (theme === 'light') {
                    root.classList.add('light-theme');
                } else {
                    root.classList.remove('light-theme');
                }

                if (toggleBtn) {
                    var isLight = root.classList.contains('light-theme');
                    toggleBtn.innerHTML = isLight
                        ? '<i class="ph ph-sun-dim"></i>&nbsp;<span>Light mode</span>'
                        : '<i class="ph ph-moon-stars"></i>&nbsp;<span>Dark mode</span>';
                }
            }

            var saved = localStorage.getItem(storageKey);
            if (saved === 'light' || saved === 'dark') {
                applyTheme(saved);
            } else {
                applyTheme('dark');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    var next = root.classList.contains('light-theme') ? 'dark' : 'light';
                    applyTheme(next);
                    localStorage.setItem(storageKey, next);
                });
            }
        })();
    </script>
