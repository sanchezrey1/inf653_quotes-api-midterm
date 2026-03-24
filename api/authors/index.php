<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    $result = $author->read($id);

    if ($result->rowCount() > 0) {
        $authors_arr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $authors_arr[] = array(
                'id' => $row['id'],
                'author' => $row['author']
            );
        }

        echo json_encode($authors_arr);
    } else {
        echo json_encode(array('message' => 'author_id Not Found'));
    }
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->author) || empty(trim($data->author))) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    $author->author = trim($data->author);
    $result = $author->create();

    echo json_encode($result);
}

if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset($data->id) ||
        !isset($data->author) ||
        empty(trim($data->author))
    ) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    $author->id = (int)$data->id;
    $author->author = trim($data->author);

    $result = $author->update();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array('message' => 'author_id Not Found'));
    }

    if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    $author->id = (int)$data->id;
    $result = $author->delete();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array('message' => 'author_id Not Found'));
    }
  }
}
?>