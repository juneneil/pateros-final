<?php
require_once('../tcpdf/tcpdf.php');
include 'includes/session.php';

function generateAttendanceRow($conn) {
    $contents = '';
    $sql = "SELECT * FROM attendance ORDER BY created_at DESC";
    $query = $conn->query($sql);
    
    while ($row = $query->fetch_assoc()) {
        $contents .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['employee_id'] . '</td>
                <td>' . $row['date'] . '</td>
                <td>' . $row['time_in'] . '</td>
                <td>' . $row['time_out'] . '</td>
                <td>' . $row['num_hr'] . '</td>
                <td>' . ($row['status'] == 1 ? 'Present' : 'Absent') . '</td>
                <td>' . $row['created_at'] . '</td>
            </tr>';
    }
    return $contents;
}

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Attendance Report');
$pdf->SetHeaderData('', '', 'Attendance Report', 'Generated Report');
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
$content .= '<h2 align="center">Attendance Report</h2>';
$content .= '<table border="1" cellspacing="0" cellpadding="3">
                <tr>
                    <th>ID</th>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours Worked</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>';
$content .= generateAttendanceRow($conn);
$content .= '</table>';

$pdf->writeHTML($content);
$pdf->Output('attendance_report.pdf', 'I');
?>
