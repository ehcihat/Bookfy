<?php
require_once 'classes/response.class.php';
require_once 'classes/book.class.php';

$_response = new Response();
$_book = new Book();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        $book_id = $_GET["id"];
        $bookData = $_book->getBook($book_id);
        echo json_encode($bookData);
    } else if (isset($_GET["featured"])) {
        $featuredBooks = $_book->getFeaturedBooks();
        echo json_encode($featuredBooks);
    } elseif (isset($_GET["author"])) {
        $bookAuthor = $_book->getBookAuthor();
        echo json_encode($bookAuthor);
    } elseif (isset($_GET["getBookByPrice"])) {
        $value = $_GET["getBookByPrice"];
        $sortedBooks = $_book->getBookByPrice($value);
        echo json_encode($sortedBooks);
    } elseif (isset($_GET["getBookByName"])) {
        $bookName = $_GET["getBookByName"];
        $searchedBooks = $_book->getBookByName($bookName);
        echo json_encode($searchedBooks);
    } elseif (isset($_GET["getBookByGenre"])) {
        $bookGenre = $_GET["getBookByGenre"];
        $sortedBooks = $_book->getBookByGenre($bookGenre);
        echo json_encode($sortedBooks);
    } elseif (isset($_GET["getBookByAuthor"])) {
        $bookAuthor = $_GET["getBookByAuthor"];
        $sortedBooks = $_book->getBookByAuthor($bookAuthor);
        echo json_encode($sortedBooks);
    }elseif (isset($_GET["getBookByCategory"])) {
        $bookCategory = $_GET["getBookByCategory"];
        $sortedBooks = $_book->getBookByCategory($bookCategory);
        echo json_encode($sortedBooks);
    }elseif (isset($_GET["getBookByISBN"])) {
        $bookISBN = $_GET["getBookByISBN"];
        $sortedBooks = $_book->getBookByISBN($bookISBN);
        echo json_encode($sortedBooks);
    }
    elseif (!empty($_GET)) {
        header('Content-Type: application/json');
        $arrayData = $_response->error_400();
        echo json_encode($arrayData);
    } else {
        $result = $_book->listBooks();
        echo json_encode($result);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $body = file_get_contents("php://input");
    $bookData = $_book->postBook($body);
    header('Content-Type: application/json');

    if (isset($bookData["result"]["error_id"])) {
        $errorId = $bookData["result"]["error_id"];
      
    } else {
        http_response_code(200);
        echo json_encode($bookData);
        exit;
    }

    switch ($errorId) {
        case '400':
            http_response_code(400);
            break;
        case '406':
            http_response_code(406);
            break;
        case '408':
            http_response_code(408);
            break;
     
        case '409':
            http_response_code(409);
            break;
        case '416':
            http_response_code(416);
            break;
        default:
            http_response_code(500); // Cualquier otro error, establecer código 500 Internal Server Error
            break;
    }
    
    echo json_encode($bookData);
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $body = file_get_contents("php://input");
    $bookData = $_book->putBook($body);

    header('Content-Type: application/json');
    if (isset($bookData["result"]["error_id"])) {
        $errorId = $bookData["result"]["error_id"];

    } else {
        http_response_code(200);
        echo json_encode($bookData);
        exit;
    }

    switch ($errorId) {
        case '400':
            http_response_code(400);
            break;
        case '406':
            http_response_code(406);
            break;
        case '408':
            http_response_code(408);
            break;
     
        case '409':
            http_response_code(409);
            break;
        case '416':
            http_response_code(416);
            break;
        default:
            http_response_code(500); // Cualquier otro error, establecer código 500 Internal Server Error
            break;
    }
 
    echo json_encode($bookData);
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $body = file_get_contents("php://input");
    $bookData = $_book->deleteBook($body);

    header('Content-Type: application/json');
    if (isset($bookData["result"]["error_id"])) {
        $response = $bookData["result"]["error_id"];
        http_response_code($response);
    } else {
        http_response_code(200);
    }
    echo json_encode($bookData);
} else {
    header('Content-Type: application/json');
    $arrayData = $_response->error_405();
    echo json_encode($arrayData);
}
?>