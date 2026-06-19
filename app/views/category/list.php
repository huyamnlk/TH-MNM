<?php $pageTitle = 'Quản lý Danh mục'; include 'app/views/shares/header.php'; ?>

<div class="container">
    <h1>Quản lý Danh mục</h1>
    <div class="header-actions" style="margin-bottom: 2rem;">
        <a href="/TH-MNM/Category/add" class="btn btn-primary btn-water">
            <i class="ph ph-plus-circle" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
            Thêm danh mục mới
        </a>
    </div>

    <div class="glass-panel" style="padding: 2rem; margin-bottom: 2.5rem;">
        <ul class="list-group" id="category-list" style="list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem;">
            <!-- Danh sách danh mục sẽ được tải từ API và hiển thị tại đây -->
        </ul>
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

        fetch('/TH-MNM/api/category', {
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
                const categoryList = document.getElementById('category-list');
                if (data.length === 0) {
                    categoryList.innerHTML = `<li style="text-align: center; color: var(--text-muted); padding: 2rem 0;">Chưa có danh mục nào. Hãy tạo mới!</li>`;
                    return;
                }
                data.forEach(category => {
                    const categoryItem = document.createElement('li');
                    categoryItem.className = 'list-group-item';
                    categoryItem.style.background = 'rgba(255, 255, 255, 0.02)';
                    categoryItem.style.border = '1px solid rgba(255, 255, 255, 0.08)';
                    categoryItem.style.borderRadius = '16px';
                    categoryItem.style.padding = '1.5rem';
                    categoryItem.style.display = 'flex';
                    categoryItem.style.justifyContent = 'space-between';
                    categoryItem.style.alignItems = 'center';
                    categoryItem.style.gap = '1.5rem';
                    categoryItem.style.transition = 'var(--transition)';

                    categoryItem.innerHTML = `
                        <div style="flex-grow: 1;">
                            <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem; font-weight: 600;">
                                <a href="/TH-MNM/Category/show/${category.id}" style="color: var(--text-main); text-decoration: none; transition: var(--transition);">
                                    ${escapeHtml(category.name)}
                                </a>
                            </h2>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">${escapeHtml(category.description || 'Không có mô tả')}</p>
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                            <a href="/TH-MNM/Category/edit/${category.id}" class="btn btn-outline btn-water btn-sm" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                <i class="ph ph-pencil-simple" style="margin-right: 0.25rem;"></i> Sửa
                            </a>
                            <button class="btn btn-danger btn-water btn-sm" onclick="deleteCategory(${category.id})" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                <i class="ph ph-trash" style="margin-right: 0.25rem;"></i> Xóa
                            </button>
                        </div>
                    `;
                    // Hover effect
                    categoryItem.addEventListener('mouseenter', () => {
                        categoryItem.style.background = 'rgba(255, 255, 255, 0.04)';
                        categoryItem.style.transform = 'translateY(-2px)';
                    });
                    categoryItem.addEventListener('mouseleave', () => {
                        categoryItem.style.background = 'rgba(255, 255, 255, 0.02)';
                        categoryItem.style.transform = 'translateY(0)';
                    });
                    categoryList.appendChild(categoryItem);
                });
            })
            .catch(error => console.error('Error fetching categories:', error));
    });

    function deleteCategory(id) {
        const token = localStorage.getItem('jwtToken');
        if (!token) {
            alert('Vui lòng đăng nhập');
            location.href = '/TH-MNM/Account/login';
            return;
        }

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
                        location.reload();
                    } else {
                        alert(data.message || 'Xóa danh mục thất bại');
                    }
                })
                .catch(error => console.error('Error deleting category:', error));
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
</script>