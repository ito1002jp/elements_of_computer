// push constant
@17
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@17
D=A
@SP
A=M
M=D
@SP
M=M+1

// eq
@SP
M=M-1
D=M
A=D
D=M
A=A-1
D=D-M
@TRUE_2
D;JEQ
@SP
A=M-1
M=0
@END_2
0;JMP
(TRUE_2)
@SP
A=M-1
M=-1
(END_2)

// push constant
@17
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@16
D=A
@SP
A=M
M=D
@SP
M=M+1

// eq
@SP
M=M-1
D=M
A=D
D=M
A=A-1
D=D-M
@TRUE_5
D;JEQ
@SP
A=M-1
M=0
@END_5
0;JMP
(TRUE_5)
@SP
A=M-1
M=-1
(END_5)

// push constant
@16
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@17
D=A
@SP
A=M
M=D
@SP
M=M+1

// eq
@SP
M=M-1
D=M
A=D
D=M
A=A-1
D=D-M
@TRUE_8
D;JEQ
@SP
A=M-1
M=0
@END_8
0;JMP
(TRUE_8)
@SP
A=M-1
M=-1
(END_8)

// push constant
@892
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@891
D=A
@SP
A=M
M=D
@SP
M=M+1

// lt
@SP
A=M
A=A-1
A=A-1
D=M
A=A+1
D=D-M
@TRUE_11
D;JLT
@SP
A=M
A=A-1
A=A-1
M=0
@END_11
0;JMP
(TRUE_11)
@SP
A=M
A=A-1
A=A-1
M=-1
(END_11)
@SP
M=M-1

// push constant
@891
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@892
D=A
@SP
A=M
M=D
@SP
M=M+1

// lt
@SP
A=M
A=A-1
A=A-1
D=M
A=A+1
D=D-M
@TRUE_14
D;JLT
@SP
A=M
A=A-1
A=A-1
M=0
@END_14
0;JMP
(TRUE_14)
@SP
A=M
A=A-1
A=A-1
M=-1
(END_14)
@SP
M=M-1

// push constant
@891
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@891
D=A
@SP
A=M
M=D
@SP
M=M+1

// lt
@SP
A=M
A=A-1
A=A-1
D=M
A=A+1
D=D-M
@TRUE_17
D;JLT
@SP
A=M
A=A-1
A=A-1
M=0
@END_17
0;JMP
(TRUE_17)
@SP
A=M
A=A-1
A=A-1
M=-1
(END_17)
@SP
M=M-1

// push constant
@32767
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1

// gt
@SP
A=M
A=A-1
A=A-1
D=M
A=A+1
D=D-M
@TRUE_20
D;JGT
@SP
A=M
A=A-1
A=A-1
M=0
@END_20
0;JMP
(TRUE_20)
@SP
A=M
A=A-1
A=A-1
M=-1
(END_20)
@SP
M=M-1

// push constant
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@32767
D=A
@SP
A=M
M=D
@SP
M=M+1

// gt
@SP
A=M
A=A-1
A=A-1
D=M
A=A+1
D=D-M
@TRUE_23
D;JGT
@SP
A=M
A=A-1
A=A-1
M=0
@END_23
0;JMP
(TRUE_23)
@SP
A=M
A=A-1
A=A-1
M=-1
(END_23)
@SP
M=M-1

// push constant
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@32766
D=A
@SP
A=M
M=D
@SP
M=M+1

// gt
@SP
A=M
A=A-1
A=A-1
D=M
A=A+1
D=D-M
@TRUE_26
D;JGT
@SP
A=M
A=A-1
A=A-1
M=0
@END_26
0;JMP
(TRUE_26)
@SP
A=M
A=A-1
A=A-1
M=-1
(END_26)
@SP
M=M-1

// push constant
@57
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@31
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@53
D=A
@SP
A=M
M=D
@SP
M=M+1

// add
@SP
M=M-1
A=M
D=M
A=A-1
M=M+D

// push constant
@112
D=A
@SP
A=M
M=D
@SP
M=M+1

// sub
@SP
M=M-1
A=M
D=M
A=A-1
M=M-D

// neg
@SP
A=M-1
M=-M

// and
@SP
A=M
A=A-1
A=A-1
D=M
A=A+1
D=D&M
A=A-1
M=D
@SP
M=M-1

// push constant
@82
D=A
@SP
A=M
M=D
@SP
M=M+1

// or
@SP
M=M-1
A=M
D=M
A=A-1
M=D|M

// not
@SP
A=M-1
M=!M

