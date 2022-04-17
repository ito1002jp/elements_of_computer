<?php 

class CodeWriter {
    private $file;
    private $symbols = [
        "local" => "LCL",
        "argument" => "ARG",
        "this" => "THIS",
        "that" => "THAT"
    ];

    public function __construct($file) {
        $this->file = $file;
    }

    /**
     * 初期化処理
     */
    public function writeInit() {}
    
    /**
     * Labelコマンドを行うアセンブリを出力する
     */
    public function writeLabel($label) {
        fwrite($this->file, "({$label})\n");
        fwrite($this->file, "\n");
    }

    /**
     * if-gotoコマンドを行うアセンブリを出力する
     */
    public function writeIf($label) {
        fwrite($this->file, "// if-goto\n");
        fwrite($this->file, "@SP\n");
        fwrite($this->file, "M=M-1\n");
        fwrite($this->file, "A=M\n");
        fwrite($this->file, "D=M\n");
        fwrite($this->file, "@{$label}\n");
        fwrite($this->file, "D;JMP\n");
        fwrite($this->file, "\n");
    }

    /**
     * gotoコマンドを行うアセンブリを出力する
     */
    public function writeGoto($label) {
        fwrite($this->file, "// goto\n");
        fwrite($this->file, "@{$label}\n");
        fwrite($this->file, "0;JMP\n");
        fwrite($this->file, "\n");
    }

    /**
     * functionコマンドを行うアセンブリを出力する
     */
    public function writeFunction($functionName, $numLocals) {
        fwrite($this->file, "// function\n");
        fwrite($this->file, "({$functionName})\n");
        // ローカル変数の数だけSPを進める
        for ($i = 0; $i < $numLocals; $i++) {
            fwrite($this->file, "@SP\n");
            fwrite($this->file, "A=M\n");
            fwrite($this->file, "M=D\n");
            fwrite($this->file, "@SP\n");
            fwrite($this->file, "M=M+1\n");
        }
        fwrite($this->file, "\n");
    }

    /**
     * returnコマンドを行うアセンブリを出力する
     */
    public function writeReturn() {
        fwrite($this->file, "// reutrn \n");
        // FRAME = LCL  => 呼び出し元の情報を格納するメモリを探すための基準となるLCLポインターを格納する
        fwrite($this->file, "@LCL\n");
        fwrite($this->file, "D=M\n");
        fwrite($this->file, "@R13\n");
        fwrite($this->file, "M=D\n");
        // RET = *(FRAME -5) => LCLの5つ前のアドレスにリターンアドレスが格納されている
        fwrite($this->file, "@5\n");
        fwrite($this->file, "D=A\n");
        fwrite($this->file, "@R13\n");
        fwrite($this->file, "A=M-D\n");
        fwrite($this->file, "D=M\n");
        fwrite($this->file, "@R14\n");
        fwrite($this->file, "M=D\n");
        // *ARG = POP()
        fwrite($this->file, "@SP\n");
        fwrite($this->file, "M=M-1\n");
        fwrite($this->file, "A=M\n");
        fwrite($this->file, "D=M\n"); // 最新の情報をpop
        fwrite($this->file, "@ARG\n"); // argument[0]に格納
        fwrite($this->file, "A=M\n");
        fwrite($this->file, "M=D\n");
        // ポインターを元に戻す
        // SP = ARG + 1
        fwrite($this->file, "@ARG\n");
        fwrite($this->file, "D=M+1\n");
        fwrite($this->file, "@SP\n");
        fwrite($this->file, "M=D\n");
        // THAT
        fwrite($this->file, "@R13\n");
        fwrite($this->file, "AM=M-1\n");
        fwrite($this->file, "D=M\n");
        fwrite($this->file, "@THAT\n");
        fwrite($this->file, "M=D\n");
        // THIS
        fwrite($this->file, "@R13\n");
        fwrite($this->file, "AM=M-1\n");
        fwrite($this->file, "D=M\n");
        fwrite($this->file, "@THIS\n");
        fwrite($this->file, "M=D\n");
        // ARG
        fwrite($this->file, "@R13\n");
        fwrite($this->file, "AM=M-1\n");
        fwrite($this->file, "D=M\n");
        fwrite($this->file, "@ARG\n");
        fwrite($this->file, "M=D\n");
        //LCL
        fwrite($this->file, "@R13\n");
        fwrite($this->file, "AM=M-1\n");
        fwrite($this->file, "D=M\n");
        fwrite($this->file, "@LCL\n");
        fwrite($this->file, "M=D\n");
        // goto RET
        fwrite($this->file, "@R14\n");
        fwrite($this->file, "A=M\n");

        fwrite($this->file, "0;JMP\n");
    }

