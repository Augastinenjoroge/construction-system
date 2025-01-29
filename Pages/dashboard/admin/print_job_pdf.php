<?php
require_once('tcpdf/tcpdf.php');
include("db_connection.php");

// Fetch job data
$query = "
    SELECT 
        j.job_id, j.company_id, c.company_name, j.job_title, j.job_description, 
        j.job_location, j.job_salary, j.job_status, j.job_created_at 
    FROM Jobs j
    JOIN Company c ON j.company_id = c.company_id
";
$result = $conn->query($query);

// Generate PDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Job Report');
$pdf->SetHeaderData('', 0, 'Job Report', 'Generated on: ' . (isset($_POST['user_time']) ? $_POST['user_time'] : ''));
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetMargins(10, 20, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

$html = '
    <h2>Job Report</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>S. No.</th>
                <th>Job ID</th>
                <th>Company Name</th>
                <th>Job Title</th>
                <th>Job Location</th>
                <th>Job Salary</th>
                <th>Job Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>';
$i = 1;
while ($row = $result->fetch_assoc()) {
    $html .= '
        <tr>
            <td>' . $i++ . '</td>
            <td>' . $row['job_id'] . '</td>
            <td>' . $row['company_name'] . '</td>
            <td>' . $row['job_title'] . '</td>
            <td>' . $row['job_location'] . '</td>
            <td>' . $row['job_salary'] . '</td>
            <td>' . $row['job_status'] . '</td>
            <td>' . $row['job_created_at'] . '</td>
        </tr>';
}
$html .= '
        </tbody>
    </table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Job_Report.pdf', 'I');
