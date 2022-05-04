<?php
require("JackTokenizer.php");
require("CompilationEngine.php");
require("SYmbolTable.php");

$filePath = $argv[1];


//／ディレクトリの場合
// ディレクトリに含まれるjackファイルリストを取得する
if (is_dir($filePath)) {
    $fileDirectory = opendir($filePath);
    $directoryName = pathinfo($filePath)["basename"];
    $jackFilePathList = [];
    while($fileName = readdir($fileDirectory)) {
    	if ($fileName != '.' && $fileName != '..' && preg_match('/\.(jack)$/i', $fileName)) {
    		$jackFilePathList[] = $filePath.$fileName;
    	  }
    }
    closedir($fileDirectory);
} else {
    $jackFilePathList[] = $filePath;
}

foreach ($jackFilePathList as $jackFilePath) {
    $tokens = [];
    $compilationEngine = new CompilationEngine($jackFilePath);
}

?>