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
        $symbolTable = new SymbolTable();

        fwrite($this->file, "<class>\n");

        $this->outputNextTokenInXML(); // class
        $this->outputNextTokenInXML(); // {class_name}
        $this->outputNextTokenInXML(); // {

        while (in_array($this->tokenizer->peek()->getVal(), ["static", "field"])) {
            $this->compileClassVarDec($symbolTable);
        }

        print_r($symbolTable);
        exit;

        while (in_array($this->tokenizer->peek()->getVal(), ["constructor", "function", "method"])) {
            $this->compileSubroutine();
        }
        $this->outputNextTokenInXML(); // }
        fwrite($this->file, "</class>\n");
    }

    /**
     * classVarDecを出力する
     * ex) static boolean var;
     */
    private function compileClassVarDec($symbolTable) {
        $token = $this->getNextToken(); // static | field
        $kind = $token->getVal();
        $token = $this->getNextToken(); // {type}
        $type = $token->getVal();
        $token = $this->getNextToken(); // {varName}
        $name = $token->getVal();
        $symbolTable->define($name, $type, $kind);

        while ($this->tokenizer->peek()->getVal() == ",") {
            $token = $this->getNextToken(); // , 
            $token = $this->getNextToken(); // {varName}
            $name = $token->getVal();
            $symbolTable->define($name, $type, $kind);
        }
        $token = $this->getNextToken(); // ;
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
        while ($this->tokenizer->peek()->isStatement()){
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
        $this->outputNextTokenInXML(); // let
        $this->outputNextTokenInXML(); // {varname}
        if ($this->tokenizer->peek()->getVal() == "[") {
            $this->outputNextTokenInXML(); // [
            $this->compileExpression();
            $this->outputNextTokenInXML(); // ]
        }
        $this->outputNextTokenInXML(); // =
        $this->compileExpression();
        $this->outputNextTokenInXML(); // ;
        fwrite($this->file, "</letStatement>\n");
    }

    private function compileIf() {
        fwrite($this->file, "<ifStatement>\n");
        $this->outputNextTokenInXML(); // if
        $this->outputNextTokenInXML(); // (
        $this->compileExpression();
        $this->outputNextTokenInXML(); // )
        $this->outputNextTokenInXML(); // {
        $this->compileStatements();
        $this->outputNextTokenInXML(); // }
        if ($this->tokenizer->peek()->getVal() == "else") {
            $this->outputNextTokenInXML(); // else
            $this->outputNextTokenInXML(); // {
            $this->compileStatements();
            $this->outputNextTokenInXML(); // }
        }
        fwrite($this->file, "</ifStatement>\n");
    }

    private function compileWhile() {
        fwrite($this->file, "<whileStatement>\n");
        $this->outputNextTokenInXML(); // while
        $this->outputNextTokenInXML(); // (
        $this->compileExpression();
        $this->outputNextTokenInXML(); // )
        $this->outputNextTokenInXML(); // {
        $this->compileStatements();
        $this->outputNextTokenInXML(); // }
        fwrite($this->file, "</whileStatement>\n");
    }

    private function compileDo() {
        fwrite($this->file, "<doStatement>\n");
        $this->outputNextTokenInXML(); // do
        // subroutine call
        $this->outputNextTokenInXML(); // {subroutineName} | {className | varName}
        if ($this->tokenizer->peek()->getVal() == ".") {
            $this->outputNextTokenInXML(); // .
            $this->outputNextTokenInXML(); // {subroutineName}
        }
        $this->outputNextTokenInXML(); // (
        $this->compileExpressionList();
        $this->outputNextTokenInXML(); // )
        $this->outputNextTokenInXML(); // ;
        fwrite($this->file, "</doStatement>\n");
    }

    private function compileReturn() {
        fwrite($this->file, "<returnStatement>\n");
        $this->outputNextTokenInXML(); // return
        if ($this->tokenizer->peek()->getVal() != ";") {
            $this->compileExpression();
        }
        $this->outputNextTokenInXML(); // ;

        fwrite($this->file, "</returnStatement>\n");
    }

    private function compileExpression() {
        fwrite($this->file, "<expression>\n");
        $this->compileTerm();
        while ($this->tokenizer->peek()->isOperation()) {
            $this->outputNextTokenInXML(); // op
            $this->compileTerm();
        }

        fwrite($this->file, "</expression>\n");
    }

    private function compileTerm() {
        fwrite($this->file, "<term>\n");
        if ($this->tokenizer->peek()->getVal() == "(") {
            // ({expression})
            $this->outputNextTokenInXML(); // (
            $this->compileExpression();
            $this->outputNextTokenInXML(); // )
        } elseif (in_array($this->tokenizer->peek()->getVal(), ["-", "~"])) {
            // {unaryOp} {term}
            $this->outputNextTokenInXML(); // {unaryOp}
            $this->compileTerm();
        } else {
            $this->outputNextTokenInXML(); // integerConstant | stringConstant | keywordConstant | varName | subroutineName
            if ($this->tokenizer->peek()->getVal() == ".") {
                // subroutineCall
                // varName.subroutineName();
                $this->outputNextTokenInXML(); // .
                $this->outputNextTokenInXML(); // {subroutineName}
                $this->outputNextTokenInXML(); // (
                $this->compileExpressionList();
                $this->outputNextTokenInXML(); // )
            } elseif ($this->tokenizer->peek()->getVal() == "(") {
                // subroutineCall
                // subroutineName();
                $this->outputNextTokenInXML(); // (
                $this->compileExpressionList();
                $this->outputNextTokenInXML(); // )
            } elseif ($this->tokenizer->peek()->getVal() == "[") {
                // {varName}[{expression}]
                $this->outputNextTokenInXML(); // [
                $this->compileExpression();
                $this->outputNextTokenInXML(); // ]
            }
        }
        fwrite($this->file, "</term>\n");
    }

    private function compileExpressionList() {
        fwrite($this->file, "<expressionList>\n");
        if ($this->tokenizer->peek()->getVal() != ")") {
            $this->compileExpression();
            while ($this->tokenizer->peek()->getVal() == ",") {
                $this->outputNextTokenInXML(); // ,
                $this->compileExpression();
            }
        }
        fwrite($this->file, "</expressionList>\n");
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

    private function getNextToken() {
        $this->tokenizer->hasMoreTokens() ? $this->tokenizer->advance() : null;
        return $this->tokenizer->getCurrentToken();
    }
}
?>