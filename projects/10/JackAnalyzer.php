<?php
require("JackTokenizer.php");

$filePath = $argv[1];


//／ディレクトリの場合
// ディレクトリに含まれるjackファイルリストを取得する
if (is_dir($filePath)) {
    $fileDirectory = opendir($filePath);
    $directoryName = pathinfo($filePath)["basename"];
    $jackFilePathList = [];
    while($fileName = readdir($fileDirectory)) {
    	if ($fileName != '.' && $fileName != '..' && preg_match('/\.(jack)$/i', $fileName)) {
    		$jackFilePathList[] = $filePath.$jackFileName;
    	  }
    }
    closedir($fileDirectory);
} else {
    $jackFilePathList[] = $filePath;
}

//ディレクトリじゃない場合

foreach ($jackFilePathList as $jackFilePath) {
    $tokens = [];
    $tokenizer = new JackTokenizer($jackFilePath);
    exit;
}

?>