# TODO - Cart + Home Shop + Category Delete Guard + Session Init

- [x] Cập nhật `index.php` để `session_start()` ở đầu mỗi request (an toàn, tránh gọi lặp).
- [x] Refactor `app/controllers/ProductController.php`:
  - [x] Sửa logic giỏ hàng sai/thiếu (`addToCart`, `cart`, `updateCart`, `removeFromCart`, `clearCart`).
  - [x] Sửa `checkout` + `processCheckout` dùng `$this->conn` (PDO) đồng nhất.
  - [x] Sau thanh toán thành công: `unset($_SESSION['cart'])`.
  - [x] Redirect path chuẩn `/TH-MNM/...`.
- [x] Cập nhật `app/controllers/CategoryController.php`:
  - [x] Chặn xóa danh mục nếu còn sản phẩm thuộc danh mục đó.
- [x] Chuyển `app/views/home/index.php` thành trang trưng bày bán hàng:
  - [x] Hiển thị danh sách sản phẩm dạng showcase.
  - [x] Có nút thêm vào giỏ hàng.
- [x] Cập nhật `app/views/product/list.php`:
  - [x] Bổ sung nút “Thêm vào giỏ”.
- [x] Cập nhật `app/views/shares/header.php`:
  - [x] Bổ sung link giỏ hàng + badge số lượng từ session.
- [x] Tạo các view mới cho giỏ hàng/đặt hàng:
  - [x] `app/views/product/cart.php`
  - [x] `app/views/product/checkout.php`
  - [x] `app/views/product/order_confirmation.php`
- [x] Cập nhật `app/views/category/list.php`:
  - [x] Hiển thị thông báo phù hợp khi danh mục không xóa được (nếu có).
- [x] Rà soát syntax/logic toàn bộ flow.

# TODO - Resize nút trên card sản phẩm (Home)

- [x] Cập nhật `app/views/home/index.php`:
  - [x] Gắn class riêng cho 2 nút “Thêm vào giỏ” và “Xem chi tiết”.
- [x] Cập nhật `public/css/style.css`:
  - [x] Thêm rule CSS để giảm nhẹ kích thước 2 nút trên card sản phẩm, không ảnh hưởng nút khác.
- [ ] Kiểm tra giao diện:
  - [ ] Đảm bảo 2 nút nhỏ hơn một chút và bố cục vẫn cân đối.

# TODO - Phân quyền Admin/User + hoàn thiện đăng nhập/đăng ký

- [x] `app/controllers/AccountController.php`
  - [x] Sửa lỗi cú pháp/logic và chuẩn hoá route `/TH-MNM/...`.
  - [x] Hoàn thiện `save`, `checkLogin`, `logout` với xử lý lỗi đầy đủ.
- [x] `app/controllers/ProductController.php`
  - [x] Thêm guard phân quyền admin cho `add`, `edit`, `delete`.
- [x] `app/controllers/CategoryController.php`
  - [x] Thêm guard phân quyền admin cho `add`, `edit`, `delete`.
- [x] `app/views/shares/header.php`
  - [x] Hiển thị menu theo trạng thái đăng nhập và role (guest/user/admin).
- [x] `app/views/account/login.php`
  - [x] Làm lại giao diện + action đúng route + hiển thị lỗi.
- [x] `app/views/account/register.php`
  - [x] Làm lại giao diện + action đúng route + hiển thị lỗi.
- [x] `app/views/product/list.php`
  - [x] Ẩn/hiện nút CRUD theo role admin.
- [x] `app/views/category/list.php`
  - [x] Ẩn/hiện nút CRUD theo role admin.
- [ ] Rà soát nhanh toàn bộ luồng phân quyền.
