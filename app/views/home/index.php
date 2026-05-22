<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bán hàng đa cấp - Quản lý bán hàng chuyên nghiệp</title>
    <link rel="stylesheet" href="/TH-MNM/public/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .home-wrap {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero {
            padding: 4rem 1rem 2rem;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(167, 139, 250, 0.4);
            background: rgba(167, 139, 250, 0.12);
            color: #ddd6fe;
            font-size: .9rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
        }

        .hero h1 {
            font-size: clamp(2.2rem, 6vw, 4.2rem);
            line-height: 1.1;
            margin-bottom: 1rem;
            letter-spacing: -0.03em;
        }

        .hero p {
            max-width: 760px;
            margin: 0 auto 2rem;
            color: var(--text-muted);
            font-size: clamp(1rem, 2vw, 1.2rem);
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 1rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .stat-card {
            background: rgba(15, 23, 42, 0.45);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 18px;
            padding: 1rem 1.25rem;
            backdrop-filter: blur(16px);
        }

        .stat-card strong {
            display: block;
            font-size: 1.5rem;
            color: #f8fafc;
            margin-bottom: .2rem;
        }

        .stat-card span {
            color: var(--text-muted);
            font-size: .92rem;
        }

        .quick-sections {
            margin-top: 2.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .quick-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, rgba(30,41,59,.6), rgba(15,23,42,.45));
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 20px;
            padding: 1.4rem;
            backdrop-filter: blur(16px);
            transition: var(--transition);
        }

        .quick-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 36px rgba(0,0,0,.35), 0 0 0 1px rgba(167,139,250,.25) inset;
        }

        .quick-card .icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-bottom: .8rem;
            background: linear-gradient(135deg, rgba(99,102,241,.35), rgba(236,72,153,.25));
            border: 1px solid rgba(255,255,255,.16);
            color: #e9d5ff;
            font-size: 1.5rem;
        }

        .quick-card h3 {
            font-size: 1.2rem;
            margin-bottom: .35rem;
            color: #fff;
        }

        .quick-card p {
            color: var(--text-muted);
            font-size: .95rem;
            margin-bottom: 1rem;
        }

        .features {
            margin-top: 2.2rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,.1);
            background: rgba(15, 23, 42, 0.38);
            backdrop-filter: blur(14px);
        }

        .features h2 {
            margin-bottom: .9rem;
            text-align: left;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
            gap: .8rem;
        }

        .feature-item {
            padding: .85rem 1rem;
            border-radius: 12px;
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.08);
            color: #e5e7eb;
            display: flex;
            align-items: center;
            gap: .55rem;
            font-size: .93rem;
        }

        .feature-item i {
            color: #a78bfa;
            font-size: 1.05rem;
        }

        @media (max-width: 900px) {
            .hero-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="home-wrap">
        <section class="hero">
            <span class="hero-badge">
                <i class="ph ph-sparkle"></i>
                Nền tảng quản lý bán hàng hiện đại
            </span>
            <h1>Bán hàng đa cấp Dashboard</h1>
            <p>Quản lý sản phẩm và danh mục tập trung trên một giao diện chuyên nghiệp, trực quan, mượt mà và tối ưu cho vận hành hàng ngày.</p>

            <div class="hero-actions">
                <a href="/TH-MNM/Product/list" class="btn btn-primary btn-water">
                    <i class="ph ph-package"></i>&nbsp; Quản lý sản phẩm
                </a>
                <a href="/TH-MNM/Category/list" class="btn btn-outline btn-water">
                    <i class="ph ph-squares-four"></i>&nbsp; Quản lý danh mục
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat-card">
                    <strong>Minh bạch nguồn gốc</strong>
                    <span>Thông tin sản phẩm rõ ràng, dễ kiểm chứng</span>
                </div>
                <div class="stat-card">
                    <strong>Cam kết chất lượng</strong>
                    <span>Chính sách bảo hành và đổi trả minh bạch</span>
                </div>
                <div class="stat-card">
                    <strong>Hỗ trợ tận tâm</strong>
                    <span>Đồng hành cùng khách hàng trước và sau bán</span>
                </div>
            </div>
        </section>

        <section class="quick-sections">
            <article class="quick-card">
                <div class="icon"><i class="ph ph-package"></i></div>
                <h3>Sản phẩm</h3>
                <p>Quản lý tên, mô tả, ảnh, giá và phân loại sản phẩm với thao tác trực quan.</p>
                <a href="/TH-MNM/Product/list" class="btn btn-outline btn-water">Vào trang sản phẩm</a>
            </article>

            <article class="quick-card">
                <div class="icon"><i class="ph ph-squares-four"></i></div>
                <h3>Danh mục</h3>
                <p>Tổ chức hệ thống danh mục khoa học để phân loại dữ liệu nhanh hơn.</p>
                <a href="/TH-MNM/Category/list" class="btn btn-outline btn-water">Vào trang danh mục</a>
            </article>

            <article class="quick-card">
                <div class="icon"><i class="ph ph-chart-line-up"></i></div>
                <h3>Vận hành</h3>
                <p>Trải nghiệm workflow quản trị tối ưu, rõ ràng, phù hợp mở rộng trong tương lai.</p>
                <a href="/TH-MNM/Product/add" class="btn btn-outline btn-water">Tạo sản phẩm mới</a>
            </article>
        </section>

        <section class="features">
            <h2>Tính năng nổi bật</h2>
            <div class="feature-list">
                <div class="feature-item"><i class="ph ph-check-circle"></i> Tìm kiếm & lọc sản phẩm theo danh mục</div>
                <div class="feature-item"><i class="ph ph-check-circle"></i> Upload ảnh sản phẩm trực tiếp</div>
                <div class="feature-item"><i class="ph ph-check-circle"></i> Giao diện responsive cho desktop/mobile</div>
                <div class="feature-item"><i class="ph ph-check-circle"></i> Hệ thống route chuẩn theo module</div>
            </div>
        </section>
    </div>
</body>
</html>
