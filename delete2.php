<?php

/*Used to delete programs

X-PETC-A: token
X-PETC-B: key
X-PETC-C: unk, 1 works
*/

include "functions.php";
init(false);

if(!isset($h["X-PETC-B"])){
	die();
}

$key=$h["X-PETC-B"];

$c=getkeydata($key);
if($c!=false || $c["ID"]==$id){
	//http://php.net/manual/en/function.rmdir.php#110489
	function delTree($dir) { 
		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) { 
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
		} 
		return rmdir($dir); 
	} 
	delTree("$key");
	header("X-Petc-FileName: ".$c["Filename"]);
	header("X-Petc-UID: ");
	header("X-Petc-Author: ");
	header("X-Petc-Date: ");
	header("X-Petc-Size: ".$c["filesize"]);
	header("X-Petc-IsSystem: 0");
	header("X-Petc-State: 0");
	header("X-Petc-RefCount: 0");
	echo("OK");
}else{
	$sb=geturl("https://save.smilebasic.com/delete2.php","X-PETC-A: ".$origid."\r\nX-PETC-B: ".$key."\r\nX-PETC-C: ".$h["X-PETC-C"]."\r\n");
	if($response_headers["http_code"]!=200){
		header("X-Petc-ErrorCode: 2");
		http_response_code(400);
		die("Error");
	}else{
		foreach($response_headers as $name=>$val){
			if($name!="http_code" && !is_numeric($name)){
				header($name.": ".$val);
			}
		}
		echo("OK");
	}
}

