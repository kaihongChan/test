<?php

$file_arr = [];//保存文件名
$dir = dir('/Users/kaihongchan/PhpstormProjects'); //目录
dir2Arr($dir);
function dir2Arr($dir)
{
	while ($file = $dir->read()) { //循环目录
		if ($file != ".." && $file != "." && $file != '.DS_Store') {//判断不为返回上级
			$file_arr[] = $file;
			var_dump($dir . '/' . $file);
//			dir2Arr($dir . '/' . $file);
		}
	}
}

//数组
var_dump($file_arr);