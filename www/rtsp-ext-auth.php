<?php
$host = "127.0.0.1";
$username = "rtmp";
$password = "rtmp";
$dbname = "rtmp";
$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!empty($data['ip']))
{
	$ip = filter_var($data['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}
if (!empty($data['user']))
{
	$user = filter_var($data['user'], FILTER_SANITIZE_STRING);
}
if (!empty($data['password']))
{
	$password = filter_var($data['password'], FILTER_SANITIZE_STRING);
}
if (!empty($data['path']))
{
	$path = filter_var($data['path'], FILTER_SANITIZE_STRING);
}
if (!empty($data['action']))
{
	$action = filter_var($data['action'], FILTER_SANITIZE_STRING);
}

function actionRead ($db, $ipv4, $name, $key, $streampath) {
	if (empty($name) || empty($key))
	{
		http_response_code(401);
	}

	if ($ipv4 == "127.0.0.1")
	{
		http_response_code(200);
	}
	else
	{
		try
		{
			$qInfoKey = "SELECT viewers FROM users";
			$qInfoKeyPrepared = $db->prepare($qInfoKey);
			$qInfoKeyPrepared->execute();
			$qInfoResults = $qInfoKeyPrepared->fetch(PDO::FETCH_ASSOC);
		}
		catch (PDOException $e)
		{
			http_response_code(401);
		}

		$viewers = json_decode($qInfoResults['viewers'], true);

		if (array_key_exists($name, $viewers)) {
			if ($viewers[$name] == $key) {
				http_response_code(200);
			}
			else
			{
				http_response_code(401);
			}
		}
		else
		{
			http_response_code(401);
		}
	}
}

function actionPublish ($db, $ipv4, $name, $key, $streampath) {
	if (empty($name) || empty($key))
	{
		http_response_code(401);
	}

	if ($ipv4 == "127.0.0.1")
	{
		http_response_code(200);
	}
	else
	{
		try
		{
			$qInfoKey = "SELECT stream_user, stream_pass, stream_path FROM users WHERE stream_pass = :stream_pass";
			$qInfoKeyPrepared = $db->prepare($qInfoKey);
			$qInfoKeyPrepared->execute(array(':stream_pass' => $key));
			$qInfoResults = $qInfoKeyPrepared->fetch(PDO::FETCH_ASSOC);
		}
		catch (PDOException $e)
		{
			http_response_code(401);
		}

		if (($name == $qInfoResults['stream_user']) && ($key == $qInfoResults['stream_pass']))
		{
			if ($streampath == $qInfoResults['stream_path'])
			{
				http_response_code(200);
			}
			else
			{
				http_response_code(401);
			}
		}
	}
}

switch($action){
	case "read":
		if (!empty($ip) && !empty($user) && !empty($password) && !empty($path))
		{
			actionRead($dbh, $ip, $user, $password, $path);
		}
		else
		{
			http_response_code(401);
		}
		break;
	case "publish":
		if (!empty($ip) && !empty($user) && !empty($password) && !empty($path))
		{
			actionPublish($dbh, $ip, $user, $password, $path);
		}
		else
		{
			http_response_code(401);
		}
		break;
	default:
		http_response_code(401);
		break;
}
?>
