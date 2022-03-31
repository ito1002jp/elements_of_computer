<?php 

class CodeWriter {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function setFileName($fileName) {}

    public function writeArithmetic($command) {
        switch ($command) {
            case "add":
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "M=M-1\n");
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "D=M\n");
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "M=M+D\n");
                break;
        }
    }

    public function writePushPop($command, $segment, $index) {
        if ($command == "C_PUSH") {
            switch($segment) {
                case "constant" :
                    fwrite($this->file, "@{$index}\n");
                    fwrite($this->file, "D=A\n");
                    fwrite($this->file, "@SP\n");
                    fwrite($this->file, "A=M\n");
                    fwrite($this->file, "M=D\n");
                    fwrite($this->file, "@SP\n");
                    fwrite($this->file, "M=M+1\n");
                    break;
            }
        } else if ($command == "C_POP") {}
    }

    public function close() {
        fclose($this->file);
    }
}
?>