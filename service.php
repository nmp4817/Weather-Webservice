<?php

header("Content-Type:application/json");

require_once '.local/config-local.php';
require_once '.local/authorization.php';
require_once 'api_calls.php';

$result = Array();

/**

if you want to authorize using database
// if ( isset($_POST['usr']) && isset($_POST['pwd']) && authorize_by_db($_POST['usr'],$_POST['pwd']) )

*/
try {
    if ( isset($_POST['secret-key']) && authorize($_POST['secret-key']) ) {
        
        switch($_POST) {

        	case (isset($_POST['city_id']) && !empty($_POST['city_id'])):
        		$city_id = intval($_POST['city_id']);
                $result = get_data_by_id($city_id);
        		break;

            case (isset($_POST['lat']) && isset($_POST['lon']) && !empty($_POST['lat']) && !empty($_POST['lon'])):
                $lat = floatval($_POST['lat']);
                $lon = floatval($_POST['lon']);
                $result = get_data_by_coord($lat, $lon);
                break;

        	case (isset($_POST['city_name']) && !empty($_POST['city_name'])):
        		$city_name = $_POST['city_name'];
                $result = get_data_by_name($city_name);
        		break;    	

        	default:
        		$result['status'] = "400";
        		$result["error_description"] = "Wrong Input!";
        	
        }

    } else {
    	$result['status'] = "400";
        $result["error_description"] = "User not authorized!";
        error_log("User not authorized!");
    }

    $result = json_encode($result,JSON_PRETTY_PRINT);
    echo $result;
} catch(Exception $e) {
    $result['status'] = "error";
    $result['error-description'] = $e->getMessage();
    error_log($e->getMessage());
}