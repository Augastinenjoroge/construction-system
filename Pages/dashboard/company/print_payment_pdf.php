<?php
require_once('tcpdf/tcpdf.php');

// Include database connection
include("db_connection.php");

// Authenticate the user
session_start();
// Authenticate the user
if (!isset($_SESSION['company_id'], $_SESSION['role']) || $_SESSION['role'] != 3) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access."]);
    exit();
}

$company_id = $_SESSION['company_id'];

// Fetch company details
$query = $conn->prepare("SELECT company_name, company_profile FROM Company WHERE company_id = ?");
$query->bind_param("i", $company_id);
$query->execute();
$result = $query->get_result();
$company = $result->fetch_assoc();

$company_name = $company['company_name'];
$company_profile = $company['company_profile'];

// Get the user time from the POST request
$user_time = isset($_POST['user_time']) ? $_POST['user_time'] : date('M d Y | h:i A'); // Default to server time if not provided

// Create PDF instance
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // 'L' for landscape orientation
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// Add Company Profile (Logo)
if (!empty($company_profile) && file_exists($company_profile)) {
    $pdf->Image($company_profile, 15, 10, 30, 30, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
}

// Add Heading
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 20, 'Payment Processed Report', 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, $company_name, 0, 1, 'C');
$pdf->SetFont('helvetica', 'I', 10);

// Use the user computer time
$pdf->Cell(0, 10, 'Generated on: ' . $user_time, 0, 1, 'C'); // Display user time from the form
$pdf->Ln(10);

// Add Table Header
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255, 165, 0); // Orange
$pdf->SetTextColor(255, 255, 255); // White
$pdf->Cell(35, 10, 'Worker Username', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Phone Number', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Email', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Job Title', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Payment Amount', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Payment Date', 1, 1, 'C', true); // New column for Payment Date

// Fetch Payment Data
$payments_query = $conn->prepare("
    SELECT 
        w.worker_username, 
        w.worker_phone_number, 
        w.worker_email, 
        j.job_title, 
        p.payment_amount,
        p.payment_date
    FROM Payments p
    INNER JOIN Worker w ON p.worker_id = w.worker_id
    INNER JOIN Jobs j ON p.job_id = j.job_id
    WHERE p.company_id = ?
");
$payments_query->bind_param("i", $company_id);
$payments_query->execute();
$payments_result = $payments_query->get_result();

// Populate Table Rows
$pdf->SetTextColor(0, 0, 0); // Black
$pdf->SetFont('helvetica', '', 10);
while ($payment = $payments_result->fetch_assoc()) {
    $pdf->Cell(35, 10, $payment['worker_username'], 1);
    $pdf->Cell(35, 10, $payment['worker_phone_number'], 1);
    $pdf->Cell(50, 10, $payment['worker_email'], 1);
    $pdf->Cell(50, 10, $payment['job_title'], 1);
    $pdf->Cell(30, 10, number_format($payment['payment_amount'], 2), 1);
    // Format the payment date like 'Nov 27 2024 | 6:00 AM'
    $pdf->Cell(50, 10, date('M d Y | h:i A', strtotime($payment['payment_date'])), 1, 1); // Display payment date in custom format
}

// Close database connection
$conn->close();

// Output PDF for download
$pdf->Output('Payment_Processed_Report.pdf', 'D');
?>
