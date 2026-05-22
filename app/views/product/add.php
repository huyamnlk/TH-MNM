<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm mới</title>
    <link rel="stylesheet" href="/TH-MNM/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="container-sm">
        <h1>Thêm sản phẩm mới</h1>
        
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

            <form method="POST" action="/TH-MNM/Product/add" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="category_id">Danh mục sản phẩm</label>
                    <select id="category_id" name="category_id" style="width: 100%; padding: 1rem 1.25rem; background: rgba(9, 9, 11, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: var(--text-main); font-family: inherit; font-size: 1rem; transition: var(--transition); margin-bottom: 0.5rem;" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo (int)$category['id']; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="name">Tên sản phẩm</label>
                    <input type="text" id="name" name="name" placeholder="Nhập tên sản phẩm..." value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="image">Hình ảnh sản phẩm</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả chi tiết</label>
                    <textarea id="description" name="description" placeholder="Nhập mô tả sản phẩm..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Giá sản phẩm ($)</label>
                    <input type="number" id="price" name="price" placeholder="Ví dụ: 150000" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-water" style="width: 100%;">
                    <i class="ph ph-plus-circle" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                    Thêm sản phẩm
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

