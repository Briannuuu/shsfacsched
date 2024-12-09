<?php
include('includes/dbcon.php');
header('Content-Type: application/json');

// Get input data from the client-side
$data = json_decode(file_get_contents("php://input"), true);

$subject_id = $data['subject_id'];
$room_id = $data['room_id'];
$section_id = $data['section_id'];
$day = $data['day'];
$start_time = $data['start_time'];
$end_time = $data['end_time'];
$instructor_name = $data['instructor'];

// Check that the start time is not later than or equal to the end time
if ($start_time >= $end_time) {
    echo json_encode(["error" => "Start time must be earlier than end time."]);
    exit;
}

// Find the instructor ID from the instructor's name
$queryInstructor = "SELECT mem_id FROM facmembers WHERE emp_name = :instructor_name";
$stmtInstructor = $dbh->prepare($queryInstructor);
$stmtInstructor->execute([':instructor_name' => $instructor_name]);

if ($stmtInstructor->rowCount() == 0) {
    echo json_encode(["error" => "Instructor not found."]);
    exit;
}

$instructor_id = $stmtInstructor->fetchColumn();

// Check for room conflicts
$queryRoomConflict = "
    SELECT * FROM schedules
    WHERE room_id = :room_id AND day = :day
    AND (start_time <= :end_time AND end_time >= :start_time)
";

$stmtRoomConflict = $dbh->prepare($queryRoomConflict);
$stmtRoomConflict->execute([
    ':room_id' => $room_id,
    ':day' => $day,
    ':start_time' => $start_time,
    ':end_time' => $end_time
]);

if ($stmtRoomConflict->rowCount() > 0) {
    echo json_encode(["conflict" => true, "error" => "Room is already occupied during this time."]);
    exit;
}

// Check for instructor conflicts
$queryInstructorConflict = "
    SELECT * FROM schedules
    WHERE fac_id = :fac_id AND day = :day
    AND (start_time <= :end_time AND end_time >= :start_time)
";

$stmtInstructorConflict = $dbh->prepare($queryInstructorConflict);
$stmtInstructorConflict->execute([
    ':fac_id' => $instructor_id,
    ':day' => $day,
    ':start_time' => $start_time,
    ':end_time' => $end_time
]);

if ($stmtInstructorConflict->rowCount() > 0) {
    echo json_encode(["conflict" => true, "error" => "Instructor is not available during this time."]);
    exit;
}

// Get the subject and room details to fill the schedules table completely
$querySubject = "SELECT subj_name FROM subjects WHERE subj_id = :subject_id";
$stmtSubject = $dbh->prepare($querySubject);
$stmtSubject->execute([':subject_id' => $subject_id]);
$subject_name = $stmtSubject->fetchColumn();

$queryRoom = "SELECT room_name, room_no FROM room WHERE room_id = :room_id";
$stmtRoom = $dbh->prepare($queryRoom);
$stmtRoom->execute([':room_id' => $room_id]);
$room_data = $stmtRoom->fetch(PDO::FETCH_ASSOC);
$room_name = $room_data['room_name'];
$room_no = $room_data['room_no'];

$querySection = "SELECT grade_sec FROM lvlsec WHERE lvlsec_id = :section_id";
$stmtSection = $dbh->prepare($querySection);
$stmtSection->execute([':section_id' => $section_id]);
$section_name = $stmtSection->fetchColumn();

// Insert the schedule into the database
$insertQuery = "
    INSERT INTO schedules (fac_id, fac_name, subj_id, subj_name, room_id, room_name, room_no, sec_id, sec_name, day, start_time, end_time)
    VALUES (:fac_id, :fac_name, :subj_id, :subj_name, :room_id, :room_name, :room_no, :sec_id, :sec_name, :day, :start_time, :end_time)
";

$insertStmt = $dbh->prepare($insertQuery);
$insertStmt->execute([
    ':fac_id' => $instructor_id,
    ':fac_name' => $instructor_name,
    ':subj_id' => $subject_id,
    ':subj_name' => $subject_name,
    ':room_id' => $room_id,
    ':room_name' => $room_name,
    ':room_no' => $room_no,
    ':sec_id' => $section_id,
    ':sec_name' => $section_name,
    ':day' => $day,
    ':start_time' => $start_time,
    ':end_time' => $end_time
]);

echo json_encode(["conflict" => false, "message" => "Schedule created successfully!"]);
?>