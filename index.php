<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ql_giay");
if ($conn->connect_error) die("Lỗi kết nối CSDL");

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ===== THÊM SẢN PHẨM ===== */
if (isset($_POST['them'])) {
    $ten = $_POST['ten'];
    $hang = $_POST['hang'];
    $gia = $_POST['gia'];
    $sl = $_POST['sl'];

    $conn->query("INSERT INTO giay (ten_giay, hang, gia, so_luong)
                  VALUES ('$ten','$hang',$gia,$sl)");
}

/* ===== MUA HÀNG ===== */
if (isset($_GET['mua'])) {
    $id = $_GET['mua'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
}

/* ===== THANH TOÁN ===== */
if (isset($_POST['thanhtoan'])) {
    foreach ($_SESSION['cart'] as $id => $sl) {
        $conn->query("UPDATE giay 
                      SET so_luong = so_luong - $sl 
                      WHERE id = $id AND so_luong >= $sl");
    }
    $_SESSION['cart'] = [];
    echo "<script>alert('Thanh toán thành công!');</script>";
}

$giay = $conn->query("SELECT * FROM giay");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>MINISHOP - Mua bán giày thể thao</title>
<style>
body {
    font-family: Arial;
    background:#f4f6f8;
    margin:0;
}

/* ===== HEADER CỐ ĐỊNH ===== */
header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background:#111;
    color:white;
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;
    z-index: 1000;
}

/* chừa khoảng trống cho header */
.container {
    width: 1100px;
    margin: 110px auto 30px auto;
    background: #fff;
    padding: 20px;
}

/* ===== FOOTER ===== */
footer {
    background:#111;
    color:white;
    text-align:center;
    padding:15px;
    margin-top:30px;
}

h2, h3 { text-align:center; }

input, button { padding:8px; margin:5px; }

table {
    width:100%;
    border-collapse: collapse;
    margin-top:20px;
}

th, td {
    border:1px solid #ccc;
    padding:10px;
    text-align:center;
}

th {
    background:#333;
    color:white;
}

.btn {
    background:#28a745;
    color:white;
    padding:6px 10px;
    text-decoration:none;
    border-radius:4px;
}

.soldout {
    color:red;
    font-weight:bold;
}

.cart {
    background:#f8f9fa;
    padding:15px;
    margin-top:20px;
}

.total {
    font-size:18px;
    font-weight:bold;
}

button {
    background:#007bff;
    color:white;
    border:none;
    cursor:pointer;
}
</style>
</head>

<body>

<header>
    🏪 MINISHOP
</header>

<div class="container">

<h2>QUẢN LÝ & MUA BÁN GIÀY THỂ THAO</h2>

<!-- ===== THÊM SẢN PHẨM ===== -->
<form method="post">
    <input type="text" name="ten" placeholder="Tên giày" required>
    <input type="text" name="hang" placeholder="Hãng" required>
    <input type="number" name="gia" placeholder="Giá (VNĐ)" required>
    <input type="number" name="sl" placeholder="Số lượng" required>
    <button name="them">Thêm sản phẩm</button>
</form>

<!-- ===== DANH SÁCH ===== -->
<h3>📦 Danh sách giày</h3>
<table>
<tr>
    <th>ID</th>
    <th>Tên giày</th>
    <th>Hãng</th>
    <th>Giá</th>
    <th>Còn</th>
    <th>Trạng thái</th>
</tr>

<?php while ($row = $giay->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['ten_giay'] ?></td>
    <td><?= $row['hang'] ?></td>
    <td><?= number_format($row['gia']) ?> VNĐ</td>
    <td><?= $row['so_luong'] ?></td>
    <td>
        <?php if ($row['so_luong'] > 0): ?>
            <a class="btn" href="?mua=<?= $row['id'] ?>">🛒 Mua</a>
        <?php else: ?>
            <span class="soldout">SOLD OUT</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>

<!-- ===== GIỎ HÀNG ===== -->
<div class="cart">
<h3>🛍️ Giỏ hàng</h3>

<?php
$tong = 0;
foreach ($_SESSION['cart'] as $id => $sl):
    $sp = $conn->query("SELECT * FROM giay WHERE id=$id")->fetch_assoc();
    if (!$sp) continue;
    $thanhtien = $sp['gia'] * $sl;
    $tong += $thanhtien;
?>
<p><?= $sp['ten_giay'] ?> × <?= $sl ?> = <?= number_format($thanhtien) ?> VNĐ</p>
<?php endforeach; ?>

<p class="total">💰 Tổng tiền: <?= number_format($tong) ?> VNĐ</p>

<?php if ($tong > 0): ?>
<form method="post">
    <button name="thanhtoan">💳 Thanh toán</button>
</form>
<?php endif; ?>
</div>

</div>

<footer>
    © 2026 MINISHOP | Ứng dụng điện toán đám mây
</footer>

</body>
</html>
