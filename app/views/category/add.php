<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Danh mục mới</title>
    <link rel="stylesheet" href="/TH-MNM/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="container-sm">
        <h1>Thêm Danh mục mới</h1>
        
        <div class="glass-panel">
            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <i class="ph ph-warning-circle" style="font-size: 1.5rem;"></i>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/TH-MNM/Category/add">
                <div class="form-group">
                    <label for="name">Tên danh mục</label>
                    <input type="text" id="name" name="name" placeholder="Ví dụ: Điện thoại, Laptop..." value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả chi tiết</label>
                    <textarea id="description" name="description" placeholder="Nhập mô tả danh mục..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-water" style="width: 100%;">
                    <i class="ph ph-plus-circle" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                    Thêm danh mục
                </button>
            </form>
        </div>

        <div style="text-align: center;">
            <a href="/TH-MNM/Category/list" class="back-link">
                <i class="ph ph-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</body>
</html>

