<?php

function get_data_by_id($city_id) {

	$response = file_get_contents(BASE_URL."id=$city_id&units=".UNITS."&APPID=".APPID);

	$response = json_decode($response,true);

	return parseResponse($rersponse);
}

function get_data_by_coord($lat, $lon) {

	$response = file_get_contents(BASE_URL."lat=$lat&lon=$lon&units=".UNITS."&APPID=".APPID);

	$response = json_decode($response,true);

	return parseResponse($rersponse);
	
}

function get_data_by_name($city_name) {

	$response = file_get_contents(BASE_URL."q=$city_name&units=".UNITS."&APPID=".APPID);
	
	$response = json_decode($response,true);
	
	return parseResponse($response);
}


function parseResponse($response) {
	if ( $response["cod"] == "200" ) {
		$result["status"] = "200";
		$result["city"] = $response["city"];
		$result["main"]["temp"] = intval($response["list"][0]["main"]["temp"]);
		$result["main"]["weather"] = $response["list"][0]["weather"][0]["main"];
        $result["main"]["wind"]["speed"] = $response["list"][0]["wind"]["speed"];
        $wind_dir_val = intval(($response["list"][0]["wind"]["deg"]/22.5)+.5);
        $direction = DIR_ARR[($wind_dir_val % 16)];
        $result["main"]["wind"]["direction"] = $direction;
        return $result;
// 		$result["id"] = $city_id;
//    	$result["lat"] = $response["city"]["coord"]["lat"];
//    	$result["lon"] = $response["city"]["coord"]["lon"];
//    	$result["country"] = $response["city"]["country"];
//    	$result["name"] = $response["city"]["name"];
	} else {
		$result['status'] = "404";
    	$result["error_description"] = "Weather not found!";
    	error_log("Weather not found!);
	}

	return $result;
}