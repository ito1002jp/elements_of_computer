<?php 
require("Parser.php");
require("CodeWriter.php");

$filePath = $argv[1];
$parser = new Parser(fopen($filePath, 'r'));
$wFilePath = str_replace(".vm", ".asm", $filePath);
$writer = new CodeWriter(fopen($wFilePath, 'w'));
$id = 0;
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
	}
}

$writer->close();
$parser->close();
?>
