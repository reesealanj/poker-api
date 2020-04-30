<?php
// Headers Required
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// Include DB Connection and User Model
include_once '../../config/Database.php';
include_once '../../models/Users.php';

$database = new Database();
$db = $database->getConnection();
$user = new Users($db);

if (
    isset($_GET['api_key']) &&
    isset($_GET['user_id'])
) {
    $api_key = htmlspecialchars(strip_tags($_GET['api_key']));
    $user_id = htmlspecialchars(strip_tags($_GET['user_id']));

    if ($user->is_admin($api_key)) {
        if ($user->id_new_key($user_id)) {
            http_response_code(200);
            echo json_encode(array("message" => "New Key Sucessfully Generated", "new_key" => $user->api_key));
            return;
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to generate new key for User", "issue" => "Internal Server Error"));
            return;
        }
    } else {
        http_response_code(403);
        echo json_encode(array("message" => "Unable to generate new key for User", "issue" => "User requesting change is not admin!"));
        return;
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to generate new key for User", "issue" => "Data Incomplete"));
    return;
}

?>