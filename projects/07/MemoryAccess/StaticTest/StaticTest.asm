// push constant
@111
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@333
D=A
@SP
A=M
M=D
@SP
M=M+1

// push constant
@888
D=A
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
@24
M=D

// pop static
@SP
M=M-1
A=M
D=M
@19
M=D

// pop static
@SP
M=M-1
A=M
D=M
@17
M=D

// push static
@19
D=M
@SP
A=M
M=D
@SP
M=M+1

// push static
@17
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

// push static
@24
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

