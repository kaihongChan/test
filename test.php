<?php

$conn = new mysqli('127.0.0.1:3306', 'root', 'root', 'test');

// 检测连接
if ($conn->connect_error) {
	die("连接失败: " . $conn->connect_error);
}

clearstatcache();

$dir = '/Users/kaihongchan/Downloads/templates'; // 目录
$bladesDir = '/Users/kaihongchan/Downloads/templates/blades';
$templatesDir = '/Users/kaihongchan/Downloads/templates/imgs';
$bgsDir = '/Users/kaihongchan/Downloads/templates/bgs';

function dirToArray($dir)
{
	$result = [];

	$cdir = scandir($dir);

	foreach ($cdir as $key => $value) {
		if (!in_array($value, ['.', '..', '.DS_Store'])) {

			if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {

				$result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);

			} else {
				$result[] = $dir . DIRECTORY_SEPARATOR . $value;
			}

		}

	}

	return $result;
}

$dirArr = dirToArray($dir);

foreach ($dirArr as $key => $value) {
	$templateDir = $dir . '/' . $key . '/';
	foreach ($value as $k => $v) {
		if (!is_array($v)) {
			$fileArr = pathinfo($v);
			$filename = str_replace($templateDir, '', $v);
			if (isset($fileArr['extension']) && $fileArr['extension'] == 'jpeg') {
//				copy($v, $templatesDir . '/' . $filename);
			} elseif (isset($fileArr['extension']) && $fileArr['extension'] == 'txt') {
				// 读取文件
				$txtContent = file($v, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				[, $nVal] = explode(':', $txtContent[0]);
				[, $oVal] = explode(':', $txtContent[1]);
				$sql = "INSERT INTO `test` (`name`, `other`) VALUES ('{$nVal}', '{$oVal}');";

				$conn->query($sql);
				$templateId = $conn->insert_id;
				var_dump($templateId);
			}
		}

		if ($k === 'bg') {
			foreach ($v as $kk => $vv) {
				$bgDir = $templateDir . 'bg/' . $kk . '/';
				foreach ($vv as $kkk => $vvv) {
					$fileArr = pathinfo($vvv);
					$filename = str_replace($bgDir, '', $vvv);
					if (isset($fileArr['extension']) && $fileArr['extension'] == 'jpeg') {
						copy($vvv, $bgsDir . '/' . $filename);
					} elseif (isset($fileArr['extension']) && $fileArr['extension'] == 'txt') {
						// 读取文件
						$txtContent = file($vvv);
					}
				}
			}
		}

		if ($k === 'blades') {
			foreach ($v as $kk => $vv) {
				$bgDir = $templateDir . 'blades/' . $kk . '/';
				foreach ($vv as $kkk => $vvv) {
					$fileArr = pathinfo($vvv);
					$filename = str_replace($bgDir, '', $vvv);
					if (isset($fileArr['extension']) && $fileArr['extension'] == 'jpeg') {
//						copy($vvv, $bladesDir . '/' . $filename);
					} elseif (isset($fileArr['extension']) && $fileArr['extension'] == 'txt') {
						// 读取文件
						$txtContent = file_get_contents($vvv);
					}
				}
			}
		}
	}
}
