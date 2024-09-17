<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$link = mysqli_connect('localhost', 'root', '', 'db');
mysqli_set_charset($link, 'utf8');

$requestMethod = $_SERVER["REQUEST_METHOD"];

// GET METHOD
if ($requestMethod == 'GET') {
    if (isset($_GET['course_code']) && !empty($_GET['course_code'])) {
        $course_code = mysqli_real_escape_string($link, $_GET['course_code']);
        $sql = "SELECT * FROM exam_result WHERE course_code = '$course_code'";
    } else {
        $sql = "SELECT * FROM exam_result";
    }

    $result = mysqli_query($link, $sql);

    $arr = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }

    echo json_encode($arr);
}

// POST METHOD
elseif ($requestMethod == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!empty($data)) {
        $course_code = mysqli_real_escape_string($link, $data['course_code']);
        $student_code = mysqli_real_escape_string($link, $data['student_code']);
        $point = mysqli_real_escape_string($link, $data['point']);
        $sql = "INSERT INTO exam_result (course_code, student_code, point) VALUES ('$course_code', '$student_code','$point')";
        $result = mysqli_query($link, $sql);
        
        if ($result) {
            echo json_encode(['status' => 'ok', 'message' => 'Insert Data Complete']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting data']);
        }
    }
}

// PUT METHOD
elseif ($requestMethod == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!empty($data)) {
        $course_code = mysqli_real_escape_string($link, $data['course_code']);
        $student_code = mysqli_real_escape_string($link, $data['student_code']);
        $point = mysqli_real_escape_string($link, $data['point']);
        
        $sql = "UPDATE exam_result SET student_code = '$student_code', point = '$point' WHERE course_code = '$course_code'";
        $result = mysqli_query($link, $sql);
        
        if ($result) {
            echo json_encode(['status' => 'ok', 'message' => 'Update Data Complete']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating data']);
        }
    }
}

// DELETE METHOD
elseif ($requestMethod == 'DELETE') {
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($link, $_GET['id']);

        $sql = "DELETE FROM exam_result WHERE id = '$id'";
        $result = mysqli_query($link, $sql);

        if ($result) {
            echo json_encode(['status' => 'ok', 'message' => 'Delete Data Complete']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting data']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID not provided']);
    }
}

// Close database connection
mysqli_close($link);
?>
