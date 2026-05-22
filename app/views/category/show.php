<?php $pageTitle = 'Chi tiết danh mục'; include 'app/views/shares/header.php'; ?>

<div class="container-sm">
    <div class="glass-panel">
        <div class="product-image-placeholder" style="margin-bottom:1rem; height:120px; border-radius:16px; color:var(--primary-color);">
            <i class="ph ph-squares-four" style="font-size:3.5rem;"></i>
        </div>

        <h1 style="margin-bottom:.75rem;"><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="product-desc" style="margin-bottom:1.2rem;"><?php echo nl2br(htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8')); ?></p>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
            <a href="/TH-MNM/Category/edit/<?php echo (int)$category['id']; ?>" class="btn btn-primary btn-water"><i class="ph ph-pencil-simple"></i>&nbsp;Sửa</a>
            <a href="/TH-MNM/Category/delete/<?php echo (int)$category['id']; ?>" class="btn btn-danger btn-water" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');"><i class="ph ph-trash"></i>&nbsp;Xóa</a>
            <a href="/TH-MNM/Category/list" class="btn btn-outline btn-water"><i class="ph ph-arrow-left"></i>&nbsp;Quay lại</a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
