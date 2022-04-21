<?php 

class JackTokenizer {
    
    /**
     * jackファイル
     */
    private $file;

    /**
     * jackプログラム１行に含まれる複数のトークンを格納する
     */
    private $currentTokens;
    
    /**
     * jackプログラムから取得したトークンを一つ格納する
     */
    private $currentToken;

    /**
     * コンストラクタ
     * - トークン解析するjackファイルを受け取る
     * ＠param $file fopen関数の結果を受け取る
     */
    public function __construct($file) {
        $this->file = $file;
    }

    /**
     * トークンがさらに存在するか確認する
     */
    public function hasMoreTokens() {
        // 確認中の行に含まれるトークンの集合配列が空であり、かつ次の行が存在しない場合はfalse
	    return !(empty($currentTokens) && feof($this->file));
    }

    /**
     * 次のトークンを取得する
     * - １行ずつ取得し、１行に含まれる複数のトークンを配列に格納する
     */
    public function advance() {
        // 確認中の行に含まれるトークンを全て調べ終わったら、次の行からトークンを取得する
        if (empty($this->currentTokens)) {
            $line = $this->removeComment(trim(fgets($this->file)));
            $this->currentTokens = explode($line, " ");
        }

        $this->currentToken = array_shift($this->currentTokens);
    }
    
    /**
     * 現トークンの種類を返す
     */
    public function tokenType() {
        switch ($this->currentToken) {
            case "class":
            case "constructor":
            case "function":
            case "method":
            case "field":
            case "static":
            case "var":
            case "int":
            case "char":
            case "boolean":
            case "void":
            case "true":
            case "false":
            case "null":
            case "this":
            case "let":
            case "do":
            case "if":
            case "else":
            case "while":
            case "return":
                $tokenType = "KEYWORD";
                break;
            case "{":
            case "}":
            case "(":
            case ")":
            case "[":
            case "]":
            case ".":
            case ",":
            case ";":
            case "+":
            case "-":
            case "*":
            case "/":
            case "&":
            case "|":
            case "<":
            case ">":
            case "=":
            case "~":
                $tokenType = "SYMBOL";
                break;
            default:
                is_int($this->currentToken) ? $tokenType = "INT_CONST" : null;
                $this->currentToken[0] == "\"" && $this->currentToken[length($this->currentToken)-1] == "\"" ? $tokenType = "STRING_CONST" : null;
                !is_int($this->currentToken[0]) ? $tokenType = "IDENTIFIER" : null;
        }
        if ($tokenType == null) {
            throw new Exception("The current token is not tokenizable. The token is \"{$this->currentToken}\"");
        }
        return $tokenType;
    }

    /**
     * 現トークンの種類がキーワードの場合キーワードの種類を返す
     * - トークンを大文字にして返す
     */
    public function keyWord() {
        return strtoupper($this->currentToken());
    }

    /**
     * 現トークンの種類がシンボルの場合シンボルの種類を返す
     */
    public function symbol() {
        return $this->currentToken();
    }

    /**
     * 現トークンの種類がIDENTIFIERの場合IDENTIFIERの種類を返す
     */
    public function identifier() {
        return $this->currentToken();
    }

    /**
     * 現トークンの種類が整数の場合整数の値を返す
     */
    public function intVal() {
        return $this->currentToken;
    }

    /**
     * 現トークンの種類が文字列の場合文字列を返す
     */
    public function stringVal() {
        return $this->currentToken;
    }
}

?>
