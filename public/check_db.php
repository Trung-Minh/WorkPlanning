<?php
// File này chạy độc lập, không dùng Laravel
$host = 'bvcr2e3kygem28cqaa5q-mysql.services.clever-cloud.com';
$db   = 'bvcr2e3kygem28cqaa5q';
$user = 'utfoz5gpqmkenjfu';
$pass = 'ee7RmOBkWMLlNzlBXxJj'; // Mật khẩu bạn cung cấp
$port = 3306;

echo "<h2>Đang kiểm tra kết nối tới Clever Cloud...</h2>";
echo "Host: $host <br>";

try {
    // Thử kết nối bằng PDO thuần
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        // Tắt xác thực SSL để test
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<h1 style='color:green'>✅ KẾT NỐI THÀNH CÔNG!</h1>";
    echo "Server đã chấp nhận kết nối.";

} catch (\PDOException $e) {
    echo "<h1 style='color:red'>❌ KẾT NỐI THẤT BẠI</h1>";
    echo "<strong>Lỗi:</strong> " . $e->getMessage() . "<br><br>";

    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "👉 <b>Nguyên nhân:</b> Clever Cloud đang chặn IP của Render.<br>";
        echo "👉 <b>Giải pháp:</b> Vào Clever Cloud > Database > Security > Thêm IP <b>0.0.0.0/0</b>";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "👉 <b>Nguyên nhân:</b> Sai Mật khẩu hoặc Tài khoản.<br>";
    }
}
?>
