// init
@256
D=A
@SP
M=D
// call
@return-address_1
D=A
@SP
A=M
M=D
@SP
M=M+1
@LCL
D=M
@SP
A=M
M=D
@SP
M=M+1
@ARG
D=M
@SP
A=M
M=D
@SP
M=M+1
@THIS
D=M
@SP
A=M
M=D
@SP
M=M+1
@THAT
D=M
@SP
A=M
M=D
@SP
M=M+1
@SP
D=M
@0
D=D-A
@5
D=D-A
@ARG
M=D
@SP
D=M
@LCL
M=D
@Sys.init
0;JMP

(return-address_1)

// function
(Class1.set)

// push argument
@0
D=A
@ARG
A=D+M
D=M
@SP
A=M
M=D
@SP
M=M+1

// pop static
@SP
M=M-1
A=M
D=M
@Class1.0
M=D

// push argument
@1
D=A
@ARG
A=D+M
D=M
@SP
A=M
M=D
@SP
M=M+1

// pop static
@SP
M=M-1
A=M
D=M
@Class1.1
M=D

// push constant
@0
D=A
@SP
A=M
M=D
@SP
M=M+1

// reutrn 
@LCL
D=M
@R13
M=D
@5
D=A
@R13
A=M-D
D=M
@R14
M=D
@SP
M=M-1
A=M
D=M
@ARG
A=M
M=D
@ARG
D=M+1
@SP
M=D
@R13
AM=M-1
D=M
@THAT
M=D
@R13
AM=M-1
D=M
@THIS
M=D
@R13
AM=M-1
D=M
@ARG
M=D
@R13
AM=M-1
D=M
@LCL
M=D
@R14
A=M
0;JMP
// function
(Class1.get)

// push static
@Class1.0
D=M
@SP
A=M
M=D
@SP
M=M+1

// push static
@Class1.1
D=M
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

// reutrn 
@LCL
D=M
@R13
M=D
@5
D=A
@R13
A=M-D
D=M
@R14
M=D
@SP
M=M-1
A=M
D=M
@ARG
A=M
M=D
@ARG
D=M+1
@SP
M=D
@R13
AM=M-1
D=M
@THAT
M=D
@R13
AM=M-1
D=M
@THIS
M=D
@R13
AM=M-1
D=M
@ARG
M=D
@R13
AM=M-1
D=M
@LCL
M=D
@R14
A=M
0;JMP
// function
(Sys.init)

// push constant
@6
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@8
D=A
@SP
A=M
M=D
@SP
M=M+1

// call
@return-address_2
D=A
@SP
A=M
M=D
@SP
M=M+1
@LCL
D=M
@SP
A=M
M=D
@SP
M=M+1
@ARG
D=M
@SP
A=M
M=D
@SP
M=M+1
@THIS
D=M
@SP
A=M
M=D
@SP
M=M+1
@THAT
D=M
@SP
A=M
M=D
@SP
M=M+1
@SP
D=M
@2
D=D-A
@5
D=D-A
@ARG
M=D
@SP
D=M
@LCL
M=D
@Class1.set
0;JMP

(return-address_2)

// pop temp
@SP
M=M-1
A=M
D=M
@R8
M=D

// push constant
@23
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@15
D=A
@SP
A=M
M=D
@SP
M=M+1

// call
@return-address_3
D=A
@SP
A=M
M=D
@SP
M=M+1
@LCL
D=M
@SP
A=M
M=D
@SP
M=M+1
@ARG
D=M
@SP
A=M
M=D
@SP
M=M+1
@THIS
D=M
@SP
A=M
M=D
@SP
M=M+1
@THAT
D=M
@SP
A=M
M=D
@SP
M=M+1
@SP
D=M
@2
D=D-A
@5
D=D-A
@ARG
M=D
@SP
D=M
@LCL
M=D
@Class2.set
0;JMP

(return-address_3)

// pop temp
@SP
M=M-1
A=M
D=M
@R8
M=D

// call
@return-address_4
D=A
@SP
A=M
M=D
@SP
M=M+1
@LCL
D=M
@SP
A=M
M=D
@SP
M=M+1
@ARG
D=M
@SP
A=M
M=D
@SP
M=M+1
@THIS
D=M
@SP
A=M
M=D
@SP
M=M+1
@THAT
D=M
@SP
A=M
M=D
@SP
M=M+1
@SP
D=M
@0
D=D-A
@5
D=D-A
@ARG
M=D
@SP
D=M
@LCL
M=D
@Class1.get
0;JMP

(return-address_4)

// call
@return-address_5
D=A
@SP
A=M
M=D
@SP
M=M+1
@LCL
D=M
@SP
A=M
M=D
@SP
M=M+1
@ARG
D=M
@SP
A=M
M=D
@SP
M=M+1
@THIS
D=M
@SP
A=M
M=D
@SP
M=M+1
@THAT
D=M
@SP
A=M
M=D
@SP
M=M+1
@SP
D=M
@0
D=D-A
@5
D=D-A
@ARG
M=D
@SP
D=M
@LCL
M=D
@Class2.get
0;JMP

(return-address_5)

(Sys$WHILE)

// goto
@Sys$WHILE
0;JMP

// function
(Class2.set)

// push argument
@0
D=A
@ARG
A=D+M
D=M
@SP
A=M
M=D
@SP
M=M+1

// pop static
@SP
M=M-1
A=M
D=M
@Class2.0
M=D

// push argument
@1
D=A
@ARG
A=D+M
D=M
@SP
A=M
M=D
@SP
M=M+1

// pop static
@SP
M=M-1
A=M
D=M
@Class2.1
M=D

// push constant
@0
D=A
@SP
A=M
M=D
@SP
M=M+1

// reutrn 
@LCL
D=M
@R13
M=D
@5
D=A
@R13
A=M-D
D=M
@R14
M=D
@SP
M=M-1
A=M
D=M
@ARG
A=M
M=D
@ARG
D=M+1
@SP
M=D
@R13
AM=M-1
D=M
@THAT
M=D
@R13
AM=M-1
D=M
@THIS
M=D
@R13
AM=M-1
D=M
@ARG
M=D
@R13
AM=M-1
D=M
@LCL
M=D
@R14
A=M
0;JMP
// function
(Class2.get)

// push static
@Class2.0
D=M
@SP
A=M
M=D
@SP
M=M+1

// push static
@Class2.1
D=M
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

// reutrn 
@LCL
D=M
@R13
M=D
@5
D=A
@R13
A=M-D
D=M
@R14
M=D
@SP
M=M-1
A=M
D=M
@ARG
A=M
M=D
@ARG
D=M+1
@SP
M=D
@R13
AM=M-1
D=M
@THAT
M=D
@R13
AM=M-1
D=M
@THIS
M=D
@R13
AM=M-1
D=M
@ARG
M=D
@R13
AM=M-1
D=M
@LCL
M=D
@R14
A=M
0;JMP
