// push constant
@10
D=A
@SP
A=M
M=D
@SP
M=M+1

// pop local
@0
D=A
@LCL
M=D+M
@SP
M=M-1
A=M
D=M
@LCL
A=M
M=D
@0
D=A
@LCL
M=M-D

// push constant
@21
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@22
D=A
@SP
A=M
M=D
@SP
M=M+1

// pop argument
@2
D=A
@ARG
M=D+M
@SP
M=M-1
A=M
D=M
@ARG
A=M
M=D
@2
D=A
@ARG
M=M-D

// pop argument
@1
D=A
@ARG
M=D+M
@SP
M=M-1
A=M
D=M
@ARG
A=M
M=D
@1
D=A
@ARG
M=M-D

// push constant
@36
D=A
@SP
A=M
M=D
@SP
M=M+1

// pop this
@6
D=A
@THIS
M=D+M
@SP
M=M-1
A=M
D=M
@THIS
A=M
M=D
@6
D=A
@THIS
M=M-D

// push constant
@42
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@45
D=A
@SP
A=M
M=D
@SP
M=M+1

// pop that
@5
D=A
@THAT
M=D+M
@SP
M=M-1
A=M
D=M
@THAT
A=M
M=D
@5
D=A
@THAT
M=M-D

// pop that
@2
D=A
@THAT
M=D+M
@SP
M=M-1
A=M
D=M
@THAT
A=M
M=D
@2
D=A
@THAT
M=M-D

// push constant
@510
D=A
@SP
A=M
M=D
@SP
M=M+1

// pop temp
@SP
M=M-1
A=M
D=M
@R11
M=D

// push local
@0
D=A
@LCL
A=D+M
D=M
@SP
A=M
M=D
@SP
M=M+1

// push that
@5
D=A
@THAT
A=D+M
D=M
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

// sub
@SP
M=M-1
A=M
D=M
A=A-1
M=M-D

// push this
@6
D=A
@THIS
A=D+M
D=M
@SP
A=M
M=D
@SP
M=M+1

// push this
@6
D=A
@THIS
A=D+M
D=M
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

// sub
@SP
M=M-1
A=M
D=M
A=A-1
M=M-D

// push temp
@R11
D=M
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

