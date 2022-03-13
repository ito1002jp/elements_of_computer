// This file is part of www.nand2tetris.org
// and the book "The Elements of Computing Systems"
// by Nisan and Schocken, MIT Press.
// File name: projects/04/Mult.asm

// Multiplies R0 and R1 and stores the result in R2.
// (R0, R1, R2 refer to RAM[0], RAM[1], and RAM[2], respectively.)
//
// This program only needs to handle arguments that satisfy
// R0 >= 0, R1 >= 0, and R0*R1 < 32768.

// Put your code here.

// R0=5;
// R1=2;
// while(R1 > 0):
//   R2 = R2 + R0;
//   R1 = R1-1;


// R2を初期化
@R2
M=0;

(LOOP)
//計算を続けるかの条件をチェックする
// R１が0だった計算をやめENDにジャンプする
@R1
D=M
@END
D;JEQ

// R2にR0を足す
@R0
D=M
@R2
M=D+M

// R1を1減らす
@R1
M=M-1

@LOOP
0;JMP

(END)
@END
0;JMP
