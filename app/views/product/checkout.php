<?php $pageTitle = 'Thanh toán'; include 'app/views/shares/header.php'; ?>

<div class="container" style="max-width: 900px;">
    <h1>Thông tin đặt hàng</h1>

    <?php
        $grandTotal = 0;
        foreach ($cart as $item) {
            $grandTotal += (float)$item['price'] * (int)$item['quantity'];
        }
    ?>

    <div class="glass-panel" style="margin-bottom: 1rem;">
        <h3 style="margin-bottom: .75rem;">Tóm tắt đơn hàng</h3>
        <div style="display: grid; gap: .5rem;">
            <?php foreach ($cart as $item): ?>
                <div style="display:flex; justify-content:space-between; gap:1rem;">
                    <span><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> x <?php echo (int)$item['quantity']; ?></span>
                    <strong><?php echo number_format((float)$item['price'] * (int)$item['quantity'], 0, ',', '.'); ?> đ</strong>
                </div>
            <?php endforeach; ?>
        </div>
        <hr style="margin: 1rem 0; border-color: rgba(255,255,255,0.15);">
        <div style="display:flex; justify-content:space-between;">
            <span>Tổng thanh toán</span>
            <strong><?php echo number_format($grandTotal, 0, ',', '.'); ?> đ</strong>
        </div>
    </div>

    <form method="POST" action="/TH-MNM/Product/processCheckout" class="glass-panel">
        <div class="form-group">
            <label for="name">Họ và tên</label>
            <input id="name" name="name" type="text" required placeholder="Nhập họ tên người nhận">
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input id="phone" name="phone" type="text" required placeholder="Nhập số điện thoại liên hệ">
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ nhận hàng</label>
            <textarea id="address" name="address" required placeholder="Nhập địa chỉ nhận hàng"></textarea>
        </div>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary btn-water">
                <i class="ph ph-check-circle"></i>&nbsp; Xác nhận đặt hàng
            </button>
            <a href="/TH-MNM/Product/cart" class="btn btn-outline btn-water">
                <i class="ph ph-arrow-left"></i>&nbsp; Quay lại giỏ hàng
            </a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>
