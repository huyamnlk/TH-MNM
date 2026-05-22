<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="/TH-MNM/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="container-sm">
        <h1>Sửa sản phẩm</h1>
        
        <div class="glass-panel">
            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/TH-MNM/Product/edit/<?php echo (int)$product['id']; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="category_id">Danh mục sản phẩm</label>
                    <select id="category_id" name="category_id" style="width: 100%; padding: 1rem 1.25rem; background: rgba(9, 9, 11, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: var(--text-main); font-family: inherit; font-size: 1rem; transition: var(--transition); margin-bottom: 0.5rem;" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php
                        $current_cat_id = isset($_POST['category_id']) ? $_POST['category_id'] : ($product['category_id'] ?? '');
                        foreach ($categories as $category): ?>
                            <option value="<?php echo (int)$category['id']; ?>" <?php echo ($current_cat_id == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="name">Tên sản phẩm</label>
                    <input type="text" id="name" name="name" placeholder="Nhập tên sản phẩm..." value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Hình ảnh hiện tại</label>
                    <?php if (!empty($product['image'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="/TH-MNM/public/images/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Product Image" style="max-width: 150px; border-radius: 8px;">
                        </div>
                    <?php else: ?>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;">Chưa có hình ảnh</p>
                    <?php endif; ?>
                    <label for="image">Thay đổi hình ảnh (chọn file mới nếu muốn đổi)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả chi tiết</label>
                    <textarea id="description" name="description" placeholder="Nhập mô tả sản phẩm..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Giá sản phẩm ($)</label>
                    <input type="number" id="price" name="price" placeholder="Ví dụ: 150000" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-water" style="width: 100%;">
                    <i class="ph ph-floppy-disk" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                    Lưu thay đổi
                </button>
            </form>
        </div>

        <div style="text-align: center;">
            <a href="/TH-MNM/Product/list" class="back-link">
                <i class="ph ph-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</body>
</html>
