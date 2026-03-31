<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    $result = $category->read($id);

    if ($result->rowCount() > 0) {
        if ($id !== null) {
            $row = $result->fetch(PDO::FETCH_ASSOC);

            echo json_encode(array(
                'id' => $row['id'],
                'category' => $row['category']
            ));
        } else {
            $categories_arr = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $categories_arr[] = array(
                    'id' => $row['id'],
                    'category' => $row['category']
                );
            }

            echo json_encode($categories_arr);
        }
    } else {
        echo json_encode(array('message' => 'category_id Not Found'));
    }
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->category) || empty(trim($data->category))) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    $category->category = trim($data->category);
    $result = $category->create();

    echo json_encode($result);
}

if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset($data->id) ||
        !isset($data->category) ||
        empty(trim($data->category))
    ) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    $category->id = (int)$data->id;
    $category->category = trim($data->category);

    $result = $category->update();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array('message' => 'category_id Not Found'));
    }
}

if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    $category->id = (int)$data->id;
    $result = $category->delete();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array('message' => 'category_id Not Found'));
    }
}
?>