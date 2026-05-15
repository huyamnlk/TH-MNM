<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Quản lý Cửa hàng</title>
    <link rel="stylesheet" href="/project1/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
            text-align: center;
        }
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
            max-width: 600px;
        }
        .dashboard-cards {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .nav-card {
            background: rgba(24, 24, 27, 0.5);
            backdrop-filter: blur(16px);
            border: 1px solid var(--surface-border);
            border-radius: var(--border-radius);
            padding: 3rem;
            width: 320px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-main);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .nav-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(139, 92, 246, 0.2) 0%, transparent 70%);
            opacity: 0;
            transition: var(--transition);
        }
        .nav-card:hover::before {
            opacity: 1;
        }
        .nav-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5), 0 0 20px rgba(139, 92, 246, 0.2);
            border-color: rgba(255,255,255,0.2);
            background: rgba(39, 39, 42, 0.7);
        }
        .nav-card i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .nav-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container hero">
        <h1>Quản lý Cửa hàng</h1>
        <p>Hệ thống quản lý sản phẩm và danh mục với giao diện hiện đại, tối ưu trải nghiệm người dùng.</p>
        
        <div class="dashboard-cards">
            <a href="/project1/Category/list" class="nav-card">
                <i class="ph ph-squares-four"></i>
                <h3>Quản lý Danh mục</h3>
                <p>Thêm, sửa, xóa và tổ chức các danh mục sản phẩm</p>
            </a>
            
            <a href="/project1/Product/list" class="nav-card">
                <i class="ph ph-package"></i>
                <h3>Quản lý Sản phẩm</h3>
                <p>Quản lý kho hàng, giá cả và hình ảnh sản phẩm</p>
            </a>
        </div>
    </div>
</body>
</html>
