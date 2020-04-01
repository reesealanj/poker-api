<?php
// Headers required
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// Include DB Connection and Session model
include_once '../../config/Database.php';
include_once '../../models/Sessions.php';
include_once '../../models/Users.php';

$database = new Database();
$db = $database->getConnection();
$session = new Sessions($db);

$data = json_decode(file_get_contents("php://input")); 

if (
    !empty($data->session) && 
    !empty($data->password) &&
    !empty($_GET['form'])
) {
    $user = new Users($db);
    $user->session_id = $user->sanitize($data->session);
    $user->password = $user->sanitize($data->password);
    $session->session_id = $user->session_id;

    $reqForm = $user->sanitize($_GET['form']);
    /*  
        1. authenticate user/session combination ($session->authValidateSession())
        2. get all assoc. keys with session
            2a. list of all games
            2b. list of all hands
    */
    if($session->authValidateSession($user->session_id, $user->password)){
        if($reqForm == "all" || $reqForm == "active") {
            $session_data = $session->status($reqForm);
            if(!empty($session_data)) {
                http_response_code(200);
                $arr = array_merge(array("message" => "Session Status Information Returned"), $session_data);
                echo json_encode($arr);
                return;
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Unable to check Session status", "issue" => "Error gathering session information"));
                return;
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to check Session status", "issue" => "Invalid format type supplied"));
            return;
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Unable to check Session status", "issue" => "Unable to authenticate session id and password provided"));
        return;
    }
} else {
    // did not supply session information
    http_response_code(400);
    echo json_encode(array("message" => "Unable to check Session status", "issue" => "Data Incomplete"));
    return;
}