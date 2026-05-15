<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="/project1/public/css/style.css">
    <!-- Thêm icon từ Phosphor Icons để giao diện đẹp hơn -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="container">
        <h1>Quản lý Sản phẩm</h1>
        <div class="header-actions">
            <a href="/project1/Product/add" class="btn btn-primary btn-water">
                <i class="ph ph-plus-circle" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                Thêm sản phẩm mới
            </a>
            <a href="/project1/" class="btn btn-outline btn-water" style="margin-left: 1rem;">
                <i class="ph ph-house" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                Trang chủ
            </a>
        </div>

        <div class="glass-panel" style="padding: 1.5rem 2rem; margin-top: 1rem; margin-bottom: 2.5rem;">
            <form method="GET" action="/project1/Product/list" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <div style="flex-grow: 1; min-width: 250px;">
                    <div style="position: relative;">
                        <i class="ph ph-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.2rem;"></i>
                        <input type="text" name="search" placeholder="Tìm kiếm tên, mô tả..." value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '', ENT_QUOTES, 'UTF-8'); ?>" style="padding-left: 2.75rem; margin-bottom: 0;">
                    </div>
                </div>
                <div style="min-width: 200px;">
                    <select name="category_filter" style="width: 100%; padding: 1rem 1.25rem; background: rgba(9, 9, 11, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: var(--text-main); font-family: inherit; font-size: 1rem; transition: var(--transition);">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->getID(); ?>" <?php echo (isset($_GET['category_filter']) && $_GET['category_filter'] == $category->getID()) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-water" style="padding: 1rem 2rem;">
                    <i class="ph ph-funnel" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                    Lọc
                </button>
                <?php if (!empty($_GET['search']) || !empty($_GET['category_filter'])): ?>
                    <a href="/project1/Product/list" class="btn btn-outline btn-water" style="padding: 1rem 1.5rem;" title="Xóa bộ lọc">
                        <i class="ph ph-x"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (empty($products)): ?>
            <div class="empty-state">
                <i class="ph ph-package" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <?php if (!empty($_GET['search']) || !empty($_GET['category_filter'])): ?>
                    <p>Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
                <?php else: ?>
                    <p>Chưa có sản phẩm nào. Hãy thêm sản phẩm đầu tiên của bạn!</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <?php if ($product->getImage()): ?>
                            <div class="product-image" style="margin-bottom: 1.25rem; border-radius: 12px; overflow: hidden; display: flex; justify-content: center; align-items: center; background: rgba(0,0,0,0.2); height: 200px;">
                                <img src="/project1/public/images/<?php echo htmlspecialchars($product->getImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php else: ?>
                            <div class="product-image-placeholder" style="margin-bottom: 1.25rem; border-radius: 12px; height: 200px; display: flex; justify-content: center; align-items: center; background: rgba(255,255,255,0.03); color: var(--text-muted); border: 1px dashed rgba(255,255,255,0.1);">
                                <i class="ph ph-image" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        <?php endif; ?>
                        <?php 
                        $catName = 'Chưa phân loại';
                        foreach ($categories as $cat) {
                            if ($cat->getID() == $product->getCategoryID()) {
                                $catName = $cat->getName();
                                break;
                            }
                        }
                        ?>
                        <div style="margin-bottom: 0.5rem;">
                            <span style="background: rgba(139, 92, 246, 0.2); color: #c4b5fd; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 500; border: 1px solid rgba(139, 92, 246, 0.3);">
                                <?php echo htmlspecialchars($catName, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        <h2><?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="product-desc"><?php echo nl2br(htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8')); ?></p>
                        <div class="product-price">
                            <span style="font-size: 1rem; color: var(--text-muted); font-weight: normal;">$</span>
                            <?php echo number_format($product->getPrice(), 0, ',', '.'); ?>
                        </div>
                        <div class="product-actions">
                            <a href="/project1/Product/edit/<?php echo $product->getID(); ?>" class="btn btn-outline btn-water">
                                <i class="ph ph-pencil-simple" style="margin-right: 0.25rem;"></i> Sửa
                            </a>
                            <a href="/project1/Product/delete/<?php echo $product->getID(); ?>" class="btn btn-danger btn-water" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
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