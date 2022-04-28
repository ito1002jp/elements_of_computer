<?php 

class Token {
    private $val;
    private $type;
    private $valsForXML = [
        "<" => "&lt;",
        ">" => "&gt;",
        "&" => "&amp;"
    ]; 

    public function __construct($val, $type) {
        $this->val = $val;
        $this->type = $type;
    }

    /**
     * tokenを返す
     * 一部tokenはXML用の表現に変換する
     */
    public function getVal() {
        $valsForXML = [
            "<" => "&lt;",
            ">" => "&gt;",
            "&" => "&amp;"
        ]; 

        if (!$val = $this->valsForXML[$this->val]) {
            $val = $this->val;
        } 

        if ($this->getType() == "STRING_CONST") {
            // double quotesを削除する
            $val = str_replace("\"", "", $val);
        }

    
        return $val;
    }

    public function getType() {return $this->type;}

    /**
     * tokenのタイプからxmlのタグ名を取得する
     * @return string
     */
    public function getXmlTag() {
        $tag = "";
        if ($this->type == "SYMBOL") {
            $tag = "symbol";
        } elseif ($this->type == "KEYWORD") {
            $tag = "keyword";
        } elseif ($this->type == "IDENTIFIER") {
            $tag = "identifier";
        } elseif ($this->type == "STRING_CONST") {
            $tag = "stringConstant";
        } elseif ($this->type == "INT_CONST") {
            $tag = "integerConstant";
        }

        if ($tag == "") {
            throw new Exception("トークンタイプが無効なためxml tagの生成ができません。tokenType: {$tokenType}");
        }

        return $tag;
    }

    /**
     * token情報をxmlフォーマットで出力する
     */
    public function genXml() {
        return "<{$this->getXmlTag()}> " . $this->getVal() . " </{$this->getXmlTag()}>";
    }

}

class JackTokenizer {
    
    /**
     * jackファイル
     */
    private $file;

    /**
     * jackプログラム１行に含まれる複数のトークンを格納する
     */
    private $tokens = [];
    
    /**
     * jackプログラムから取得したトークンを一つ格納する
     */
    private $currentToken;

    const KEYWORDS = [
            "class",
            "constructor",
            "function",
            "method",
            "field",
            "static",
            "var",
            "int",
            "char",
            "boolean",
            "void",
            "true",
            "false",
            "null",
            "this",
            "let",
            "do",
            "if",
            "else",
            "while",
            "return"
        ];
    const SYMBOLS = [
            "{",
            "}",
            "(",
            ")",
            "[",
            "]",
            ".",
            ",",
            ";",
            "+",
            "-",
            "*",
            "/",
            "&",
            "|",
            "<",
            ">",
            "=",
            "~"
        ];


    /**
     * コンストラクタ
     * - トークン解析するjackファイルを受け取る
     * - トークンに分解しxml形式にパースしてファイルに書き出す
     * ＠param $file fopen関数の結果を受け取る
     */
    public function __construct($filePath) {
        $this->file = fopen($filePath, 'r');
        $wFilePath = explode(".jack", $filePath)[0]."T2.xml"; // .jack => .xmlに書き換える

        // token生成処理
        while($line = fgets($this->file)) { 
            // コメントの場合コメント終了するまでポインターを進める
            if (strpos(trim($line), "/*") === 0) {
                while (strpos($line, "*/") === false) {
                    $line = fgets($this->file);
                }
                continue;
            }

            $line = $this->removeCommentAndNewLineCode($line);
            // コメントラインのみの場合はスキップする
            if (empty($line)) {
                continue;
            }
            $this->getTokensFromLine($line);
        }

        // xml出力処理
        $wFile = fopen($wFilePath, 'w');
        fwrite($wFile, "<tokens>\n");
        foreach ($this->tokens as $token) {
            fwrite($wFile, $token->genXml()."\n");
        }
        fwrite($wFile, "</tokens>\n");
    }

    /**
     * jackファイルの行からコメントや改行コードを削除する
     */
	private function removeCommentAndNewLineCode($line) {
		return rtrim(explode("//", $line)[0]);
	}

