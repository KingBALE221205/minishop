<?php
session_start();

/* =========================
   DANH SÁCH SẢN PHẨM GIẢ LẬP
   ========================= */
$products = [
    1 => ["name" => "Nike Mercurial Vapor 15", "price" => 3500000, "qty" => 3],
    2 => ["name" => "Adidas Predator Accuracy", "price" => 3200000, "qty" => 2],
    3 => ["name" => "Puma Future Ultimate", "price" => 3000000, "qty" => 1],
    4 => ["name" => "Mizuno Morelia Neo", "price" => 3800000, "qty" => 2],
    5 => ["name" => "Nike Tiempo Legend 10", "price" => 3300000, "qty" => 0],
];

/* =========================
   GIỎ HÀNG
   ========================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   MUA HÀNG
   ========================= */
if (isset($_GET['buy'])) {
    $id = (int)$_GET['buy'];
    if (isset($products[$id]) && $products[$id]['qty'] > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
        $products[$id]['qty']--;
    }
}

/* =========================
   THANH TOÁN
   ========================= */
if (isset($_POST['checkout'])) {
    $_SESSION['cart'] = [];
    echo "<script>alert('Thanh toán thành công!');</script>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>MINISHOP</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f8;
}

/* HEADER CỐ ĐỊNH */
header {
    position: fixed;
    top: 0;
    width: 100%;
    background: #111;
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 24px;
    z-index: 1000;
}

/* NỘI DUNG */
.container {
    width: 1000px;
    margin: 120px auto 60px;
    background: white;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}
th {
    background: #222;
    color: white;
}

.buy {
    background: #28a745;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
}

.soldout {
    color: red;
    font-weight: bold;
}

/* GIỎ HÀNG */
.cart {
    margin-top: 30px;
    background: #f8f9fa;
    padding: 15px;
}

/* FOOTER */
footer {
    background: #111;
    color: white;
    text-align: center;
    padding: 15px;
    position: fixed;
    bottom: 0;
    width: 100%;
}
</style>
</head>

<body>

<header>
    🏪 MINISHOP – CỬA HÀNG GIÀY THỂ THAO
</header>

<div class="container">
<h2>⚽ DANH SÁCH SẢN PHẨM</h2>

<table>
<tr>
    <th>Tên giày</th>
    <th>Giá</th>
    <th>Số lượng</th>
    <th>Trạng thái</th>
</tr>

<?php foreach ($products as $id => $p): ?>
<tr>
    <td><?= $p['name'] ?></td>
    <td><?= number_format($p['price']) ?> VNĐ</td>
    <td><?= $p['qty'] ?></td>
    <td>
        <?php if ($p['qty'] > 0): ?>
            <a class="buy" href="?buy=<?= $id ?>">🛒 Mua hàng</a>
        <?php else: ?>
            <span class="soldout">SOLD OUT</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<div class="cart">
<h3>🛍️ Giỏ hàng</h3>

<?php
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty):
    $subtotal = $products[$id]['price'] * $qty;
    $total += $subtotal;
?>
<p><?= $products[$id]['name'] ?> × <?= $qty ?> = <?= number_format($subtotal) ?> VNĐ</p>
<?php endforeach; ?>

<h4>💰 Tổng tiền: <?= number_format($total) ?> VNĐ</h4>

<?php if ($total > 0): ?>
<form method="post">
    <button name="checkout">💳 Thanh toán</button>
</form>
<?php endif; ?>
</div>

</div>

<footer>
    © 2026 – MINISHOP | Điện toán đám mây
</footer>

</body>
</html>