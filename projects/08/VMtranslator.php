<?php 
require("Parser.php");
require("CodeWriter.php");

$filePath = $argv[1];

$fileDirectory = opendir($filePath);
$directoryName = pathinfo($filePath)["basename"];
$vmFileList = [];
while($fileName = readdir($fileDirectory)) {
	if ($fileName != '.' && $fileName != '..' && preg_match('/\.(vm)$/i', $fileName)) {
		$vmFileList[] = $fileName;
	  }
}
closedir($fileDirectory);

$wFilePath = $filePath.$directoryName.".asm";
$writer = new CodeWriter(fopen($wFilePath, 'w'));
foreach ($vmFileList as $vmFile) {
	$id = 0;
	$parser = new Parser(fopen($filePath.$vmFile, 'r'));
	while ($parser->hasMoreCommands()) {
		$parser->advance();
		$commandType = $parser->commandType();
		switch($commandType) {
			case "C_PUSH":
			case "C_POP":
				$writer->writePushPop($id, $commandType, $parser->arg1(), $parser->arg2());
				$id++;
				break;
			case "C_ARITHMETIC":
				$writer->writeArithmetic($id, $parser->arg1());
				$id++;
				break;
			case "C_LABEL":
				$writer->writeLabel($parser->arg1());
				break;
			case "C_IF":
				$writer->writeIf($parser->arg1());
				break;
			case "C_GOTO":
				$writer->writeGoto($parser->arg1());
				break;
			case "C_FUNCTION":
				$writer->writeFunction($parser->arg1(),$parser->arg2());
				break;
			case "C_RETURN":
				$writer->writeReturn();
				break;
		}
	}
}

$writer->close();
$parser->close();
?>
