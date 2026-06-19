<?php
require_once 'app/helpers/SessionHelper.php';

$isLoggedIn = SessionHelper::isLoggedIn();
$isAdmin = SessionHelper::isAdmin();
$role = SessionHelper::getRole();
$displayName = $_SESSION['fullname'] ?? ($_SESSION['username'] ?? 'Khách');

$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $ci) {
        $cartCount += (int) ($ci['quantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') : 'Bán hàng đa cấp'; ?>
    </title>
    <link rel="stylesheet" href="/TH-MNM/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body>
    <header class="site-header glass-panel" style="padding: 1rem 1.5rem; margin-top: 0; margin-bottom: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <a href="/TH-MNM/"
                style="display:flex; align-items:center; gap:.65rem; color:var(--text-main); font-weight:700; font-size:1.1rem;">
                <i class="ph ph-storefront" style="font-size:1.4rem;"></i>
                Bán hàng đa cấp
            </a>

            <nav style="display:flex; gap:.5rem; flex-wrap:wrap; align-items:center;">
                <a class="btn btn-outline btn-water" href="/TH-MNM/"><i class="ph ph-house"></i>&nbsp;Trang chủ</a>
                <a class="btn btn-outline btn-water" href="/TH-MNM/Product/list"><i class="ph ph-package"></i>&nbsp;Sản
                    phẩm</a>

                <?php if ($isAdmin): ?>
                    <a class="btn btn-outline btn-water" href="/TH-MNM/Category/list"><i
                            class="ph ph-squares-four"></i>&nbsp;Danh mục</a>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <a class="btn btn-primary btn-water" href="/TH-MNM/Product/cart">
                        <i class="ph ph-shopping-cart"></i>&nbsp;Giỏ hàng
                        <span
                            style="display:inline-flex; min-width:22px; height:22px; padding:0 .45rem; border-radius:999px; align-items:center; justify-content:center; margin-left:.35rem; background:rgba(255,255,255,.2); font-size:.82rem;">
                            <?php echo (int) $cartCount; ?>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if (!$isLoggedIn): ?>
                    <a class="btn btn-outline btn-water" href="/TH-MNM/Account/login"><i
                            class="ph ph-sign-in"></i>&nbsp;Đăng nhập</a>
                    <a class="btn btn-primary btn-water" href="/TH-MNM/Account/register"><i
                            class="ph ph-user-plus"></i>&nbsp;Đăng ký</a>
                <?php else: ?>
                    <span class="btn btn-outline btn-water" style="pointer-events:none;">
                        <i
                            class="ph ph-user-circle"></i>&nbsp;<?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
                        <span
                            style="margin-left:.4rem; padding:.1rem .45rem; border-radius:999px; background:rgba(139,92,246,.25); border:1px solid rgba(139,92,246,.4); font-size:.75rem;">
                            <?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </span>
                    <a class="btn btn-danger btn-water" href="javascript:logout()"><i
                            class="ph ph-sign-out"></i>&nbsp;Đăng xuất</a>
                <?php endif; ?>

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
    <script>
        function logout() {
            localStorage.removeItem('jwtToken');
            location.href = '/TH-MNM/Account/logout';
        }
        document.addEventListener("DOMContentLoaded", function () {
            const token = localStorage.getItem('jwtToken');
            if (token) {
                document.getElementById('nav-login').style.display = 'none';
                document.getElementById('nav-logout').style.display = 'block';
            } else {
                document.getElementById('nav-login').style.display = 'block';
                document.getElementById('nav-logout').style.display = 'none';
            }
        });
    </script>
    <div class="container mt-4"></div>