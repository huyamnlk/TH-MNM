<?php $pageTitle = 'Chi tiết sản phẩm'; include 'app/views/shares/header.php'; ?>

<div class="container">
    <div class="glass-panel">
        <div style="display:grid; grid-template-columns: minmax(250px, 360px) 1fr; gap:2rem; align-items:start;">
            <div>
                <?php if (!empty($product['image'])): ?>
                    <img src="/TH-MNM/public/images/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width:100%; border-radius:16px; object-fit:cover; box-shadow:0 10px 30px rgba(0,0,0,.35);">
                <?php else: ?>
                    <div class="product-image-placeholder" style="height:260px; border-radius:16px; display:flex; justify-content:center; align-items:center;">
                        <i class="ph ph-image" style="font-size:4rem; opacity:.4;"></i>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <span style="display:inline-block; margin-bottom:.75rem; background: rgba(139, 92, 246, 0.2); color:#c4b5fd; padding:.3rem .75rem; border-radius:20px; font-size:.85rem; border:1px solid rgba(139, 92, 246, 0.3);">
                    <?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <h1 style="text-align:left; margin-bottom:.75rem;"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <div class="product-price" style="margin-bottom:1rem;"><?php echo number_format((float)$product['price'], 0, ',', '.'); ?> đ</div>
                <p class="product-desc" style="font-size:1.05rem;"><?php echo nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')); ?></p>

                <div style="display:flex; gap:.75rem; flex-wrap:wrap; margin-top:1.5rem;">
                    <a href="/TH-MNM/Product/edit/<?php echo (int)$product['id']; ?>" class="btn btn-primary btn-water"><i class="ph ph-pencil-simple"></i>&nbsp;Sửa</a>
                    <a href="/TH-MNM/Product/delete/<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-water" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');"><i class="ph ph-trash"></i>&nbsp;Xóa</a>
                    <a href="/TH-MNM/Product/list" class="btn btn-outline btn-water"><i class="ph ph-arrow-left"></i>&nbsp;Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
