<?php
require_once('tcpdf/tcpdf.php');
include("db_connection.php");

// Fetch companys data
$query = "SELECT * FROM Company";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching companys: " . $conn->error);
}

// Get formatted user time from POST
$userTime = isset($_POST['user_time']) ? $_POST['user_time'] : "Unknown Time";

// Create a new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Admin');
$pdf->SetTitle('company Report');
$pdf->SetSubject('company Details');
$pdf->SetKeywords('TCPDF, PDF, report, company');

// Set header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Title
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'company Report', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, 'Generated Time: ' . $userTime, 0, 1, 'C');
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 10);
$tableHeader = '<table border="1" cellpadding="5">
    <tr style="background-color: #f2f2f2;">
        <th>S. No.</th>
        <th>User ID</th>
        <th>company ID</th>
        <th>company name</th>
        <th>company Email</th>
        <th>company Phone Number</th>
        <th>company Address</th>
        <th>Created At</th>
    </tr>';
$tableBody = '';

$count = 1;
while ($row = $result->fetch_assoc()) {
    $tableBody .= '<tr>
        <td>' . $count . '</td>
        <td>' . $row['user_id'] . '</td>
        <td>' . $row['company_id'] . '</td>
        <td>' . $row['company_name'] . '</td>
        <td>' . $row['company_email'] . '</td>
        <td>' . $row['company_phone_number'] . '</td>
        <td>' . $row['company_address'] . '</td>
        <td>' . $row['company_created_at'] . '</td>
    </tr>';
    $count++;
}

$tableFooter = '</table>';
$pdf->writeHTML($tableHeader . $tableBody . $tableFooter, true, false, false, false, '');

// Output the PDF
$pdf->Output('Company_Report.pdf', 'I');
?>
