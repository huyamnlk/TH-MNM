# API Endpoints

Base URL (no rewrite):
- `http://localhost/TH-MNM/index.php?url=api/{resource}`

Base URL (if URL rewrite is enabled):
- `http://localhost/TH-MNM/api/{resource}`

## Product API

1. List products
- Method: `GET`
- URL: `/api/product`

2. Get product by ID
- Method: `GET`
- URL: `/api/product/{id}`

3. Create new product
- Method: `POST`
- URL: `/api/product`
- Headers: `Content-Type: application/json`
- Body example:
```json
{
  "name": "Tên sản phẩm",
  "description": "Mô tả sản phẩm",
  "price": 120000,
  "category_id": 1
}
```

4. Update product
- Method: `PUT`
- URL: `/api/product/{id}`
- Headers: `Content-Type: application/json`
- Body example:
```json
{
  "name": "Tên mới",
  "description": "Mô tả mới",
  "price": 150000,
  "category_id": 2
}
```

5. Delete product
- Method: `DELETE`
- URL: `/api/product/{id}`

## Category API

1. List categories
- Method: `GET`
- URL: `/api/category`

2. Get category by ID
- Method: `GET`
- URL: `/api/category/{id}`

3. Create new category
- Method: `POST`
- URL: `/api/category`
- Headers: `Content-Type: application/json`
- Body example:
```json
{
  "name": "Tên danh mục",
  "description": "Mô tả danh mục"
}
```

4. Update category
- Method: `PUT`
- URL: `/api/category/{id}`
- Headers: `Content-Type: application/json`
- Body example:
```json
{
  "name": "Tên danh mục cập nhật",
  "description": "Mô tả mới"
}
```

5. Delete category
- Method: `DELETE`
- URL: `/api/category/{id}`

## Notes

- Nếu bạn dùng Postman, nhớ thêm header `Accept: application/json` và `Content-Type: application/json` cho POST/PUT.
- Nếu API trả lỗi `404`, kiểm tra xem ID có tồn tại trong cơ sở dữ liệu chưa.
- Nếu bạn không dùng URL rewrite, luôn gửi yêu cầu qua `index.php?url=api/...`.
