<?php
$pageTitle = 'Đăng ký';
include 'app/views/shares/header.php';
?>

<div class="container" style="max-width: 640px;">
    <div class="glass-panel" style="padding: 2rem;">
        <h1 style="margin-bottom: 0.5rem;">Đăng ký tài khoản</h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Tạo tài khoản mới để sử dụng hệ thống.</p>

        <?php if (!empty($errors)): ?>
            <div class="glass-panel" style="border: 1px solid rgba(248, 113, 113, 0.5); background: rgba(127, 29, 29, 0.2); color: #fecaca; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.1rem;">
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/TH-MNM/Account/save" method="post">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="username">Tên đăng nhập</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Username"
                        value="<?php echo htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="fullname">Họ và tên</label>
                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        placeholder="Họ tên"
                        value="<?php echo htmlspecialchars($old['fullname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="phone">Số điện thoại</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="Số điện thoại"
                        value="<?php echo htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="password">Mật khẩu</label>
                    <input
                        type="text"
                        id="password"
                        name="password"
                        placeholder="Mật khẩu"
                        required
                    >
                </div>

                <div>
                    <label for="confirmpassword">Xác nhận mật khẩu</label>
                    <input
                        type="text"
                        id="confirmpassword"
                        name="confirmpassword"
                        placeholder="Nhập lại mật khẩu"
                        required
                    >
                </div>
            </div>

            <div style="margin-top: 1rem; margin-bottom: 1.25rem;">
                <label for="role">Vai trò</label>
                <select id="role" name="role" style="width: 100%; padding: 1rem 1.25rem; background: rgba(9, 9, 11, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: var(--text-main); font-family: inherit; font-size: 1rem;">
                    <option value="user" <?php echo (($old['role'] ?? 'user') === 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo (($old['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-water" style="width: 100%;">
                <i class="ph ph-user-plus"></i>&nbsp;Đăng ký
            </button>
        </form>

        <p style="margin-top: 1rem; color: var(--text-muted);">
            Đã có tài khoản?
            <a href="/TH-MNM/Account/login" style="color: var(--text-main); font-weight: 600;">Đăng nhập</a>
        </p>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
