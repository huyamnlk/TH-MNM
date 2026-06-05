<?php
$pageTitle = 'Trang trưng bày sản phẩm';
include 'app/views/shares/header.php';

require_once 'app/config/database.php';
$conn = Database::getConnection();

$search = trim($_GET['search'] ?? '');
$categoryFilter = trim($_GET['category_filter'] ?? '');

$catStmt = $conn->query("SELECT id, name FROM category ORDER BY name ASC");
$categories = $catStmt->fetchAll();

$sql = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name AS category_name
        FROM product p
        LEFT JOIN category c ON p.category_id = c.id
        WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
    $params['search'] = '%' . $search . '%';
}

if ($categoryFilter !== '') {
    $sql .= " AND p.category_id = :category_id";
    $params['category_id'] = (int)$categoryFilter;
}

$sql .= " ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="container">
    <section class="glass-panel" style="padding: 2rem; margin-bottom: 1.2rem; text-align: center;">
        <h1 style="margin-bottom: .5rem;">Chào mừng đến với cửa hàng</h1>
        <p style="color: var(--text-muted); max-width: 760px; margin: 0 auto;">
            Khám phá các sản phẩm nổi bật, thêm vào giỏ hàng và tiến hành đặt hàng trực tiếp ngay trên website.
        </p>
    </section>

    <div class="glass-panel" style="padding: 1.5rem 2rem; margin-bottom: 1.5rem;">
        <form method="GET" action="/TH-MNM/" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <div style="flex-grow: 1; min-width: 250px;">
                <div style="position: relative;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.2rem;"></i>
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" style="padding-left: 2.75rem; margin-bottom: 0;">
                </div>
            </div>
            <div style="min-width: 220px;">
                <select name="category_filter" style="width: 100%; padding: 1rem 1.25rem; background: rgba(9, 9, 11, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: var(--text-main); font-family: inherit; font-size: 1rem; transition: var(--transition);">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo (int)$category['id']; ?>" <?php echo ($categoryFilter !== '' && (int)$categoryFilter === (int)$category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-outline btn-water">
                <i class="ph ph-funnel"></i>&nbsp;Lọc
            </button>
            <?php if ($search !== '' || $categoryFilter !== ''): ?>
                <a href="/TH-MNM/" class="btn btn-outline btn-water" title="Xóa bộ lọc">
                    <i class="ph ph-x"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <i class="ph ph-package" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Không có sản phẩm phù hợp.</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <?php if (!empty($product['image'])): ?>
                        <div class="product-image" style="margin-bottom: 1.25rem; border-radius: 12px; overflow: hidden; display: flex; justify-content: center; align-items: center; background: rgba(0,0,0,0.2); height: 200px;">
                            <img src="/TH-MNM/public/images/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php else: ?>
                        <div class="product-image-placeholder" style="margin-bottom: 1.25rem; border-radius: 12px; height: 200px; display: flex; justify-content: center; align-items: center; background: rgba(255,255,255,0.03); color: var(--text-muted); border: 1px dashed rgba(255,255,255,0.1);">
                            <i class="ph ph-image" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                    <?php endif; ?>

                    <div style="margin-bottom: 0.5rem;">
                        <span style="background: rgba(139, 92, 246, 0.2); color: #c4b5fd; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 500; border: 1px solid rgba(139, 92, 246, 0.3);">
                            <?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>

                    <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="product-desc"><?php echo nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                    <div class="product-price"><?php echo number_format((float)$product['price'], 0, ',', '.'); ?> đ</div>

                    <div class="product-actions">
                        <a href="/TH-MNM/Product/addToCart/<?php echo (int)$product['id']; ?>" class="btn btn-primary btn-water btn-sm-card">
                            <i class="ph ph-shopping-cart-simple" style="margin-right: 0.18rem;"></i> Thêm vào giỏ
                        </a>
                        <a href="/TH-MNM/Product/show/<?php echo (int)$product['id']; ?>" class="btn btn-outline btn-water btn-sm-card">
                            <i class="ph ph-eye" style="margin-right: 0.18rem;"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
