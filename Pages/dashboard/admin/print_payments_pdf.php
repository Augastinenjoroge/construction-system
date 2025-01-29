<?php
require_once('tcpdf/tcpdf.php');
include("db_connection.php");

// Fetch user time from POST
$user_time = isset($_POST['user_time']) ? $_POST['user_time'] : 'Unknown Time';

// Fetch payment data
$query = "SELECT p.payment_id, p.application_id, p.company_id, c.company_name, p.worker_id, w.worker_username, 
                 p.job_id, j.job_title, j.job_salary, p.payment_amount, p.payment_status, p.payment_date 
          FROM Payments p
          JOIN Company c ON p.company_id = c.company_id
          JOIN Worker w ON p.worker_id = w.worker_id
          JOIN Jobs j ON p.job_id = j.job_id";
$result = $conn->query($query);

// Create PDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Payments Report');
$pdf->SetHeaderData('', 0, 'Payments Report', "Generated on: $user_time");
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetMargins(10, 20, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Table HTML
$html = '<h2 style="text-align:center;">Payments Report</h2>';
$html .= '<table border="1" cellpadding="5">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th>S. No.</th>
                    <th>Payment ID</th>
                    <th>Application ID</th>
                    <th>Company ID</th>
                    <th>Company Name</th>
                    <th>Worker ID</th>
                    <th>Worker Username</th>
                    <th>Job ID</th>
                    <th>Job Title</th>
                    <th>Job Salary</th>
                    <th>Payment Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>';

$count = 1;
while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
                <td>{$count}</td>
                <td>{$row['payment_id']}</td>
                <td>{$row['application_id']}</td>
                <td>{$row['company_id']}</td>
                <td>{$row['company_name']}</td>
                <td>{$row['worker_id']}</td>
                <td>{$row['worker_username']}</td>
                <td>{$row['job_id']}</td>
                <td>{$row['job_title']}</td>
                <td>{$row['job_salary']}</td>
                <td>{$row['payment_amount']}</td>
                <td>{$row['payment_status']}</td>
                <td>{$row['payment_date']}</td>
              </tr>";
    $count++;
}

$html .= '</tbody></table>';

// Output HTML to PDF
$pdf->writeHTML($html);
$pdf->Output('Payments_Report.pdf', 'I');
