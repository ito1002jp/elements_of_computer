// This file is part of www.nand2tetris.org
// and the book "The Elements of Computing Systems"
// by Nisan and Schocken, MIT Press.
// File name: projects/04/Fill.asm

// Runs an infinite loop that listens to the keyboard input.
// When a key is pressed (any key), the program blackens the screen,
// i.e. writes "black" in every pixel;
// the screen should remain fully black as long as the key is pressed. 
// When no key is pressed, the program clears the screen, i.e. writes
// "white" in every pixel;
// the screen should remain fully clear as long as no key is pressed.

// Put your code here.

// 画面は 512 * 256 pixel
// SCREENの情報を管理するメモリマップのRAMアドレスは16384から始まる
// 横列の512ピクセルは1レジスタ(16ビット)x32で成り立っている。
// つまり、画面の描画に利用するメモリの数は32*256=>8192。
// したがって、16384~24576までのメモリを利用する

// //fill・clear
// address = 16384;
// while (address <= 24576) {
//   memory[address] = -1 | 0;
//   address+=1;
// }

// //main loop
// while (true) {
//    if (key is pressed) {
//      fill();
//    }
//    clear()
// }


(LOOP)
@KBD
D=M
@FILL
D;JGT

(CLEAR)
@offset
D=M
@SCREEN
A=D+A
M=0

@INC_OFFSET
0;JMP

(FILL)
@offset
D=M
@SCREEN
A=D+A
M=-1

(INC_OFFSET)
@offset
MD=M+1
// loopに戻るかどうかチェク
@8192
D=D-A
@LOOP
D;JLT

(RESET_OFFSET)
@offset
M=0;
@LOOP
0;JMP
