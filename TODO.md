# TODO - Build full web bán hàng (Product + Category CRUD + UI upgrade)

- [ ] Hoàn thiện backend cơ bản
  - [x] Thêm `show($id)` cho ProductController
  - [ ] Thêm `show($id)` cho CategoryController
  - [x] Chuẩn hóa `session_start()` an toàn trong controllers
  - [x] Chuẩn hóa redirect path `/TH-MNM/...`

- [ ] Hoàn thiện view chức năng cơ bản
  - [ ] Tạo `app/views/product/show.php`
  - [ ] Tạo `app/views/category/show.php`
  - [ ] Thêm nút "Xem" ở list Product/Category
  - [ ] Bổ sung điều hướng thống nhất giữa các trang

- [ ] Hoàn thiện layout dùng chung
  - [ ] Tạo `app/views/shares/header.php`
  - [ ] Tạo `app/views/shares/footer.php`
  - [ ] Gắn header/footer vào các trang home, product, category

- [ ] Nâng cấp giao diện
  - [ ] Cải tiến `public/css/style.css` (hero, card, button, detail view, responsive)
  - [ ] Hiệu ứng hover/transition đẹp và đồng bộ

- [ ] Kiểm tra sau triển khai
  - [ ] Kiểm tra route và điều hướng
  - [ ] Kiểm tra CRUD Product (add/edit/delete/show/list)
  - [ ] Kiểm tra CRUD Category (add/edit/delete/show/list)
  - [ ] Kiểm tra hiển thị UI trên các trang chính
