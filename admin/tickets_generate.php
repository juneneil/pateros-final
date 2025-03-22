<?php
require_once('../tcpdf/tcpdf.php');
include 'includes/session.php';

function generateRow($conn) {
    $contents = '';
    $sql = "SELECT * FROM ticket ORDER BY created_at DESC";
    $query = $conn->query($sql);
    
    while ($row = $query->fetch_assoc()) {
        $contents .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['resident_id'] . '</td>
                <td>' . $row['category'] . '</td>
                <td>' . $row['sub_category'] . '</td>
                <td>' . $row['reason_for_inquiry'] . '</td>
                <td>' . $row['voters_certificate'] . '</td>
                <td>' . $row['asesor_office'] . '</td>
                <td>' . $row['business_office'] . '</td>
                <td>' . $row['health_office'] . '</td>
                <td>' . $row['cedula'] . '</td>
                <td>' . $row['job_opportunities'] . '</td>
                <td>' . $row['police_clearance'] . '</td>
                <td>' . $row['dswd'] . '</td>
                <td>' . $row['created_at'] . '</td>
                <td>' . $row['booking_date'] . '</td>
            </tr>';
    }
    return $contents;
}

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Ticket Report');
$pdf->SetHeaderData('', '', 'Ticket Report', 'Generated Report');
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
$content .= '<h2 align="center">Ticket Report</h2>';
$content .= '<table border="1" cellspacing="0" cellpadding="3">
                <tr>
                    <th>ID</th>
                    <th>Resident ID</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Reason for Inquiry</th>
                    <th>Voters Certificate</th>
                    <th>Asesor Office</th>
                    <th>Business Office</th>
                    <th>Health Office</th>
                    <th>Cedula</th>
                    <th>Job Opportunities</th>
                    <th>Police Clearance</th>
                    <th>DSWD</th>
                    <th>Created At</th>
                    <th>Booking Date</th>
                </tr>';
$content .= generateRow($conn);
$content .= '</table>';

$pdf->writeHTML($content);
$pdf->Output('ticket_report.pdf', 'I');
?>