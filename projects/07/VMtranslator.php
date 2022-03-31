<?php 
require("Parser.php");
require("CodeWriter.php");

$filePath = $argv[1];
$parser = new Parser(fopen($filePath, 'r'));
$wFilePath = str_replace(".vm", ".asm", $filePath);
$writer = new CodeWriter(fopen($wFilePath, 'w'));
while ($parser->hasMoreCommands()) {
	$parser->advance();
	$commandType = $parser->commandType();
	switch($commandType) {
		case "C_PUSH":
		case "C_POP":
			$writer->writePushPop($commandType, $parser->arg1(), $parser->arg2());
			break;
		case "C_ARITHMETIC":
			$writer->writeArithmetic($parser->arg1());
			break;
	}
}

$writer->close();
$parser->close();
?>
