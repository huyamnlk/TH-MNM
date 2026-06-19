<?php $pageTitle = 'Chi tiết danh mục'; include 'app/views/shares/header.php'; ?>

<div class="container-sm">
    <div class="glass-panel" id="category-details-panel">
        <div class="product-image-placeholder" style="margin-bottom:1rem; height:120px; border-radius:16px; color:var(--primary-color);">
            <i class="ph ph-squares-four" style="font-size:3.5rem;"></i>
        </div>

        <h1 id="category-name" style="margin-bottom:.75rem; text-align: left;">Đang tải...</h1>
        <p id="category-description" class="product-desc" style="margin-bottom:1.2rem;"></p>

        <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
            <a id="edit-category-btn" href="#" class="btn btn-primary btn-water"><i class="ph ph-pencil-simple"></i>&nbsp;Sửa</a>
            <button id="delete-category-btn" class="btn btn-danger btn-water"><i class="ph ph-trash"></i>&nbsp;Xóa</button>
            <a href="/TH-MNM/Category/list" class="btn btn-outline btn-water"><i class="ph ph-arrow-left"></i>&nbsp;Quay lại</a>
        </div>
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

        const categoryId = <?= (int)$category['id'] ?>;
        
        // Fetch current category info
        fetch(`/TH-MNM/api/category/${categoryId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
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
                document.getElementById('category-name').textContent = data.name || 'Không có tên';
                document.getElementById('category-description').textContent = data.description || 'Không có mô tả';
                
                // Configure buttons
                document.getElementById('edit-category-btn').href = `/TH-MNM/Category/edit/${data.id}`;
                document.getElementById('delete-category-btn').onclick = function() {
                    deleteCategory(data.id);
                };
            })
            .catch(error => {
                console.error('Error fetching category data:', error);
                document.getElementById('category-name').textContent = 'Lỗi tải dữ liệu';
            });

        function deleteCategory(id) {
            if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
                fetch(`/TH-MNM/api/category/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                })
                    .then(response => {
                        if (response.status === 401) {
                            alert('Vui lòng đăng nhập lại');
                            location.href = '/TH-MNM/Account/login';
                            throw new Error('Unauthorized');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.message === 'Category deleted successfully') {
                            location.href = '/TH-MNM/Category/list';
                        } else {
                            alert(data.message || 'Xóa danh mục thất bại');
                        }
                    })
                    .catch(error => console.error('Error deleting category:', error));
            }
        }
    });
</script>
