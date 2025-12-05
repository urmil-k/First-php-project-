<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';
require_once 'includes/fpdf.php';

// 1. Check Authentication
if (!isset($_SESSION['user_id']) && !isset($_SESSION['is_admin'])) {
    die("❌ Unauthorized access.");
}

if (!isset($_GET['order_id'])) {
    die("❌ Order ID is missing.");
}

$orderId = intval($_GET['order_id']);

// 2. Fetch Order Details
$sql = "SELECT o.*, u.uname, u.email 
        FROM orders o 
        JOIN users u ON o.uid = u.uid 
        WHERE o.order_id = :oid";
$stmt = $pdo->prepare($sql);
$stmt->execute([':oid' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Security Check: Users can only download their OWN orders (Admins can download any)
if (!$order || (!isset($_SESSION['is_admin']) && $order['uid'] != $_SESSION['user_id'])) {
    die("❌ Order not found or access denied.");
}

// 3. Fetch Order Items
$sqlItems = "SELECT oi.*, p.pname 
             FROM order_items oi 
             JOIN product p ON oi.pid = p.pid 
             WHERE oi.order_id = :oid";
$stmtItems = $pdo->prepare($sqlItems);
$stmtItems->execute([':oid' => $orderId]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

// 4. Create PDF using FPDF
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'APPLE STORE ONLINE - INVOICE', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Generated on: ' . date('d-M-Y'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Order Info Box
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(0, 10, "Order Info", 1, 1, 'L', true);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 8, "Order ID: #" . $order['order_id'], 0, 0);
$pdf->Cell(50, 8, "Status: " . ucfirst($order['status']), 0, 1);
$pdf->Cell(50, 8, "Date: " . $order['created_at'], 0, 1);
$pdf->Ln(5);

// Customer Info Box
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Customer Details", 1, 1, 'L', true);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, "Name: " . $order['name'], 0, 1);
$pdf->Cell(0, 8, "Email: " . $order['email'], 0, 1);
$pdf->Cell(0, 8, "Phone: " . $order['phone'], 0, 1);
$pdf->MultiCell(0, 8, "Address: " . $order['address']);
$pdf->Ln(10);

// Items Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(100, 10, 'Product', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Price', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Qty', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Total', 1, 1, 'C', true);

// Items Table Body
$pdf->SetFont('Arial', '', 10);
$total = 0;

foreach ($items as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    
    // 1. Save the current position (X and Y)
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // 2. Print the Product Name using MultiCell
    $pdf->MultiCell(100, 5, $item['pname'], 1, 'L');
    
    // 3. Get the new Y position (where the MultiCell ended)
    $newY = $pdf->GetY();
    
    // 4. Calculate the total height of this row
    $height = $newY - $y;
    
    // 5. Move the cursor BACK to the right of the Product cell
    // (X + 100 width, Original Y)
    $pdf->SetXY($x + 100, $y);
    
    // 6. Print the remaining cells using the calculated $height
    // This ensures their borders stretch all the way down
    $pdf->Cell(30, $height, number_format($item['price'], 2), 1, 0, 'R');
    $pdf->Cell(20, $height, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(40, $height, number_format($subtotal, 2), 1, 1, 'R');
    
    // 7. Force the PDF to start the next row below the tallest cell
    // (This prevents the next row from overlapping if the last cell was shorter)
    $pdf->SetXY($x, $newY); // Reset X to left margin, Y to new row bottom
}

// Grand Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'Grand Total', 1, 0, 'R');
$pdf->Cell(40, 10, number_format($total, 2), 1, 1, 'R');

// Output
$pdf->Output('D', 'Invoice_' . $orderId . '.pdf'); // 'D' forces download
?>