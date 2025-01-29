<?php
require_once('tcpdf/tcpdf.php');
include("db_connection.php");

// Get user time from form submission
$user_time = isset($_POST['user_time']) ? $_POST['user_time'] : '';

// Fetch applications with details
$query = "
SELECT 
    Applications.application_id,
    Applications.application_status,
    Applications.application_date,
    Worker.worker_id,
    Worker.worker_username,
    Jobs.job_title,
    Jobs.company_id,
    Company.company_name
FROM Applications
JOIN Worker ON Applications.worker_id = Worker.worker_id
JOIN Jobs ON Applications.job_id = Jobs.job_id
JOIN Company ON Jobs.company_id = Company.company_id";

$result = $conn->query($query);

// Create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Application Report');
$pdf->SetHeaderData('', 0, 'Application Report', 'Generated on: ' . $user_time);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetMargins(10, 20, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 10);
$pdf->AddPage();

// Table header
$html = '<h2>Application Report</h2><table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>S. No.</th>
            <th>Application ID</th>
            <th>Company ID</th>
            <th>Company Name</th>
            <th>Worker ID</th>
            <th>Worker Username</th>
            <th>Job Title</th>
            <th>Application Date</th>
            <th>Application Status</th>
        </tr>
    </thead>
    <tbody>';

// Table rows
$i = 1;
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>' . $i++ . '</td>
        <td>' . $row['application_id'] . '</td>
        <td>' . $row['company_id'] . '</td>
        <td>' . $row['company_name'] . '</td>
        <td>' . $row['worker_id'] . '</td>
        <td>' . $row['worker_username'] . '</td>
        <td>' . $row['job_title'] . '</td>
        <td>' . $row['application_date'] . '</td>
        <td>' . $row['application_status'] . '</td>
    </tr>';
}
$html .= '</tbody></table>';

// Output table
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('application_report.pdf', 'I');
?>
