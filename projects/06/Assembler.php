<?php 
// php 7.1
//include "constants.php";

define(
    "COMP",
    [
        "0"   => "0101010",
        "1"   => "0111111",
        "-1"  => "0111010",
        "D"   => "0001100",
        "A"   => "0110000",
        "!D"  => "0001101",
        "!A"  => "0110001",
        "-D"  => "0001111",
        "-A"  => "0110011",
        "D+1" => "0011111",
        "A+1" => "0110111",
        "D-1" => "0001110",
        "A-1" => "0110010",
        "D+A" => "0000010",
        "D-A" => "0010011",
        "D&A" => "0000000",
        "D|A" => "0010101",
        "M"   => "1110000",
        "!M"  => "1110001",
        "-M"  => "1110011",
        "M+1" => "1110111",
        "M-1" => "1110010",
        "D+M" => "1000010",
        "D-M" => "1010011",
        "M-D" => "1000111",
        "D&M" => "1000000",
        "D|M" => "1010101"
    ]
);
define(
    "DEST",
    [
        null => "000",
        "M"    => "001",
        "D"    => "010",
        "MD"   => "011",
        "A"    => "100",
        "AM"   => "101",
        "AD"   => "110",
        "AMD"  => "111"
    ]
);
define(
    "JUMP",
    [
        null => "000",
        "JGT"  => "001",
        "JEQ"  => "010",
        "JGE"  => "011",
        "JLT"  => "100",
        "JNE"  => "101",
        "JLE"  => "110",
        "JMP"  => "111"
    ]
);
define(
    "COMMAND_TYPE",
    [
        "A_COMMAND",
        "C_COMMAND",
        "L_COMMAND"
    ]
);

class Parser {
    private $file;
    private $currentLine;

    public function __construct($filePath) {
        $this->file = fopen($filePath, 'r');
    }

    public function getCurrentLine() {
	    return $this->currentLine;
    }

    public function hasMoreCommands() {
	    $hasMore = !feof($this->file);
	    if (!$hasMore) {
		fclose($this->file);
	    }
	    return $hasMore;
    }

    public function advance() : void {
	    $currentLine = fgets($this->file);
	    $this->currentLine = substr($currentLine, 0, strcspn($currentLine, "//"));
    }
    
    public function commandType() {
	    if (substr(trim($this->currentLine),0,1) == "@") {
		    return COMMAND_TYPE[0];
	    } else if (substr(trim($this->currentLine),0,1) == "(") {
		    return COMMAND_TYPE[2];
	    }
	    return COMMAND_TYPE[1];
    }

    public function symbol() {
	   $mnemonic = str_replace(["@", "(", ")"], "", $this->currentLine);
	   return trim($mnemonic);
    }

    public function dest() {
	   $mnemonic = null;
	   if (strpos($this->currentLine, "=")) {
	   	$mnemonic = explode("=", $this->currentLine)[0];
	   }
	   return trim($mnemonic);
    }

    public function comp() {
	   $mnemonic = explode("=", $this->currentLine)[1];
	   if ($mnemonic == null) {
	   	$mnemonic = explode(";", $this->currentLine)[0];
	   }
	   return trim($mnemonic);
    }
    public function jump() {
	   $mnemonic = explode(";", $this->currentLine)[1];
	   return trim($mnemonic);
    
    }
}

class Code {
    public function getBinaryFromMnemonic($comp, $dest, $jump) {
	    return $binaryCode = "111" . $this->comp($comp) . $this->dest($dest) . $this->jump($jump)."\n";
    }
    public function getBinaryFromDecimal($decimal) {
	    return str_pad(decbin($decimal), 16, 0, STR_PAD_LEFT)."\n";
    }
    private function dest($mnemonic) {
	    return DEST[$mnemonic];
    }
    private function comp($mnemonic) {
	    return COMP[$mnemonic];
    }
    private function jump($mnemonic) {
	    return JUMP[$mnemonic];
    }
}

class SymbolTable {
    private $symbolTable;
    // available RAMAddress
    private $RAMAddress = 16;

    public function __construct() {
	    $this->symbolTable = [
		    "SP" => "0",
		    "LCL" => "1",
		    "ARG" => "2",
		    "THIS" => "3",
		    "THAT" => "4",
		    "SCREEN" => "16384",
		    "KEYBOARD" => "24576",
		    "R0" => "0",
		    "R1" => "1",
		    "R2" => "2",
		    "R3" => "3",
		    "R4" => "4",
		    "R5" => "5",
		    "R6" => "6",
		    "R7" => "7",
		    "R8" => "8",
		    "R9" => "9",
		    "R10" => "10",
		    "R11" => "11",
		    "R12" => "12",
		    "R13" => "13",
		    "R14" => "14",
		    "R15" => "15"
	    ];
    }
    public function addEntry(string $symbol, $address = null) {
	    if ($address != null) {
	    	$this->symbolTable[$symbol] = $address;
	    } else {
		$this->symbolTable[$symbol] = $this->RAMAddress;
	    	$this->RAMAddress++;
	    }
    }
    public function contains($symbol) {
	    return $this->symbolTable[$symbol] != null;
    }
    public function getAddress($symbol) {
	    if ($this->contains($symbol)) {
	        return $this->symbolTable[$symbol];
	    }
	    return false;
    }
}


// アセンブリファイル
$filePath = $argv[1];
$parser = new Parser($filePath);
$code = new Code();
$symbolTable = new SymbolTable();


// store the ROM address of L symbol
$ROMAddress = -1;
while ($parser->hasMoreCommands()) {
	$parser->advance();
	if (substr(trim($parser->getCurrentLine()), 0, 2) ==  "//" || rtrim($parser->getCurrentLine()) ==  "") {
		continue;
	}
	// count the ROM address
	if ($parser->commandType() == COMMAND_TYPE[2]) {
		$symbolTable->addEntry($parser->symbol(), $ROMAddress+1);
	} else {
		$ROMAddress++;
	}
}

$parser = new Parser($filePath);
// 機械語に変換したものを書き込むファイル
$hackFile = fopen("./decodedCode.hack", "w");
while ($parser->hasMoreCommands()) {
	$parser->advance();
	if (substr(trim($parser->getCurrentLine()), 0, 2) ==  "//" || rtrim($parser->getCurrentLine()) ==  "" || substr(trim($parser->getCurrentLine()),0,1) == "(") {
		continue;
	}
	switch ($parser->commandType()) {
		case COMMAND_TYPE[0]:
			if (is_numeric($parser->symbol())) {
				$address = $parser->symbol();
			} else {
				$address = $symbolTable->getAddress($parser->symbol());
				if ($address == null) {
					$symbolTable->addEntry($parser->symbol());
					$address = $symbolTable->getAddress($parser->symbol());
				} else {
					print($parser->symbol(). ": ". $address ."\n");
				}

			}
			$binaryCode = $code->getBinaryFromDecimal($address);
			break;
		case COMMAND_TYPE[1]:
			$binaryCode = $code->getBinaryFromMnemonic($parser->comp(), $parser->dest(), $parser->jump());
			break;
	}
	// ファイルに書き込む
	fwrite($hackFile, $binaryCode);
}
fclose($hackFile);
?>
