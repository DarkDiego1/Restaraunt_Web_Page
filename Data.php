<?php
$Nombre = $_POST['Nombre'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$Card = $_POST['Card_Number'] ?? '';
$Card_holder = $_POST['Card_Holders'] ?? '';
$expiration = $_POST['Card_Expiration'] ?? '';
$CVV = $_POST['Card_CVV'] ?? '';
$Direccion = $_POST['Direccion'] ?? '';
$Postal = $_POST['Postal'] ?? '';
$city = $_POST['city'] ?? '';
$Telefono = $_POST['Telefono'] ?? '';

$fullName = trim($Nombre . ' ' . $apellidos);
if (empty($fullName)) {
    $fullName = $Card_holder ?: 'Customer';
}

$last4 = strlen($Card) >= 4 ? substr($Card, -4) : '****';

$cartDataRaw = $_POST['cart_data'] ?? '[]';
$cartItems = json_decode($cartDataRaw, true) ?: [];
$total = $_POST['order_total'] ?? '0.00';

$menuJsonContent = file_exists('menu.json') ? file_get_contents('menu.json') : false;
$menuData = $menuJsonContent ? json_decode($menuJsonContent, true) : null;
$drivers = $menuData['drivers'] ?? [
    ["name" => "John Doe", "img" => "Driver1.png"],
    ["name" => "Jane Smith", "img" => "Driver2.png"],
    ["name" => "Mike Johnson", "img" => "Driver3.png"]
];
$randomDriver = !empty($drivers) ? $drivers[array_rand($drivers)] : ["name" => "Driver", "img" => "Driver1.png"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McDonald's - Order Confirmation</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <header>
        <div class="header-container">
            <div class="header-brand">
                <span class="m-logo">M</span>
                <h1>McDonald's</h1>
            </div>
            <a href="Mcdonnalds.html" style="color: #27251F; background-color: #FFBC0D; text-decoration: none; font-weight: bold; padding: 8px 16px; border-radius: 6px;">Back to Menu</a>
        </div>
    </header>

    <main>
        <section class="confirmation-section" style="display: flex;">
            <div class="confirmation_container">
                <div class="confirmation-banner">
                    <span class="confirmation-check">&#10003;</span>
                    <h2>Order Confirmed!</h2>
                    <p>Thank you for your purchase, <strong><?php echo htmlspecialchars($fullName); ?></strong>!</p>
                </div>

                <div class="assigned-driver-box">
                    <h3>Assigned Driver</h3>
                    <div class="driver-card selected assigned-driver-card">
                        <img src="<?php echo htmlspecialchars($randomDriver['img']); ?>" alt="<?php echo htmlspecialchars($randomDriver['name']); ?>">
                        <p><?php echo htmlspecialchars($randomDriver['name']); ?></p>
                        <span class="driver-status">On the way</span>
                    </div>
                </div>

                <div class="confirmation-details">
                    <h3>Order Summary</h3>
                    <div class="confirmation-items-list">
                        <?php if (!empty($cartItems)): ?>
                            <?php foreach ($cartItems as $item): ?>
                                <div class="confirmation-item-row">
                                    <span><?php echo htmlspecialchars($item['quantity'] . 'x ' . $item['name']); ?></span>
                                    <span>$ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="confirmation-item-row">
                                <span>No items</span>
                                <span>$ 0.00</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="confirmation-total-row">
                        <span>Total (Card ending in <?php echo htmlspecialchars($last4); ?>)</span>
                        <span>$ <?php echo htmlspecialchars($total); ?></span>
                    </div>
                </div>

                <div class="confirmation-details">
                    <h3>Delivery Details</h3>
                    <p><strong>Recipient:</strong> <?php echo htmlspecialchars($fullName); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($Direccion . ', ' . $city . ', ' . $Postal); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($Telefono); ?></p>
                </div>
            </div>
        </section>
    </main>

</body>
</html>

</html>