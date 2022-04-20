<?php

class Parser {
    private $file;
    public $currentCommand;

    public function __construct($file) {
        $this->file = $file;
    }

	public function hasMoreCommands() {
	    return !feof($this->file);
    }

    public function close() {
        fclose($this->file);
    }

	public function advance() : void {
	    // $line = trim(fgets($this->file));
		// if ($this->isCommentLine($line) || $this->isEmptyLine($line)) {
			// $this->advance();
		// } else {
        $this->currentCommand = $this->removeComment(trim(fgets($this->file)));
		// }
    }

	private function isCommentLine($line) {
		if (substr(trim($line), 0, 2) == "//") {
			return true;
		}
		return false;
	}

	private function isEmptyLine($line) {
		if (rtrim($line) == "") {
			return true;
		}
		return false;
	}

	private function removeComment($line) {
		return substr($line, 0, strcspn($line, "//"));
	}

    /**
     * 現コマンドのコマンドタイプを返す
     */
	public function commandType() {
        $command = explode(" ", $this->currentCommand)[0];
        switch ($command) {
            case "push":
                $commandType = "C_PUSH";
                break;
            case "pop":
                $commandType =  "C_POP";
                break;
            case "add":
            case "sub":
            case "neg":
            case "eq":
            case "gt":
            case "lt":
            case "and":
            case "or":
            case "not":
                $commandType =  "C_ARITHMETIC";
                break;
            case "label":
                $commandType = "C_LABEL";
                break;
            case "if-goto":
                $commandType = "C_IF";
                break;
            case "goto":
                $commandType = "C_GOTO";
                break;
            case "function":
                $commandType = "C_FUNCTION";
                break;
            case "return":
                $commandType = "C_RETURN";
                break;
            case "call":
                $commandType = "C_CALL";
                break;
        }
        return $commandType;
    }

    /**
     * コマンドの第１引数を返す
     * - C_RETURNの場合コール不可
     * - C_ARITHMETICの場合、コマンドを返す
     */
	public function arg1() {
        $commands = explode(" ", $this->currentCommand);
        $commandType = $this->commandType();
        if ($commandType == "C_RETURN") {
            throw new Exception("C_REUTRNの場合コール不可。");
        }

        if ($commandType == "C_ARITHMETIC") {
            return $commands[0];
        }

        return $commands[1];
	}

    /**
     * コマンドの第２引数を返す
     * - C_PUSH, C_POP, C_FUNCTION, C_CALL時のみコール可能
     */
	public function arg2() {
        if (!in_array($this->commandType(), ["C_PUSH", "C_POP", "C_FUNCTION", "C_CALL"])) {
            throw new Exception("C_PUSH, C_POP, C_FUNCTION, C_CALL時のみコール可能です。");
        }
        $commands = explode(" ", $this->currentCommand);
        return $commands[2];
	}
}
?>