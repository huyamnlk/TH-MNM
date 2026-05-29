<?php $pageTitle = 'Đặt hàng thành công'; include 'app/views/shares/header.php'; ?>

<div class="container" style="max-width: 900px;">
    <div class="glass-panel" style="text-align: center; padding: 2rem;">
        <i class="ph ph-check-circle" style="font-size: 4rem; color: #4ade80; margin-bottom: 1rem; display: inline-block;"></i>
        <h1 style="margin-bottom: .75rem;">Đặt hàng thành công</h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận và giỏ hàng đã được làm mới.
        </p>

        <div style="display:flex; justify-content:center; gap:.75rem; flex-wrap:wrap;">
            <a href="/TH-MNM/" class="btn btn-primary btn-water">
                <i class="ph ph-house"></i>&nbsp; Về trang trưng bày
            </a>
            <a href="/TH-MNM/Product/list" class="btn btn-outline btn-water">
                <i class="ph ph-package"></i>&nbsp; Xem thêm sản phẩm
            </a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
