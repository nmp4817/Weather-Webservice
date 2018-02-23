<?php

function get_data_by_id($city_id) {

	$result = check_cache([$city_id],["id"]);

	if( !empty($result) ) {
		
		return $result;

	} else {
		
		$response = file_get_contents(BASE_URL."id=$city_id&units=".UNITS."&APPID=".APPID);

		$response = json_decode($response,true);

		return parseResponse($response);
	}
}

function get_data_by_coord($lat, $lon) {

	$result = check_cache([$lat,$lon],["lat","lon"]);

	if( !empty($result) ) {
		
		return $result;

	} else {
		$response = file_get_contents(BASE_URL."lat=$lat&lon=$lon&units=".UNITS."&APPID=".APPID);

		$response = json_decode($response,true);

		return parseResponse($response);
	}
	
}

function get_data_by_name($city_name) {

	$result = check_cache([$city_id],["id"]);

	if( !empty($result) ) {
		
		return $result;

	} else {
		$response = file_get_contents(BASE_URL."q=$city_name&units=".UNITS."&APPID=".APPID);
		
		$response = json_decode($response,true);
		
		return parseResponse($response);
	}
}


function parseResponse($response) {
	if ( $response["cod"] == "200" ) {
		$result["status"] = "200";
		$result["id"] = $response["city"]["id"];
	   	$result["lat"] = $response["city"]["coord"]["lat"];
	   	$result["lon"] = $response["city"]["coord"]["lon"];
	   	$result["country"] = $response["city"]["country"];
	   	$result["name"] = $response["city"]["name"];
		$result["temp"] = intval($response["list"][0]["main"]["temp"]);
		$result["weather"] = $response["list"][0]["weather"][0]["main"];
        $result["wind_speed"] = $response["list"][0]["wind"]["speed"];
        $wind_dir_val = intval(($response["list"][0]["wind"]["deg"]/22.5)+.5);
        $direction = DIR_ARR[($wind_dir_val % 16)];
        $result["wind_direction"] = $direction;

        //appending to cache
        if ( filesize(CACHE_FILE) == 0 ) {
        	$tempArray[] = $result;
        	$jsonData = json_encode($tempArray);
        } else {
        	$inp = file_get_contents(CACHE_FILE);
			$tempArray = json_decode($inp);
			array_push($tempArray, $result);
			$jsonData = json_encode($tempArray);
        }
        
		file_put_contents(CACHE_FILE, $jsonData);
        
        return $result;
		
	} else {
		$result['status'] = "404";
    	$result["error_description"] = "Weather not found!";
    	error_log("Weather not found!");
	}

	return $result;
}

function check_cache($params, $param_types) {

	$time_limit = 30 * 60;

	$result = Array();

	if ( file_exists(CACHE_FILE) ) {

		if ( (time() < (filectime(CACHE_FILE) + $time_limit) ) ) {
		
			$cached_data = json_decode(file_get_contents(CACHE_FILE),true);
			
			//Got Cache file
			for ($i = 0; $i < sizeof($cached_data); $i++) {
				for ($j = 0; $j < sizeof($params); $j++ ) {
					if ( $cached_data[$i][$param_types[$j]] == $params[$j] ) {
						$flag = true;
					} else {
						$flag = false;
						break;
					}
				}

				if ( $flag ) {
					return $cached_data[$i]; 
				}
			}

		} else {
			//File expired
			unlink(CACHE_FILE);
			$handle = fopen(CACHE_FILE, 'w') or die('Cannot open file:  '.CACHE_FILE);
			fclose($handle);
		}
	} else {
		//File does not exist
		$handle = fopen(CACHE_FILE, 'w') or die('Cannot open file:  '.CACHE_FILE);
		fclose($handle);
	}

	return $result;
}