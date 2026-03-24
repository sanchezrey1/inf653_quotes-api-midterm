<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

    if ($id !== null || $author_id !== null || $category_id !== null) {
        $result = $quote->read_filtered($id, $author_id, $category_id);
    } else {
        $result = $quote->read();
    }

    if ($result->rowCount() > 0) {
        $quotes_arr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quote_item = array(
                'id' => $row['id'],
                'quote' => $row['quote'],
                'author' => $row['author'],
                'category' => $row['category']
            );

            $quotes_arr[] = $quote_item;
        }

        echo json_encode($quotes_arr);
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset($data->quote) || empty(trim($data->quote)) ||
        !isset($data->author_id) ||
        !isset($data->category_id)
    ) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    if (!$quote->authorExists($data->author_id)) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit;
    }

    if (!$quote->categoryExists($data->category_id)) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit;
    }

    $quote->quote = trim($data->quote);
    $quote->author_id = (int)$data->author_id;
    $quote->category_id = (int)$data->category_id;

    $result = $quote->create();

    echo json_encode($result);
}

if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset($data->id) ||
        !isset($data->quote) || empty(trim($data->quote)) ||
        !isset($data->author_id) ||
        !isset($data->category_id)
    ) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    if (!$quote->authorExists($data->author_id)) {
        echo json_encode(array('message' => 'author_id Not Found'));
        exit;
    }

    if (!$quote->categoryExists($data->category_id)) {
        echo json_encode(array('message' => 'category_id Not Found'));
        exit;
    }

    $quote->id = (int)$data->id;
    $quote->quote = trim($data->quote);
    $quote->author_id = (int)$data->author_id;
    $quote->category_id = (int)$data->category_id;

    $result = $quote->update();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }

    if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id)) {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit;
    }

    $quote->id = (int)$data->id;
    $result = $quote->delete();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }
  }
}
?>