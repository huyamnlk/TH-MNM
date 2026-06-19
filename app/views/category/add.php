<?php $pageTitle = 'Thêm danh mục mới'; include 'app/views/shares/header.php'; ?>

<div class="container-sm">
    <h1>Thêm danh mục mới</h1>
    
    <div class="glass-panel">
        <form id="add-category-form">
            <div class="form-group">
                <label for="name">Tên danh mục</label>
                <input type="text" id="name" name="name" placeholder="Nhập tên danh mục..." required>
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả chi tiết</label>
                <textarea id="description" name="description" placeholder="Nhập mô tả danh mục..."></textarea>
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

<?php include 'app/views/shares/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const token = localStorage.getItem('jwtToken');
        if (!token) {
            alert('Vui lòng đăng nhập');
            location.href = '/TH-MNM/Account/login';
            return;
        }

        document.getElementById('add-category-form').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            const jsonData = {};
            formData.forEach((value, key) => {
                jsonData[key] = value;
            });

            fetch('/TH-MNM/api/category', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(jsonData)
            })
                .then(response => {
                    if (response.status === 401) {
                        alert('Phiên làm việc hết hạn. Vui lòng đăng nhập lại.');
                        location.href = '/TH-MNM/Account/login';
                        throw new Error('Unauthorized');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message === 'Category created successfully') {
                        location.href = '/TH-MNM/Category/list';
                    } else {
                        if (data.errors && Array.isArray(data.errors)) {
                            alert('Thêm danh mục thất bại:\n' + data.errors.join('\n'));
                        } else {
                            alert(data.message || 'Thêm danh mục thất bại');
                        }
                    }
                })
                .catch(error => console.error('Error creating category:', error));
        });
    });
</script>