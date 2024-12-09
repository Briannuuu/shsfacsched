<?php
require_once('plugins/tcpdf/tcpdf.php');
include('includes/dbcon.php');

$instructor = $_GET['instructor'] ?? '';

// echo $instructor;

// Fetch schedule data based on the selected instructor
$query = $dbh->prepare("SELECT * FROM schedules WHERE fac_name = :instructor");
$query->bindParam(':instructor', $instructor, PDO::PARAM_STR);
$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Title
$pdf->Cell(0, 10, 'Faculty Schedule Report', 0, 1, 'C');

// Table headers
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(40, 10, 'Subject', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Room', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Section', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Day', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'Start Time', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'End Time', 1, 1, 'C', 1);

// Table data
foreach ($data as $row) {
    $pdf->Cell(40, 10, $row['subj_name'], 1);
    $pdf->Cell(40, 10, $row['room_name'] . ' ' . $row['room_no'], 1);
    $pdf->Cell(30, 10, $row['sec_name'], 1);
    $pdf->Cell(30, 10, $row['day'], 1);
    $pdf->Cell(25, 10, $row['start_time'], 1);
    $pdf->Cell(25, 10, $row['end_time'], 1, 1);
}

$pdf->Output('schedule_report.pdf', 'D');
?>