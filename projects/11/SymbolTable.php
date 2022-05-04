<?php
class SymbolTable {
    private $tblClass;
    private $tblSubroutine;
    private $index = 0;

    public function __construct() {
        $this->index = 0;
        $this->tblClass = [];
        $this->tblSubroutine = [];
    }

    /**
     * 新しいサブルーチンのスコープを開始する
     */
    public function startSubroutine() {
        $this->index = 0;
        $this->tblSubroutine = [];
    }

    /**
     * identifierを定義しインデックスを付与する
     */
    public function define($name, $type, $kind) {
        if (in_array($kind, ["static", "field"])) {
            $this->tblClass[] = [$name, $type, $kind, $this->index];
        } else {
            $this->tblSubroutine[] = [$name, $type, $kind, $this->index];
        }
        $this->index++;
    }

    /**
     * 指定した$typeが現在のスコープで定義されている数をカウントして返す
     */
    public function varCount($type) {}

    /**
     * 指定した$nameの属性を返す
     * @return string
     */
    public function kindOf($name) {}

    /**
     * 指定した$nameの型を返す
     * @return string
     */
    public function typeOf($name) {}

    /**
     * 指定した$nameのインデックスを返す
     * @return int
     */
    public function indexOf($name) {}
}
?>