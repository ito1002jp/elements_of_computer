<?php 

class CompilationEngine {
    private $file;
    private $tokenizer;

    public function __construct($filePath) {
        $wFilePath = explode(".jack", $filePath)[0]."2.xml"; // .jack => .xmlに書き換える
        $this->file = fopen($wFilePath, 'w');
        $this->tokenizer = new JackTokenizer($filePath);
        $this->compileClass();
    }

    private function compileClass() {
        fwrite($this->file, "<class>\n");

        $this->outputNextTokenInXML(); // class
        $this->outputNextTokenInXML(); // {class_name}
        $this->outputNextTokenInXML(); // {

        while (in_array($this->tokenizer->peek()->getVal(), ["static", "field"])) {
            $this->compileClassVarDec();
        }

        while (in_array($this->tokenizer->peek()->getVal(), ["constructor", "function", "method"])) {
            $this->compileSubroutine();
        }

        fwrite($this->file, "</class>\n");
    }

    /**
     * 次のトークンを取得し、トークン情報をXML形式に出力する
     */
    private function outputNextTokenInXML()
    {
        $this->tokenizer->hasMoreTokens() ? $this->tokenizer->advance() : null;
        $xml = $this->tokenizer->getCurrentTokenInXML();
        fwrite($this->file, "{$xml}\n");
    }

    /**
     * classVarDecを出力する
     * ex) static boolean var;
     */
    private function compileClassVarDec() {
        fwrite($this->file, "<classVarDec>\n");
        $this->outputNextTokenInXML(); // static | field
        $this->outputNextTokenInXML(); // {type}
        $this->outputNextTokenInXML(); // {varName}
        while ($this->tokenizer->peek()->getVal() == ",") {
            $this->outputNextTokenInXML(); // ,  
            $this->outputNextTokenInXML(); // {varName}
        }
        $this->outputNextTokenInXML(); // ;
        fwrite($this->file, "</classVarDec>\n");
    }

    private function compileSubroutine() {
        fwrite($this->file, "<subroutineDec>\n");
        $this->outputNextTokenInXML(); // constructor | function | method
        $this->outputNextTokenInXML(); // void | {type}
        $this->outputNextTokenInXML(); // {subroutineName}
        $this->outputNextTokenInXML(); // (
        $this->compileParameterList(); 
        $this->outputNextTokenInXML(); // )
        fwrite($this->file, "<subroutineBody>\n");
        $this->outputNextTokenInXML(); // {
        while ($this->tokenizer->peek()->getVal() == "var") {
            $this->compileVarDec();
        }
        $this->compileStatements();
        $this->outputNextTokenInXML(); // } 
        fwrite($this->file, "</subroutineBody>\n");
        fwrite($this->file, "</subroutineDec>\n");
    }

    private function compileParameterList() {
        fwrite($this->file, "<parameterList>\n");
        while ($this->tokenizer->peek()->getVal() != ")") {
            $this->outputNextTokenInXML(); // {type}
            $this->outputNextTokenInXML(); // {varName}
            // @TODO, がある場合の処理を書く
        }
        fwrite($this->file, "</parameterList>\n");
    }

    /**
     * ex) var int varName;
     */
    private function compileVarDec() {
        fwrite($this->file, "<varDec>\n");
        $this->outputNextTokenInXML(); // var
        $this->outputNextTokenInXML(); // {type}
        $this->outputNextTokenInXML(); // {varName};
        while ($this->tokenizer->peek()->getVal() == ",") {
            $this->outputNextTokenInXML(); // ,  
            $this->outputNextTokenInXML(); // {varName}
        }
        $this->outputNextTokenInXML(); // ;
        fwrite($this->file, "</varDec>\n");
    }

    private function compileStatements() {
        fwrite($this->file, "<statements>\n");
        while ($this->tokenizer->peek()->getVal() != "}") {
            $this->tokenizer->advance();
            switch ($this->tokenizer->peek()->getVal()) {
                case "let":
                    $this->compileLet();
                    break;
                case "if":
                    $this->compileIf();
                    break;
                case "while":
                    $this->compileWhile();
                    break;
                case "do":
                    $this->compileDo();
                    break;
                case "return":
                    $this->compileReturn();
                    break;
            }
        }
        fwrite($this->file, "</statements>\n");
    }

    private function compileLet() {
        fwrite($this->file, "<letStatement>\n");
        fwrite($this->file, "</letStatement>\n");
    }

    private function compileIf() {
        fwrite($this->file, "<ifStatement>\n");
        fwrite($this->file, "</ifStatement>\n");
    }

    private function compileWhile() {
        fwrite($this->file, "<whileStatement>\n");
        fwrite($this->file, "</whileStatement>\n");
    }

    private function compileDo() {
        fwrite($this->file, "<doStatement>\n");
        fwrite($this->file, "</doStatement>\n");
    }

    private function compileReturn() {
        fwrite($this->file, "<returnStatement>\n");
        fwrite($this->file, "</returnStatement>\n");
    }

    private function compileExpression() {}
    private function compileTerm() {}
    private function compileExpressionList() {}
}
?>