<?php
require_once('../tcpdf/tcpdf.php');
include 'includes/session.php';

function generateRow($conn) {
    $contents = '';
    $sql = "SELECT * FROM employees ORDER BY created_on DESC";
    $query = $conn->query($sql);
    
    while ($row = $query->fetch_assoc()) {
        $contents .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['employee_id'] . '</td>
                <td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>
                <td>' . $row['address'] . '</td>
                <td>' . $row['birthdate'] . '</td>
                <td>' . $row['contact_info'] . '</td>
                <td>' . $row['gender'] . '</td>
                <td>' . $row['position_id'] . '</td>
                <td>' . $row['schedule_id'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['created_on'] . '</td>
            </tr>';
    }
    return $contents;
}

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Employee Report');
$pdf->SetHeaderData('', '', 'Employee Report', 'Generated Report');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 9);
$pdf->AddPage();

$content = '';
$content .= '<h2 align="center">Employee Report</h2>';
$content .= '<table border="1" cellspacing="0" cellpadding="3">
                <tr>
                    <th>ID</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Birthdate</th>
                    <th>Contact Info</th>
                    <th>Gender</th>
                    <th>Position ID</th>
                    <th>Schedule ID</th>
                    <th>Email</th>
                    <th>Created On</th>
                </tr>';
$content .= generateRow($conn);
$content .= '</table>';

$pdf->writeHTML($content);
$pdf->Output('employee_report.pdf', 'I');
?>
