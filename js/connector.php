<?php 
header_remove("X-Frame-Options");

include("settings.php");
include("functions.php");

/* POST requests */
if($_POST['type'] === "refreshToken")
{
    $json_data = getNewToken($_POST['token']);
    if(!isset($json_data['data']['access_token']))
    {
        die("error");
    }
    setcookie("access_token", $json_data['data']['access_token'],time()+3600);
    setcookie("refresh_token", $json_data['data']['refresh_token'],time()+36000000);
    die("success");
}
elseif($_POST['type'] === "register")
{
    $curl = curl_init();
    $url = "https://live.timeclock365.com/api/v6/registration";
    $arr = array("company_name" => $_POST["company_name"],"first_name"=>$_POST["first_name"],"last_name"=>$_POST["last_name"],
    "number_of_employees" => intval($_POST['number_of_employees']),"phone"=>$_POST['phone'],"email"=> $_POST["email"],"timezone" => $_POST["timezone"],
    "recaptcha_response"=>$_POST["recaptcha_response"],"privacy_policy_accepted"=>true,"source"=>"ms_teams","partner"=>"NP17448R");
    $content = '{ 
        "company_name": "'.$_POST['company_name'].'",
        "first_name": "' . $name[0] . '",
        "last_name": "' . $name[1] . '",
        "number_of_employees": '.$_POST['employees'] . '",
        "phone": "' . $_POST['phone_number'] . '",
        "email": "' . $_POST['email'] . '",
        "timezone": "' . $_POST['timezone'] . '",
        "country": "' . $_POST['country'].'",
        "recaptcha_response": "' . $_POST['captcha'] . '",
        "privacy_policy_accepted": true,
        "partner": "NP17448R",
    }';

    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTFIELDS => json_encode($arr),
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    die($response);
}
elseif($_POST['type'] === "mission_start")
{
    $curl = curl_init();
    $task_id = $_POST["task_id"];
    $url = "https://live.timeclock365.com/api/v3/mission/" . $task_id ."/start";
    $content = '{ "date": ' . $_POST["date"] . '}';

    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTFIELDS => $content,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $_COOKIE['access_token']
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    die($response);
}
elseif($_POST['type'] === "mission_stop")
{
    $curl = curl_init();
    $task_id = $_POST["task_id"];
    $url = "https://live.timeclock365.com/api/v3/mission/" . $task_id . "/pause";
    $content = '{ "date": ' . $_POST["date"] . '}';

    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTFIELDS => $content,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $_COOKIE['access_token']
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    die($response);
}
elseif($_POST['type'] === "timeoff")
{
    $curl = curl_init();
    $url = "https://live.timeclock365.com/api/v4/sessions/absence";
    $content = '{ "started_at": "' . $_POST['started_at'] . '", "finished_at": "'. $_POST['finished_at'] . '", "type": "' . $_POST['absence_type'] . '"}';

    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTFIELDS => $content,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $_COOKIE['access_token']
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    die($response);
}
elseif($_POST['type'] === "getOnlineEmployees")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://live.timeclock365.com/api/v4/employees/online?amount=1000",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        die($response);
}
elseif($_POST['type'] === "getFinishedEmployees")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://live.timeclock365.com/api/v5/employees/finished?amount=1000",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        die($response);
}
elseif($_POST['type'] === "gettoken" || $_POST['type'] === "login")
{
	$url = 'https://live.timeclock365.com/api/oauth/v2/token';
    $data = array('grant_type' => 'password', 'client_id' => $client_id, 'client_secret' => $client_secret, 'username' => $_POST['username'], 'password' => $_POST['password']);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) 
    { 
        if($_POST['type'] == "login")
        {
            die('error');
        }
        echo 'Invalid username/password combination';
    }
    else
    {
        $json_data = json_decode($result,true);
        setcookie("access_token", $json_data['data']['access_token'],time()+3600);
        setcookie("refresh_token", $json_data['data']['refresh_token'],time()+36000000);
        if($_POST['type'] == "login")
        {
            die('success');
        }
    }
    echo '<script>window.location.href = "https://www.timeclock365.com/msteams/";</script>';
}
else if($_POST['type'] === "punchin" || $_POST['type'] === "punchout")
{
    $curl = curl_init();
    $content = "{}";
    if($_POST['type'] == "punchin")
    {
        $url = "https://live.timeclock365.com/api/v4/sessions/start";
        $content = '{ "location_type": "' . $_POST['location'] .'", "source":"microsoft_teams" }';
    }
    else
    {
        $url = "https://live.timeclock365.com/api/v4/sessions/end";
    }

    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POSTFIELDS => $content,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $_COOKIE['access_token']
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}
/* GET requests */
elseif($_GET['type'] === "absenceTypes")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://live.timeclock365.com/api/v4/absences",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        die($response);
}
elseif($_GET['type'] === "timesheet")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://live.timeclock365.com/api/v5/time-sheet",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        die($response);
}
elseif($_GET['type'] === "settings")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://live.timeclock365.com/api/v3/settings",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $_COOKIE['access_token']
        )
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        die($response);
}
elseif($_GET['type'] === "getAddress")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.geonames.org/findNearbyJSON?lat=".$_GET['lat']."&lng=".$_GET['lng']."&username=portaltimeclock365",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        die($response);
}
elseif($_GET['type'] === "gettasks")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://live.timeclock365.com/api/v3/missions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => "{}",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        die($response);
}
elseif($_GET['type'] === "getInfo")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://live.timeclock365.com/api/v3/user-info',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response,true);
        $date = isset($json['data']['last_session']) ? $json['data']['last_session']['start_date'] : 'null';
        $loc = isset($json['data']['last_session']) ? $json['data']['last_session']['location_type'] : 'null';
        $array = array(
            'name' => $json['data']['full_name'], 
            'punched_in' => $json['data']['last_session'] != null,
            'start_date' => $date,
            'location' => $loc
        );
        echo json_encode($array);
        //echo $json['data']['last_session'] != null ? 'true' : 'false';
}
elseif($_GET['type'] === "settings")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://live.timeclock365.com/api/v3/user-settings',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        die($response);
}
elseif($_GET['type'] === "missions")
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://live.timeclock365.com/api/v3/missions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $_COOKIE['access_token']
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        die($response);
}
else
{
    die("Invalid request.");
}

?>