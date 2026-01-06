<?php
// Thông tin kết nối TiDB Cloud (Đã điền sẵn)
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = 4000;
$username = '2K3eTsxBZH4ZizW.root';
$password = 'Pn9B7v9I84IO2HnV';
$dbname = 'test';

// Khởi tạo kết nối SSL (Bắt buộc với TiDB)
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Thực hiện kết nối
if (!mysqli_real_connect($conn, $host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Lỗi kết nối Database: " . mysqli_connect_error());
}

// Thiết lập font chữ tiếng Việt cho chuẩn
$conn->set_charset("utf8");
?>