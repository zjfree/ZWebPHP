<?php 

$url = @$_GET['url'] ?: '';
if (empty($url))
{
	exit('ERROR:url is null!');
}

// create curl resource 
$ch = curl_init(); 

// set url 
curl_setopt($ch, CURLOPT_URL, $url); 

//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

curl_setopt($ch, CURLOPT_TIMEOUT, 5);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding:gzip'));
curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

//设置post方式提交
if (!empty($_POST))
{
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
}

// $output contains the output string 
$output = curl_exec($ch); 

if (curl_errno($ch) != 0)
{
	echo 'ERROR:' . curl_error($ch);
}
else
{
	echo $output;
}

curl_close($ch); 