    public function setFileName($fileName) {}

    public function writeArithmetic($id, $command) {
        switch ($command) {
            case "add":
                fwrite($this->file, "// add\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "M=M-1\n");
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "D=M\n");
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "M=M+D\n");
                fwrite($this->file, "\n");
                break;
            case "sub":
                fwrite($this->file, "// sub\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "M=M-1\n"); 
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "D=M\n"); // Dにyの値を退避させる
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "M=M-D\n");
                fwrite($this->file, "\n");
                break;
            case "eq":
                fwrite($this->file, "// eq\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "M=M-1\n"); // ポインターの値を一個減らす
                fwrite($this->file, "D=M\n"); // Dにポインターの位置を記録する
                fwrite($this->file, "A=D\n"); // Aをポインターの位置に変更する
                fwrite($this->file, "D=M\n"); // RAM[SP]をDに退避する
                fwrite($this->file, "A=A-1\n"); // RAM[SP-1]を取得するため、Aを-1する
                fwrite($this->file, "D=D-M\n"); // D(RAM[SP]) - M(RAM[SP-1])を計算する
                fwrite($this->file, "@TRUE_{$id}\n");
                fwrite($this->file, "D;JEQ\n"); // Dがゼロだったら、eqなので、0。 Dがゼロではなかったら, eqではないので-1。
                // eqではなかった時の処理
                fwrite($this->file, "@SP\n");  // RAM[SP-1]］にeqの結果である"-1"を格納する
                fwrite($this->file, "A=M-1\n");                
                fwrite($this->file, "M=0\n");
                fwrite($this->file, "@END_{$id}\n");
                fwrite($this->file, "0;JMP\n"); // eqだった時の処理をスキップする
                // eqだった時の処理
                fwrite($this->file, "(TRUE_{$id})\n");
                fwrite($this->file, "@SP\n");  // RAM[SP-1]］にeqの結果である"0"を格納する
                fwrite($this->file, "A=M-1\n");                
                fwrite($this->file, "M=-1\n");
                fwrite($this->file, "(END_{$id})\n");
                fwrite($this->file, "\n");
                break;
            case "lt":
                fwrite($this->file, "// lt\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "A=A-1\n"); // ポインターの値を2個減らし、ポインターがxをさすようにする
                fwrite($this->file, "A=A-1\n"); 
                fwrite($this->file, "D=M\n"); // xの値をDに退避する
                fwrite($this->file, "A=A+1\n");  // ポインターがyをさすようにする
                fwrite($this->file, "D=D-M\n"); // D(RAM[SP-2] or x) - M(RAM[SP-1] or y)を計算する
                fwrite($this->file, "@TRUE_{$id}\n");
                fwrite($this->file, "D;JLT\n"); // D(x-y)<0だったら、ltなので、0。 D(x-y)<0ではなかったら, ltではないので-1。
                // lt=falseだった時の処理
                fwrite($this->file, "@SP\n");  // ポインターの値を2個減らし、ポインターがxをさすようにする
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "M=0\n"); // そして、xにltの結果である"-1"を格納する
                fwrite($this->file, "@END_{$id}\n");
                fwrite($this->file, "0;JMP\n"); // lt=trueだった時の処理をスキップする
                // lt=trueだった時の処理
                fwrite($this->file, "(TRUE_{$id})\n");
                fwrite($this->file, "@SP\n");  // ポインターの値を2個減らし、ポインターがxをさすようにする
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "A=A-1\n"); 
                fwrite($this->file, "A=A-1\n"); 
                fwrite($this->file, "M=-1\n"); // そして、xにltの結果である"0"を格納する
                // 終了時処理
                fwrite($this->file, "(END_{$id})\n");
                fwrite($this->file, "@SP\n"); //現状ポインターはy+1を指しているのでyをさすようにしてあげる。
                fwrite($this->file, "M=M-1\n");
                fwrite($this->file, "\n");
                break;
            case "gt":
                fwrite($this->file, "// gt\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "A=A-1\n"); // ポインターの値を2個減らし、ポインターがxをさすようにする
                fwrite($this->file, "A=A-1\n"); 
                fwrite($this->file, "D=M\n"); // xの値をDに退避する
                fwrite($this->file, "A=A+1\n");  // ポインターがyをさすようにする
                fwrite($this->file, "D=D-M\n"); // D(RAM[SP-2] or x) - M(RAM[SP-1] or y)を計算する
                fwrite($this->file, "@TRUE_{$id}\n");
                fwrite($this->file, "D;JGT\n"); // D(x-y)>0だったら、gtなので、0。 D(x-y)<0ではなかったら, gtではないので-1。
                // lt=falseだった時の処理
                fwrite($this->file, "@SP\n");  // ポインターの値を2個減らし、ポインターがxをさすようにする
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "M=0\n"); // そして、xにltの結果である"-1"を格納する
                fwrite($this->file, "@END_{$id}\n");
                fwrite($this->file, "0;JMP\n"); // lt=trueだった時の処理をスキップする
                // lt=trueだった時の処理
                fwrite($this->file, "(TRUE_{$id})\n");
                fwrite($this->file, "@SP\n");  // ポインターの値を2個減らし、ポインターがxをさすようにする
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "A=A-1\n"); 
                fwrite($this->file, "A=A-1\n"); 
                fwrite($this->file, "M=-1\n"); // そして、xにltの結果である"0"を格納する
                // 終了時処理
                fwrite($this->file, "(END_{$id})\n");
                fwrite($this->file, "@SP\n"); //現状ポインターはy+1を指しているのでyをさすようにしてあげる。
                fwrite($this->file, "M=M-1\n");
                fwrite($this->file, "\n");
                break;
            case "and":
                fwrite($this->file, "// and\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "A=M\n");  //addressポインターがxをさすようにする
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "D=M\n"); // Dにxの値を退避させる
                fwrite($this->file, "A=A+1\n"); //addressポインターがyをさすようにする
                fwrite($this->file, "D=D&M\n"); // and(x, y)の結果をDに退避する
                fwrite($this->file, "A=A-1\n"); // and(x, y)の結果をxのaddressに格納する
                fwrite($this->file, "M=D\n");
                fwrite($this->file, "@SP\n"); //SPの値をyにおく
                fwrite($this->file, "M=M-1\n");
                fwrite($this->file, "\n");
                break;
            case "or":
                fwrite($this->file, "// or\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "M=M-1\n"); 
                fwrite($this->file, "A=M\n");
                fwrite($this->file, "D=M\n"); // Dにyの値を退避させる
                fwrite($this->file, "A=A-1\n");
                fwrite($this->file, "M=D|M\n");
                fwrite($this->file, "\n");
                break;
            case "not":
                fwrite($this->file, "// not\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "A=M-1\n");
                fwrite($this->file, "M=!M\n");
                fwrite($this->file, "\n");
                break;
            case "neg":
                fwrite($this->file, "// neg\n");
                fwrite($this->file, "@SP\n");
                fwrite($this->file, "A=M-1\n");
                fwrite($this->file, "M=-M\n");
                fwrite($this->file, "\n");
                break;
        }
    }

    public function writePushPop($id, $command, $segment, $index) {
        if ($command == "C_PUSH") {
            switch($segment) {
                case "constant" :
                    fwrite($this->file, "// push constant\n");
                    fwrite($this->file, "@{$index}\n");
                    fwrite($this->file, "D=A\n");
                    break;
                case "temp":
                    // temp : R5~R12
                    $index += 5;
                case "pointer": 
                    // pointer : R3~R4
                    $index += 3;
                    fwrite($this->file, "// push temp\n");
                    // tempからプッシュするデータを取得する
                    fwrite($this->file, "@R{$index}\n");
                    fwrite($this->file, "D=M\n");
                    break;
                case "static" :
                    $index += 16;
                    fwrite($this->file, "// push static\n");
                    fwrite($this->file, "@{$index}\n");
                    fwrite($this->file, "D=M\n");
                    break;
                case "local" :
                case "argument" :
                case "this" :
                case "that" :
                    $symbol = $this->symbols[$segment];
                    fwrite($this->file, "// push {$segment}\n");
                    // プッシュするデータを取得する
                    fwrite($this->file, "@{$index}\n");
                    fwrite($this->file, "D=A\n");
                    fwrite($this->file, "@{$symbol}\n");
                    fwrite($this->file, "A=D+M\n"); // index + localのスタートポインターを算出しLCLを上書きする
                    fwrite($this->file, "D=M\n"); // プッシュするデータをDに退避させる
                    break;

            }
            // stackにDの値をプッシュする
            fwrite($this->file, "@SP\n");
            fwrite($this->file, "A=M\n");
            fwrite($this->file, "M=D\n");
            fwrite($this->file, "@SP\n");
            fwrite($this->file, "M=M+1\n");
            fwrite($this->file, "\n");

        } else if ($command == "C_POP") {
            switch($segment) {
                case "temp" :
                    $index += 5;
                case "pointer" :
                    $index += 3;
                    fwrite($this->file, "// pop {$segment}\n");
                    // ポップするデータを取得する
                    fwrite($this->file, "@SP\n"); // SPの一番上にある値をDに取得しポインターをデクリメントする
                    fwrite($this->file, "M=M-1\n");
                    fwrite($this->file, "A=M\n");
                    fwrite($this->file, "D=M\n");
                    // segment[index]にポップしたデータを格納する
                    fwrite($this->file, "@R{$index}\n");
                    fwrite($this->file, "M=D\n");
                    fwrite($this->file, "\n");
                    break;
                case "static" :
                    $index += 16;
                    fwrite($this->file, "// pop {$segment}\n");
                    // ポップするデータを取得する
                    fwrite($this->file, "@SP\n"); // SPの一番上にある値をDに取得しポインターをデクリメントする
                    fwrite($this->file, "M=M-1\n");
                    fwrite($this->file, "A=M\n");
                    fwrite($this->file, "D=M\n");
                    // segment[index]にポップしたデータを格納する
                    fwrite($this->file, "@{$index}\n");
                    fwrite($this->file, "M=D\n");
                    fwrite($this->file, "\n");
                    break;
                case "local" :
                case "argument" :
                case "this" :
                case "that" :
                    $symbol = $this->symbols[$segment];
                    fwrite($this->file, "// pop {$segment}\n");
                    // local[index]のアドレスを取得する
                    fwrite($this->file, "@{$index}\n");
                    fwrite($this->file, "D=A\n");
                    fwrite($this->file, "@{$symbol}\n");
                    fwrite($this->file, "M=D+M\n"); // index + localのスタートポインターを算出しLCLを上書きする
                    // ポップするデータを取得する
                    fwrite($this->file, "@SP\n"); // SPの一番上にある値をDに取得しポインターをデクリメントする
                    fwrite($this->file, "M=M-1\n");
                    fwrite($this->file, "A=M\n");
                    fwrite($this->file, "D=M\n");
                    // popしたデータをlocal[index]に格納する
                    fwrite($this->file, "@{$symbol}\n");
                    fwrite($this->file, "A=M\n");
                    fwrite($this->file, "M=D\n");
                    // LCLをもとに戻す
                    fwrite($this->file, "@{$index}\n");
                    fwrite($this->file, "D=A\n");
                    fwrite($this->file, "@{$symbol}\n");
                    fwrite($this->file, "M=M-D\n"); 
                    fwrite($this->file, "\n");
                    break;

            }

        }
    }

    public function close() {
        fclose($this->file);
    }
}
?>