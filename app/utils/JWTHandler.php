<?php
require_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTHandler
{
    // Khóa bí mật dùng để ký và xác thực tính toàn vẹn của Token JWT
    private $secret_key;

    /**
     * Khởi tạo JWTHandler và thiết lập khóa bí mật mặc định.
     */
    public function __construct()
    {
        // Khóa bảo mật dùng trong thuật toán HS256 để ký chuỗi JWT
        $this->secret_key = "HUTECH_University_XaLoHaNoi_12345";
    }

    /**
     * Tạo chuỗi Token JWT từ dữ liệu truyền vào.
     * Token sẽ có thời hạn sử dụng là 1 giờ (3600 giây) kể từ thời điểm phát hành.
     *
     * @param mixed $data Dữ liệu cần mã hóa vào token (thông tin user đăng nhập)
     * @return string Chuỗi token JWT đã được mã hóa và ký số
     */
    public function encode($data)
    {
        $issuedAt = time(); // Thời điểm phát hành token (Unix timestamp)
        $expirationTime = $issuedAt + 3600; // Thời điểm hết hạn (hợp lệ trong 1 giờ)
        
        // Cấu trúc payload chứa thông tin JWT chuẩn
        $payload = array(
            'iat' => $issuedAt,        // Thời gian phát hành (Issued At)
            'exp' => $expirationTime,  // Thời gian hết hạn (Expiration Time)
            'data' => $data            // Dữ liệu tùy chỉnh chứa trong Token
        );
        
        // Thực hiện mã hóa Payload sử dụng thuật toán HS256 và khóa bí mật
        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    /**
     * Giải mã chuỗi Token JWT để lấy lại dữ liệu gốc.
     * Kiểm tra tính toàn vẹn của chữ ký số và hạn sử dụng của Token.
     *
     * @param string $jwt Chuỗi token JWT cần giải mã
     * @return array|null Trả về mảng dữ liệu gốc nếu giải mã thành công và token còn hạn, ngược lại trả về null.
     */
    public function decode($jwt)
    {
        try {
            // Thực hiện giải mã Token bằng thư viện Firebase JWT
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            // Chuyển đối tượng stdClass được giải mã thành một mảng liên kết PHP
            return (array) $decoded->data;
        } catch (Exception $e) {
            // Trả về null nếu token không hợp lệ, sai chữ ký, hoặc đã hết hạn
            return null;
        }
    }
}
?>