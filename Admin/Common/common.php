<?php

function out_log($key,$value){
	
	$dir = './log/';
	
	if(!is_dir($dir)){
		
		mkdir($dir);
	}
	
	$filename = $dir.$key.'.log';
	
	file_put_contents($filename,$value.'');	
}