<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Danh mục</title>
    <link rel="stylesheet" href="/project1/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="container">
        <h1>Quản lý Danh mục</h1>
        <div class="header-actions">
            <a href="/project1/Category/add" class="btn btn-primary btn-water">
                <i class="ph ph-plus-circle" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                Thêm danh mục mới
            </a>
            <a href="/project1/" class="btn btn-outline btn-water" style="margin-left: 1rem;">
                <i class="ph ph-house" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                Trang chủ
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
                        <h2><?php echo htmlspecialchars($category->getName(), ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="product-desc"><?php echo nl2br(htmlspecialchars($category->getDescription(), ENT_QUOTES, 'UTF-8')); ?></p>
                        
                        <div class="product-actions">
                            <a href="/project1/Category/edit/<?php echo $category->getID(); ?>" class="btn btn-outline btn-water">
                                <i class="ph ph-pencil-simple" style="margin-right: 0.25rem;"></i> Sửa
                            </a>
                            <a href="/project1/Category/delete/<?php echo $category->getID(); ?>" class="btn btn-danger btn-water" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                <i class="ph ph-trash" style="margin-right: 0.25rem;"></i> Xóa
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
