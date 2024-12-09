<?php
include('includes/dbcon.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instructor = isset($_POST['instructor']) ? trim($_POST['instructor']) : null;
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10; // Default to 10 rows per page
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Default to page 1

    try {
        // Calculate the offset
        $offset = ($page - 1) * $limit;

        // Base SQL query to fetch schedules
        $sql = "SELECT 
                    subj_name AS subject, 
                    CONCAT(room_name, ' ', room_no) AS room, 
                    sec_name AS section, 
                    day, 
                    start_time, 
                    end_time 
                FROM schedules";

        // Add instructor filter if provided
        if (!empty($instructor) && is_numeric($instructor)) {
            $sql .= " WHERE fac_id = :instructor";
        }

        $sql .= " ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), start_time
                  LIMIT :limit OFFSET :offset";

        $query = $dbh->prepare($sql);

        // Bind parameters
        if (!empty($instructor) && is_numeric($instructor)) {
            $query->bindParam(':instructor', $instructor, PDO::PARAM_INT);
        }
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);

        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        // Fetch total count
        $countQuery = "SELECT COUNT(*) AS total FROM schedules";
        
        if (!empty($instructor) && is_numeric($instructor)) {
            $countQuery .= " WHERE fac_id = :instructor";
        }

        $countStmt = $dbh->prepare($countQuery);
        if (!empty($instructor) && is_numeric($instructor)) {
            $countStmt->bindParam(':instructor', $instructor, PDO::PARAM_INT);
        }
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