<?php
$pageTitle = 'Đăng nhập';
include 'app/views/shares/header.php';
?>

<div class="container" style="max-width: 560px;">
    <div class="glass-panel" style="padding: 2rem;">
        <h1 style="margin-bottom: 0.5rem;">Đăng nhập</h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Đăng nhập để mua hàng và quản lý tài khoản.</p>

        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="glass-panel" style="border: 1px solid rgba(74, 222, 128, 0.5); background: rgba(20, 83, 45, 0.2); color: #bbf7d0; margin-bottom: 1rem;">
                <?php
                    echo htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8');
                    unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="glass-panel" style="border: 1px solid rgba(248, 113, 113, 0.5); background: rgba(127, 29, 29, 0.2); color: #fecaca; margin-bottom: 1rem;">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="/TH-MNM/Account/checkLogin" method="post">
            <div style="margin-bottom: 1rem;">
                <label for="username">Tên đăng nhập</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo htmlspecialchars($oldUsername ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="Nhập username"
                    required
                >
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="password">Mật khẩu</label>
                <input
                    type="text"
                    id="password"
                    name="password"
                    placeholder="Nhập mật khẩu"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-water" style="width: 100%;">
                <i class="ph ph-sign-in"></i>&nbsp;Đăng nhập
            </button>
        </form>

        <p style="margin-top: 1rem; color: var(--text-muted);">
            Chưa có tài khoản?
            <a href="/TH-MNM/Account/register" style="color: var(--text-main); font-weight: 600;">Đăng ký ngay</a>
        </p>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
