<?php
include('includes/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['instructor'])) {
    $instructor = trim($_POST['instructor']);
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 5; // Default to 5 rows per page
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Default to page 1

    if (empty($instructor) || !is_numeric($instructor)) {
        echo json_encode(['error' => 'Invalid instructor ID.']);
        exit;
    }

    try {
        // Calculate the offset
        $offset = ($page - 1) * $limit;

        // Fetch schedules with LIMIT and OFFSET
        $sql = "SELECT 
                    subj_name AS subject, 
                    CONCAT(room_name, ' ', room_no) AS room, 
                    sec_name AS section, 
                    day, 
                    start_time, 
                    end_time 
                FROM schedules
                WHERE fac_id = :instructor
                ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), start_time
                LIMIT :limit OFFSET :offset";

        $query = $dbh->prepare($sql);
        $query->bindParam(':instructor', $instructor, PDO::PARAM_INT);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        // Fetch total count
        $countQuery = "SELECT COUNT(*) AS total FROM schedules WHERE fac_id = :instructor";
        $countStmt = $dbh->prepare($countQuery);
        $countStmt->bindParam(':instructor', $instructor, PDO::PARAM_INT);
        $countStmt->execute();
        $totalCount = $countStmt->fetch(PDO::FETCH_OBJ)->total;

        // Return data
        echo json_encode([
            'data' => $results,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'An error occurred while fetching the schedule.']);
        error_log("Error fetching schedule: " . $e->getMessage());
    }
}
?>