    /**
     * jackファイルの１行からtokenを全て取得し、メンバ変数に格納する
     * return Token $token
     */
    private function getTokensFromLine($line) {
        while ($line) {
            preg_match("/\\s/", $line, $spaceMatches, PREG_OFFSET_CAPTURE);
            preg_match("/\"/", $line, $doubleQuoteMatches, PREG_OFFSET_CAPTURE);
            $spaceIndex = !is_null($spaceMatches[0][1]) ? $spaceMatches[0][1] : PHP_INT_MAX;
            $doubleQuoteIndex = !is_null($doubleQuoteMatches[0][1]) ? $doubleQuoteMatches[0][1] : PHP_INT_MAX;

            if ($spaceIndex < $doubleQuoteIndex) {
                $tokenVal = substr($line, 0, $spaceIndex);
                $line = trim(substr($line, $spaceIndex, strlen($line)));
            } elseif ($doubleQuoteIndex < $spaceIndex) {
                //string の処理を行う
                // 2個目のdouble quoteを探す
                preg_match("/\"/", substr($line, $doubleQuoteIndex+1, strlen($line)), $doubleQuoteMatches, PREG_OFFSET_CAPTURE);
                $firstDoubleQuoteIndex = $doubleQuoteIndex;
                $lastDoubleQuoteIndex = $doubleQuoteMatches[0][1] + 2;
                $tokenVal = substr($line, $firstDoubleQuoteIndex, $lastDoubleQuoteIndex);
                $line = trim(substr($line, $lastDoubleQuoteIndex, strlen($line)));
            } else {
                $tokenVal = $line;
                $line = null;
            }

            $token = $this->judgeToken($tokenVal);
            if ($token) {
                $this->tokens[] = $token;
                continue;
            }

            // スペースで区切る => tokenチェック => tokenチェック失敗時、symbolで分割してtokenチェック
            // tokenチェック失敗時の処理 => チェック失敗時は、(x)などsymbolとidenfierの組み合わせの場合などが想定される。
            // 次のsymbolまでを一区切りにしいた物を一つずつチェックする
            while ($tokenVal) {
                // tokenValの先頭から一番近いsymbolのindexを取得する
                preg_match("/[\{\}\(\)\[\]\.,;\+\-\*\/&\|<>=~]/", $tokenVal, $symbolMatches, PREG_OFFSET_CAPTURE);
                $symbolIndex = $symbolMatches[0][1];

                if (!is_null($symbolIndex)) {
                    // 先頭から１番近いsymbolまでをtokenのjudge対象とする。
                    $token = $this->judgeToken(substr($tokenVal, 0, $symbolIndex));
                    // 先頭から１番近いsymbolまでをtokenのjudge対象としい、tokenのjudgeが失敗した場合は先頭がsymbolである($symbolIndex=0)。先頭のindexをインクリメントし再度ジャッジする
                    if (!$token) {
                        $symbolIndex++;
                        $token = $this->judgeToken(substr($tokenVal, 0, $symbolIndex));
                    }
                } else {
                    $token = $this->judgeToken($tokenVal);
                    $symbolIndex = strlen($tokenVal);
                }

                if ($token) {
                    $this->tokens[] = $token;
                    $tokenVal = substr($tokenVal, $symbolIndex, strlen($tokenVal));
                } else {
                    // いずれのtoken judgeも失敗した場合、それはtokenが無効であるため例外を投げる。
                    throw new Exception("The token is invalid. {$tokenVal}");
                }
            }
        }
    }

    /**
     * 与えられた文字列がtokenかどうかを判断する。tokenである場合はTokenを生成し、そうでない場合はnullを返す
     * @return Token
     */
    private function judgeToken($tokenVal) {
        // echo $tokenVal."\n";
        $token = null;
        if (in_array($tokenVal, self::KEYWORDS)) {
            $token = new Token($tokenVal, "KEYWORD");
        } elseif (in_array($tokenVal, self::SYMBOLS)) {
            $token = new Token($tokenVal, "SYMBOL");
        } elseif (preg_match('/^[0-9]+$/', $tokenVal)) { // check integer const
            $token = new Token($tokenVal, "INT_CONST");
        } elseif (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $tokenVal)) { // check identifier
            $token = new Token($tokenVal, "IDENTIFIER");
        } elseif (preg_match('/^".*"$/', $tokenVal)) { // check string const
            $token = new Token($tokenVal, "STRING_CONST");
        }

        return $token;
    }

    /**
     * トークンがさらに存在するか確認する
     */
    public function hasMoreTokens() {}

    /**
     * 次のトークンを取得する
     */
    public function advance() {}
    
    /**
     * 現トークンの種類を返す
     */
    public function tokenType() {}

    /**
     * 現トークンの種類がキーワードの場合キーワードの種類を返す
     * - トークンを大文字にして返す
     */
    public function keyWord() {}

    /**
     * 現トークンの種類がシンボルの場合シンボルの種類を返す
     */
    public function symbol() {}

    /**
     * 現トークンの種類がIDENTIFIERの場合IDENTIFIERの種類を返す
     */
    public function identifier() {}

    /**
     * 現トークンの種類が整数の場合整数の値を返す
     */
    public function intVal() {}

    /**
     * 現トークンの種類が文字列の場合文字列を返す
     */
    public function stringVal() {}
}

?>
