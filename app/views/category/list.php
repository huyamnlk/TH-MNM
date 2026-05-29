<?php $pageTitle = 'Quản lý Danh mục'; include 'app/views/shares/header.php'; ?>

<div class="container">
    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="glass-panel" style="border: 1px solid rgba(248, 113, 113, 0.5); background: rgba(127, 29, 29, 0.2); color: #fecaca; margin-bottom: 1rem;">
            <?php
                echo htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8');
                unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="glass-panel" style="border: 1px solid rgba(74, 222, 128, 0.5); background: rgba(20, 83, 45, 0.2); color: #bbf7d0; margin-bottom: 1rem;">
            <?php
                echo htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8');
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>
    <h1>Quản lý Danh mục</h1>
    <div class="header-actions">
        <a href="/TH-MNM/Category/add" class="btn btn-primary btn-water">
            <i class="ph ph-plus-circle" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
            Thêm danh mục mới
        </a>
    </div>

    <?php if (empty($categories)): ?>
        <div class="empty-state">
            <i class="ph ph-squares-four" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Chưa có danh mục nào. Hãy thêm danh mục đầu tiên của bạn!</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($categories as $category): ?>
                <div class="product-card">
                    <div class="product-image-placeholder" style="margin-bottom: 1.25rem; border-radius: 12px; height: 100px; display: flex; justify-content: center; align-items: center; background: rgba(255,255,255,0.03); color: var(--primary-color);">
                        <i class="ph ph-squares-four" style="font-size: 3rem;"></i>
                    </div>
                    <h2><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="product-desc"><?php echo nl2br(htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8')); ?></p>

                    <div class="product-actions">
                        <a href="/TH-MNM/Category/show/<?php echo (int)$category['id']; ?>" class="btn btn-outline btn-water">
                            <i class="ph ph-eye" style="margin-right: 0.25rem;"></i> Xem
                        </a>
                        <a href="/TH-MNM/Category/edit/<?php echo (int)$category['id']; ?>" class="btn btn-outline btn-water">
                            <i class="ph ph-pencil-simple" style="margin-right: 0.25rem;"></i> Sửa
                        </a>
                        <a href="/TH-MNM/Category/delete/<?php echo (int)$category['id']; ?>" class="btn btn-danger btn-water" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                            <i class="ph ph-trash" style="margin-right: 0.25rem;"></i> Xóa
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
