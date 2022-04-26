<?php
require("JackTokenizer.php");

// $wFilePath = $filePath.$directoryName.".asm";
// $writer = new CodeWriter(fopen($wFilePath, 'w'));
// $writer->writeInit();
// foreach ($vmFileList as $vmFile) {
// 	$id = 0;
// 	$parser = new Parser(fopen($filePath.$vmFile, 'r'));
// 	$writer->setFileName($vmFile);
// 	while ($parser->hasMoreCommands()) {
// 		$parser->advance();
// 		$commandType = $parser->commandType();
// 		switch($commandType) {
// 			case "C_PUSH":
// 			case "C_POP":
// 				$writer->writePushPop($id, $commandType, $parser->arg1(), $parser->arg2());
// 				$id++;
// 				break;
// 		}
// 	}
// 	$parser->close();
// }

// $writer->close();


$filePath = $argv[1];

// ディレクトリに含まれるjackファイルリストを取得する
$fileDirectory = opendir($filePath);
$directoryName = pathinfo($filePath)["basename"];
$jackFileList = [];
while($fileName = readdir($fileDirectory)) {
	if ($fileName != '.' && $fileName != '..' && preg_match('/\.(jack)$/i', $fileName)) {
		$jackFileList[] = $fileName;
	  }
}
closedir($fileDirectory);

foreach ($jackFileList as $jackFileName) {
    $tokens = [];
    $tokenizer = new JackTokenizer($filePath.$jackFileName);
    exit;
}

?>