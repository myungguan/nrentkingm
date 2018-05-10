	.file	"AllatUtil.c"
	.text
.globl _get_value
	.type	_get_value, @function
_get_value:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -12(%ebp)
	movl	$0, -16(%ebp)
	subl	$8, %esp
	pushl	8(%ebp)
	pushl	12(%ebp)
	call	strstr
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	jne	.L5
	movl	16(%ebp), %eax
	movb	$0, (%eax)
	jmp	.L4
.L5:
	subl	$12, %esp
	pushl	8(%ebp)
	call	strlen
	addl	$16, %esp
	movl	%eax, %edx
	leal	-4(%ebp), %eax
	addl	%edx, (%eax)
	movl	$0, -8(%ebp)
.L6:
	movl	20(%ebp), %eax
	decl	%eax
	cmpl	%eax, -8(%ebp)
	jl	.L9
	jmp	.L7
.L9:
	movl	-8(%ebp), %eax
	addl	-4(%ebp), %eax
	cmpb	$13, (%eax)
	je	.L7
	movl	-8(%ebp), %eax
	addl	-4(%ebp), %eax
	cmpb	$10, (%eax)
	je	.L7
	movl	-8(%ebp), %eax
	addl	-4(%ebp), %eax
	cmpb	$0, (%eax)
	jne	.L10
	jmp	.L7
.L10:
	call	__ctype_b_loc
	movl	%eax, %ecx
	movl	-8(%ebp), %eax
	addl	-4(%ebp), %eax
	movsbl	(%eax),%eax
	leal	(%eax,%eax), %edx
	movl	(%ecx), %eax
	movw	(%eax,%edx), %ax
	andl	$65535, %eax
	andl	$8192, %eax
	testl	%eax, %eax
	jne	.L12
	movl	$1, -16(%ebp)
.L12:
	cmpl	$0, -16(%ebp)
	je	.L8
	movl	-12(%ebp), %eax
	movl	%eax, %edx
	addl	16(%ebp), %edx
	movl	-8(%ebp), %eax
	addl	-4(%ebp), %eax
	movb	(%eax), %al
	movb	%al, (%edx)
	leal	-12(%ebp), %eax
	incl	(%eax)
.L8:
	leal	-8(%ebp), %eax
	incl	(%eax)
	jmp	.L6
.L7:
	leal	-8(%ebp), %eax
	decl	(%eax)
.L14:
	cmpl	$0, -8(%ebp)
	jns	.L17
	jmp	.L15
.L17:
	call	__ctype_b_loc
	movl	%eax, %ecx
	movl	-8(%ebp), %eax
	addl	16(%ebp), %eax
	movsbl	(%eax),%eax
	leal	(%eax,%eax), %edx
	movl	(%ecx), %eax
	movw	(%eax,%edx), %ax
	andl	$65535, %eax
	andl	$8192, %eax
	testl	%eax, %eax
	jne	.L16
	jmp	.L15
.L16:
	leal	-8(%ebp), %eax
	decl	(%eax)
	jmp	.L14
.L15:
	movl	-8(%ebp), %eax
	addl	16(%ebp), %eax
	incl	%eax
	movb	$0, (%eax)
.L4:
	leave
	ret
	.size	_get_value, .-_get_value
	.section	.rodata
.LC0:
	.string	"allat_enc_data="
.LC1:
	.string	"1"
	.text
.globl _check_enc
	.type	_check_enc, @function
_check_enc:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$4136, %esp
	subl	$4, %esp
	pushl	$4097
	pushl	$0
	leal	-4120(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	cmpl	$0, 8(%ebp)
	jne	.L20
	movl	$-1, -4128(%ebp)
	jmp	.L19
.L20:
	subl	$8, %esp
	pushl	8(%ebp)
	leal	-4120(%ebp), %eax
	pushl	%eax
	call	strcpy
	addl	$16, %esp
	leal	-4120(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC0
	pushl	%eax
	call	strstr
	addl	$16, %esp
	movl	%eax, -4124(%ebp)
	cmpl	$0, -4124(%ebp)
	jne	.L21
	movl	$-1, -4128(%ebp)
	jmp	.L19
.L21:
	movl	-4124(%ebp), %eax
	addl	$20, %eax
	subl	$4, %esp
	pushl	$1
	pushl	$.LC1
	pushl	%eax
	call	strncmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L22
	movl	$0, -4128(%ebp)
	jmp	.L19
.L22:
	movl	$1, -4128(%ebp)
.L19:
	movl	-4128(%ebp), %eax
	leave
	ret
	.size	_check_enc, .-_check_enc
	.section	.rodata
.LC2:
	.string	"SSL"
.LC3:
	.string	"tx.allatpay.com"
	.align 32
.LC4:
	.string	"/servlet/AllatPay/pay/approval.jsp"
	.align 32
.LC5:
	.string	"reply_cd=0230\nreply_msg=\276\317\310\243\310\255\277\300\267\371\n"
	.align 32
.LC6:
	.string	"reply_cd=0231\nreply_msg=\276\317\310\243\310\255 \303\274\305\251 \277\300\267\371\n"
	.text
.globl ApprovalReq
	.type	ApprovalReq, @function
ApprovalReq:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -4(%ebp)
	movl	$0, -8(%ebp)
	subl	$4, %esp
	pushl	$4
	pushl	$0
	pushl	16(%ebp)
	call	memset
	addl	$16, %esp
	subl	$8, %esp
	pushl	$.LC2
	pushl	12(%ebp)
	call	strcmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L25
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$443
	pushl	$.LC3
	pushl	$.LC4
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L27
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L24
.L25:
	subl	$12, %esp
	pushl	8(%ebp)
	call	_check_enc
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	jne	.L28
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$80
	pushl	$.LC3
	pushl	$.LC4
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L27
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L24
.L28:
	cmpl	$0, -4(%ebp)
	jle	.L31
	subl	$8, %esp
	pushl	$.LC5
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
	jmp	.L27
.L31:
	subl	$8, %esp
	pushl	$.LC6
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
.L27:
	movl	$0, -12(%ebp)
.L24:
	movl	-12(%ebp), %eax
	leave
	ret
	.size	ApprovalReq, .-ApprovalReq
	.section	.rodata
	.align 32
.LC7:
	.string	"/servlet/AllatPay/pay/sanction.jsp"
	.text
.globl SanctionReq
	.type	SanctionReq, @function
SanctionReq:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -4(%ebp)
	movl	$0, -8(%ebp)
	subl	$4, %esp
	pushl	$4
	pushl	$0
	pushl	16(%ebp)
	call	memset
	addl	$16, %esp
	subl	$8, %esp
	pushl	$.LC2
	pushl	12(%ebp)
	call	strcmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L34
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$443
	pushl	$.LC3
	pushl	$.LC7
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L36
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L33
.L34:
	subl	$12, %esp
	pushl	8(%ebp)
	call	_check_enc
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	jne	.L37
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$80
	pushl	$.LC3
	pushl	$.LC7
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L36
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L33
.L37:
	cmpl	$0, -4(%ebp)
	jle	.L40
	subl	$8, %esp
	pushl	$.LC5
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
	jmp	.L36
.L40:
	subl	$8, %esp
	pushl	$.LC6
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
.L36:
	movl	$0, -12(%ebp)
.L33:
	movl	-12(%ebp), %eax
	leave
	ret
	.size	SanctionReq, .-SanctionReq
	.section	.rodata
	.align 32
.LC8:
	.string	"/servlet/AllatPay/pay/cancel.jsp"
	.text
.globl CancelReq
	.type	CancelReq, @function
CancelReq:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -4(%ebp)
	movl	$0, -8(%ebp)
	subl	$4, %esp
	pushl	$4
	pushl	$0
	pushl	16(%ebp)
	call	memset
	addl	$16, %esp
	subl	$8, %esp
	pushl	$.LC2
	pushl	12(%ebp)
	call	strcmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L43
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$443
	pushl	$.LC3
	pushl	$.LC8
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L45
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L42
.L43:
	subl	$12, %esp
	pushl	8(%ebp)
	call	_check_enc
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	jne	.L46
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$80
	pushl	$.LC3
	pushl	$.LC8
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L45
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L42
.L46:
	cmpl	$0, -4(%ebp)
	jle	.L49
	subl	$8, %esp
	pushl	$.LC5
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
	jmp	.L45
.L49:
	subl	$8, %esp
	pushl	$.LC6
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
.L45:
	movl	$0, -12(%ebp)
.L42:
	movl	-12(%ebp), %eax
	leave
	ret
	.size	CancelReq, .-CancelReq
	.section	.rodata
	.align 32
.LC9:
	.string	"/servlet/AllatPay/pay/cash_registry.jsp"
	.text
.globl CashRegReq
	.type	CashRegReq, @function
CashRegReq:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -4(%ebp)
	movl	$0, -8(%ebp)
	subl	$4, %esp
	pushl	$4
	pushl	$0
	pushl	16(%ebp)
	call	memset
	addl	$16, %esp
	subl	$8, %esp
	pushl	$.LC2
	pushl	12(%ebp)
	call	strcmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L52
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$443
	pushl	$.LC3
	pushl	$.LC9
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L54
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L51
.L52:
	subl	$12, %esp
	pushl	8(%ebp)
	call	_check_enc
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	jne	.L55
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$80
	pushl	$.LC3
	pushl	$.LC9
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L54
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L51
.L55:
	cmpl	$0, -4(%ebp)
	jle	.L58
	subl	$8, %esp
	pushl	$.LC5
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
	jmp	.L54
.L58:
	subl	$8, %esp
	pushl	$.LC6
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
.L54:
	movl	$0, -12(%ebp)
.L51:
	movl	-12(%ebp), %eax
	leave
	ret
	.size	CashRegReq, .-CashRegReq
	.section	.rodata
	.align 32
.LC10:
	.string	"/servlet/AllatPay/pay/cash_approval.jsp"
	.text
.globl CashAppReq
	.type	CashAppReq, @function
CashAppReq:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -4(%ebp)
	movl	$0, -8(%ebp)
	subl	$4, %esp
	pushl	$4
	pushl	$0
	pushl	16(%ebp)
	call	memset
	addl	$16, %esp
	subl	$8, %esp
	pushl	$.LC2
	pushl	12(%ebp)
	call	strcmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L61
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$443
	pushl	$.LC3
	pushl	$.LC10
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L63
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L60
.L61:
	subl	$12, %esp
	pushl	8(%ebp)
	call	_check_enc
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	jne	.L64
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$80
	pushl	$.LC3
	pushl	$.LC10
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L63
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L60
.L64:
	cmpl	$0, -4(%ebp)
	jle	.L67
	subl	$8, %esp
	pushl	$.LC5
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
	jmp	.L63
.L67:
	subl	$8, %esp
	pushl	$.LC6
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
.L63:
	movl	$0, -12(%ebp)
.L60:
	movl	-12(%ebp), %eax
	leave
	ret
	.size	CashAppReq, .-CashAppReq
	.section	.rodata
	.align 32
.LC11:
	.string	"/servlet/AllatPay/pay/cash_cancel.jsp"
	.text
.globl CashCanReq
	.type	CashCanReq, @function
CashCanReq:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -4(%ebp)
	movl	$0, -8(%ebp)
	subl	$4, %esp
	pushl	$4
	pushl	$0
	pushl	16(%ebp)
	call	memset
	addl	$16, %esp
	subl	$8, %esp
	pushl	$.LC2
	pushl	12(%ebp)
	call	strcmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L70
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$443
	pushl	$.LC3
	pushl	$.LC11
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L72
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L69
.L70:
	subl	$12, %esp
	pushl	8(%ebp)
	call	_check_enc
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	jne	.L73
	subl	$8, %esp
	pushl	16(%ebp)
	pushl	$80
	pushl	$.LC3
	pushl	$.LC11
	pushl	$.LC3
	pushl	8(%ebp)
	call	SendRepo
	addl	$32, %esp
	movl	%eax, -8(%ebp)
	cmpl	$0, -8(%ebp)
	je	.L72
	movl	-8(%ebp), %eax
	movl	%eax, -12(%ebp)
	jmp	.L69
.L73:
	cmpl	$0, -4(%ebp)
	jle	.L76
	subl	$8, %esp
	pushl	$.LC5
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
	jmp	.L72
.L76:
	subl	$8, %esp
	pushl	$.LC6
	pushl	16(%ebp)
	call	strcpy
	addl	$16, %esp
.L72:
	movl	$0, -12(%ebp)
.L69:
	movl	-12(%ebp), %eax
	leave
	ret
	.size	CashCanReq, .-CashCanReq
	.section	.rodata
.LC12:
	.string	"\n<br>80\277\254\260\341\n<br>"
.LC13:
	.string	"\n<br>443\277\254\260\341\n<br>"
.LC14:
	.string	"reply_cd="
.LC15:
	.string	"0290"
.LC16:
	.string	"redirect_ip="
.LC17:
	.string	"redirect_port="
	.text
.globl SendRepo
	.type	SendRepo, @function
SendRepo:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$88, %esp
	movl	$0, -12(%ebp)
	movl	$443, -16(%ebp)
	subl	$4, %esp
	pushl	$5
	pushl	$0
	leal	-72(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	cmpl	$80, 24(%ebp)
	jne	.L79
	subl	$8, %esp
	pushl	28(%ebp)
	pushl	24(%ebp)
	pushl	20(%ebp)
	pushl	16(%ebp)
	pushl	12(%ebp)
	pushl	8(%ebp)
	call	SendReq
	addl	$32, %esp
	movl	%eax, -12(%ebp)
	subl	$12, %esp
	pushl	$.LC12
	call	printf
	addl	$16, %esp
	jmp	.L80
.L79:
	subl	$8, %esp
	pushl	28(%ebp)
	pushl	24(%ebp)
	pushl	20(%ebp)
	pushl	16(%ebp)
	pushl	12(%ebp)
	pushl	8(%ebp)
	call	SendReqSSL
	addl	$32, %esp
	movl	%eax, -12(%ebp)
	subl	$12, %esp
	pushl	$.LC13
	call	printf
	addl	$16, %esp
.L80:
	cmpl	$0, -12(%ebp)
	jne	.L81
	pushl	$5
	leal	-72(%ebp), %eax
	pushl	%eax
	pushl	28(%ebp)
	pushl	$.LC14
	call	_get_value
	addl	$16, %esp
	leal	-72(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC15
	pushl	%eax
	call	strcmp
	addl	$16, %esp
	testl	%eax, %eax
	jne	.L86
	pushl	$16
	leal	-40(%ebp), %eax
	pushl	%eax
	pushl	28(%ebp)
	pushl	$.LC16
	call	_get_value
	addl	$16, %esp
	pushl	$6
	leal	-56(%ebp), %eax
	pushl	%eax
	pushl	28(%ebp)
	pushl	$.LC17
	call	_get_value
	addl	$16, %esp
	leal	-56(%ebp), %eax
	subl	$12, %esp
	pushl	%eax
	call	atoi
	addl	$16, %esp
	movl	%eax, -16(%ebp)
	cmpl	$80, 24(%ebp)
	jne	.L83
	subl	$8, %esp
	pushl	28(%ebp)
	pushl	-16(%ebp)
	pushl	20(%ebp)
	pushl	16(%ebp)
	leal	-40(%ebp), %eax
	pushl	%eax
	pushl	8(%ebp)
	call	SendReq
	addl	$32, %esp
	movl	%eax, -12(%ebp)
	jmp	.L84
.L83:
	subl	$8, %esp
	pushl	28(%ebp)
	pushl	-16(%ebp)
	pushl	20(%ebp)
	pushl	16(%ebp)
	leal	-40(%ebp), %eax
	pushl	%eax
	pushl	8(%ebp)
	call	SendReqSSL
	addl	$32, %esp
	movl	%eax, -12(%ebp)
.L84:
	cmpl	$0, -12(%ebp)
	je	.L86
	movl	$-1, -76(%ebp)
	jmp	.L78
.L81:
	movl	$-1, -76(%ebp)
	jmp	.L78
.L86:
	movl	$0, -76(%ebp)
.L78:
	movl	-76(%ebp), %eax
	leave
	ret
	.size	SendRepo, .-SendRepo
	.section	.rodata
.LC18:
	.string	"%04d%02d%02d%02d%02d%02d"
.LC19:
	.string	"1.0.0.2"
.LC20:
	.string	"C"
	.align 32
.LC21:
	.string	"%s&allat_opt_lang=%s&allat_opt_ver=%s&allat_apply_ymdhms=%s"
	.align 32
.LC22:
	.string	"POST %s HTTP/1.0\r\nAccept: */*\r\nConnection: close\r\nContent-type: application/x-www-form-urlencoded\r\nHost: %s:%d\r\nContent-length: %d\r\n\r\n%s"
.LC23:
	.string	"\301\370\302\245\267\316 \272\270\263\273\264\302 \300\374\271\256\300\272(%d) : %s"
.LC24:
	.string	"\r\n\r\n"
.LC25:
	.string	"Content-Length:"
.LC26:
	.string	"Content-length:"
	.align 32
.LC27:
	.string	"reply_cd=0261\nreply_msg=SSL Socket \305\353\275\305 \277\300\267\371"
	.text
.globl SendReqSSL
	.type	SendReqSSL, @function
SendReqSSL:
	pushl	%ebp
	movl	%esp, %ebp
	pushl	%edi
	subl	$25748, %esp
	movl	$-1, -25528(%ebp)
	movl	$0, -25536(%ebp)
	movl	$0, -25540(%ebp)
	movl	$0, -25724(%ebp)
	subl	$4, %esp
	pushl	$15
	pushl	$0
	leal	-25512(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$4, %esp
	pushl	$9096
	pushl	$0
	leal	-9112(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$4, %esp
	pushl	$8192
	pushl	$0
	leal	-17304(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$4, %esp
	pushl	$8192
	pushl	$0
	leal	-25496(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$12, %esp
	pushl	$0
	call	time
	addl	$16, %esp
	movl	%eax, -25516(%ebp)
	subl	$12, %esp
	leal	-25516(%ebp), %eax
	pushl	%eax
	call	localtime
	addl	$16, %esp
	movl	%eax, -25520(%ebp)
	movl	-25520(%ebp), %eax
	pushl	(%eax)
	movl	-25520(%ebp), %eax
	pushl	4(%eax)
	movl	-25520(%ebp), %eax
	pushl	8(%eax)
	movl	-25520(%ebp), %eax
	pushl	12(%eax)
	movl	-25520(%ebp), %eax
	movl	16(%eax), %eax
	incl	%eax
	pushl	%eax
	movl	-25520(%ebp), %eax
	movl	20(%eax), %eax
	addl	$1900, %eax
	pushl	%eax
	pushl	$.LC18
	leal	-25512(%ebp), %eax
	pushl	%eax
	call	sprintf
	addl	$32, %esp
	subl	$8, %esp
	leal	-25512(%ebp), %eax
	pushl	%eax
	pushl	$.LC19
	pushl	$.LC20
	pushl	8(%ebp)
	pushl	$.LC21
	leal	-17304(%ebp), %eax
	pushl	%eax
	call	sprintf
	addl	$32, %esp
	subl	$12, %esp
	pushl	20(%ebp)
	call	gethostbyname
	addl	$16, %esp
	movl	%eax, -25708(%ebp)
	cmpl	$0, -25708(%ebp)
	jne	.L88
	jmp	.L89
.L88:
	subl	$4, %esp
	pushl	$16
	pushl	$0
	leal	-25704(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	movl	-25708(%ebp), %eax
	movw	8(%eax), %ax
	movw	%ax, -25704(%ebp)
	movl	24(%ebp), %eax
	andl	$65535, %eax
	subl	$12, %esp
	pushl	%eax
	call	htons
	addl	$16, %esp
	movw	%ax, -25702(%ebp)
	movl	-25708(%ebp), %eax
	movl	16(%eax), %eax
	movl	(%eax), %eax
	movl	(%eax), %eax
	movl	%eax, -25700(%ebp)
	movl	$16, -25532(%ebp)
	subl	$4, %esp
	pushl	$0
	pushl	$1
	pushl	$2
	call	socket
	addl	$16, %esp
	movl	%eax, -25528(%ebp)
	cmpl	$0, -25528(%ebp)
	jns	.L90
	jmp	.L89
.L90:
	subl	$8, %esp
	pushl	$1
	pushl	$13
	call	signal
	addl	$16, %esp
	subl	$4, %esp
	pushl	$16
	leal	-25704(%ebp), %eax
	pushl	%eax
	pushl	-25528(%ebp)
	call	connect
	addl	$16, %esp
	testl	%eax, %eax
	jns	.L91
	jmp	.L89
.L91:
	call	SSL_library_init
	subl	$12, %esp
	subl	$4, %esp
	call	SSLv3_method
	addl	$4, %esp
	pushl	%eax
	call	SSL_CTX_new
	addl	$16, %esp
	movl	%eax, -25536(%ebp)
	cmpl	$0, -25536(%ebp)
	jne	.L92
	jmp	.L89
.L92:
	subl	$12, %esp
	pushl	-25536(%ebp)
	call	SSL_new
	addl	$16, %esp
	movl	%eax, -25540(%ebp)
	cmpl	$0, -25540(%ebp)
	jne	.L93
	jmp	.L89
.L93:
	subl	$12, %esp
	pushl	-25540(%ebp)
	call	SSL_set_connect_state
	addl	$16, %esp
	subl	$8, %esp
	pushl	-25528(%ebp)
	pushl	-25540(%ebp)
	call	SSL_set_fd
	addl	$16, %esp
	cmpl	$-1, %eax
	jne	.L94
	jmp	.L89
.L94:
	subl	$12, %esp
	pushl	-25540(%ebp)
	call	SSL_connect
	addl	$16, %esp
	cmpl	$-1, %eax
	jne	.L95
	jmp	.L89
.L95:
	movl	$1, -25532(%ebp)
	subl	$4, %esp
	leal	-25532(%ebp), %eax
	pushl	%eax
	pushl	$21537
	pushl	-25528(%ebp)
	call	ioctl
	addl	$16, %esp
	subl	$4, %esp
	leal	-17304(%ebp), %eax
	pushl	%eax
	leal	-17304(%ebp), %eax
	subl	$4, %esp
	pushl	%eax
	call	strlen
	addl	$8, %esp
	pushl	%eax
	pushl	24(%ebp)
	pushl	20(%ebp)
	pushl	16(%ebp)
	pushl	$.LC22
	leal	-9112(%ebp), %eax
	pushl	%eax
	call	sprintf
	addl	$32, %esp
	movl	%eax, -25532(%ebp)
	subl	$4, %esp
	leal	-9112(%ebp), %eax
	pushl	%eax
	leal	-9112(%ebp), %eax
	subl	$4, %esp
	pushl	%eax
	call	strlen
	addl	$8, %esp
	pushl	%eax
	pushl	$.LC23
	call	printf
	addl	$16, %esp
	movl	$5, -25680(%ebp)
	movl	$0, -25676(%ebp)
	movl	$0, -25728(%ebp)
.L96:
	movl	-25728(%ebp), %eax
	cmpl	-25532(%ebp), %eax
	jl	.L98
	jmp	.L97
.L98:
	movl	$0, %eax
	movl	$32, %ecx
	leal	-25672(%ebp), %edi
#APP
	cld; rep; stosl
#NO_APP
	movl	%ecx, %eax
	movl	%eax, -25732(%ebp)
	movl	%edi, %eax
	movl	%eax, -25736(%ebp)
	movl	-25528(%ebp), %eax
	movl	%eax, %edx
	shrl	$5, %edx
	movl	-25528(%ebp), %eax
	andl	$31, %eax
#APP
	btsl %eax,-25672(%ebp,%edx,4)
#NO_APP
	subl	$12, %esp
	leal	-25680(%ebp), %eax
	pushl	%eax
	pushl	$0
	leal	-25672(%ebp), %eax
	pushl	%eax
	pushl	$0
	movl	-25528(%ebp), %eax
	incl	%eax
	pushl	%eax
	call	select
	addl	$32, %esp
	testl	%eax, %eax
	jg	.L100
	jmp	.L89
.L100:
	subl	$4, %esp
	movl	-25728(%ebp), %edx
	movl	-25532(%ebp), %eax
	subl	%edx, %eax
	pushl	%eax
	leal	-9112(%ebp), %eax
	addl	-25728(%ebp), %eax
	pushl	%eax
	pushl	-25540(%ebp)
	call	SSL_write
	addl	$16, %esp
	movl	%eax, -25716(%ebp)
	cmpl	$0, -25716(%ebp)
	jns	.L101
	subl	$8, %esp
	pushl	-25716(%ebp)
	pushl	-25540(%ebp)
	call	SSL_get_error
	addl	$16, %esp
	movl	%eax, -25524(%ebp)
	cmpl	$2, -25524(%ebp)
	je	.L96
	cmpl	$3, -25524(%ebp)
	je	.L96
	movl	$0, -25716(%ebp)
.L101:
	cmpl	$0, -25716(%ebp)
	jne	.L104
	jmp	.L89
.L104:
	movl	-25716(%ebp), %edx
	leal	-25728(%ebp), %eax
	addl	%edx, (%eax)
	jmp	.L96
.L97:
	movl	$22, -25680(%ebp)
	movl	$0, -25676(%ebp)
	movl	$0, -25728(%ebp)
	movl	$0, -25720(%ebp)
	movl	$8191, -25532(%ebp)
.L105:
	movl	-25728(%ebp), %eax
	cmpl	-25532(%ebp), %eax
	jl	.L107
	jmp	.L106
.L107:
	movl	$0, %eax
	movl	$32, %ecx
	leal	-25672(%ebp), %edi
#APP
	cld; rep; stosl
#NO_APP
	movl	%ecx, %eax
	movl	%eax, -25736(%ebp)
	movl	%edi, %eax
	movl	%eax, -25732(%ebp)
	movl	-25528(%ebp), %eax
	movl	%eax, %edx
	shrl	$5, %edx
	movl	-25528(%ebp), %eax
	andl	$31, %eax
#APP
	btsl %eax,-25672(%ebp,%edx,4)
#NO_APP
	subl	$12, %esp
	leal	-25680(%ebp), %eax
	pushl	%eax
	pushl	$0
	pushl	$0
	leal	-25672(%ebp), %eax
	pushl	%eax
	movl	-25528(%ebp), %eax
	incl	%eax
	pushl	%eax
	call	select
	addl	$32, %esp
	testl	%eax, %eax
	jg	.L109
	jmp	.L89
.L109:
	subl	$4, %esp
	movl	-25728(%ebp), %edx
	movl	-25532(%ebp), %eax
	subl	%edx, %eax
	pushl	%eax
	leal	-25496(%ebp), %eax
	addl	-25728(%ebp), %eax
	pushl	%eax
	pushl	-25540(%ebp)
	call	SSL_read
	addl	$16, %esp
	movl	%eax, -25716(%ebp)
	cmpl	$0, -25716(%ebp)
	jns	.L110
	subl	$8, %esp
	pushl	-25716(%ebp)
	pushl	-25540(%ebp)
	call	SSL_get_error
	addl	$16, %esp
	cmpl	$2, %eax
	jne	.L89
	jmp	.L105
.L110:
	cmpl	$0, -25716(%ebp)
	jne	.L113
	cmpl	$0, -25720(%ebp)
	jne	.L106
	jmp	.L89
.L113:
	movl	-25716(%ebp), %edx
	leal	-25728(%ebp), %eax
	addl	%edx, (%eax)
	leal	-25496(%ebp), %eax
	addl	-25728(%ebp), %eax
	movb	$0, (%eax)
	cmpl	$0, -25724(%ebp)
	jne	.L105
	leal	-25496(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC24
	pushl	%eax
	call	strstr
	addl	$16, %esp
	movl	%eax, -25712(%ebp)
	cmpl	$0, -25712(%ebp)
	jne	.L117
	movl	-25728(%ebp), %eax
	cmpl	-25532(%ebp), %eax
	jl	.L105
	jmp	.L89
.L117:
	movl	$1, -25724(%ebp)
	leal	-25496(%ebp), %edx
	movl	-25712(%ebp), %eax
	subl	%edx, %eax
	movl	%eax, %edx
	movl	-25728(%ebp), %eax
	subl	%edx, %eax
	subl	$4, %eax
	movl	%eax, -25728(%ebp)
	leal	-25496(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC25
	pushl	%eax
	call	strstr
	addl	$16, %esp
	movl	%eax, -25736(%ebp)
	cmpl	$0, -25736(%ebp)
	jne	.L120
	leal	-25496(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC26
	pushl	%eax
	call	strstr
	addl	$16, %esp
	movl	%eax, -25736(%ebp)
.L120:
	cmpl	$0, -25736(%ebp)
	je	.L121
	movl	-25736(%ebp), %eax
	addl	$16, %eax
	subl	$12, %esp
	pushl	%eax
	call	atoi
	addl	$16, %esp
	movl	%eax, -25532(%ebp)
	cmpl	$0, -25532(%ebp)
	jle	.L89
	cmpl	$8191, -25532(%ebp)
	ja	.L89
	jmp	.L124
.L121:
	movl	$1, -25720(%ebp)
	movl	$8191, -25532(%ebp)
.L124:
	subl	$4, %esp
	movl	-25728(%ebp), %eax
	incl	%eax
	pushl	%eax
	movl	-25712(%ebp), %eax
	addl	$4, %eax
	pushl	%eax
	leal	-25496(%ebp), %eax
	pushl	%eax
	call	memmove
	addl	$16, %esp
	jmp	.L105
.L106:
	subl	$8, %esp
	leal	-25496(%ebp), %eax
	pushl	%eax
	pushl	28(%ebp)
	call	strcpy
	addl	$16, %esp
	subl	$12, %esp
	pushl	-25540(%ebp)
	call	SSL_shutdown
	addl	$16, %esp
	subl	$12, %esp
	pushl	-25540(%ebp)
	call	SSL_free
	addl	$16, %esp
	subl	$12, %esp
	pushl	-25536(%ebp)
	call	SSL_CTX_free
	addl	$16, %esp
	subl	$12, %esp
	pushl	-25528(%ebp)
	call	close
	addl	$16, %esp
	movl	$0, -25740(%ebp)
	jmp	.L87
.L89:
	cmpl	$0, -25540(%ebp)
	je	.L125
	subl	$12, %esp
	pushl	-25540(%ebp)
	call	SSL_free
	addl	$16, %esp
.L125:
	cmpl	$0, -25536(%ebp)
	je	.L126
	subl	$12, %esp
	pushl	-25536(%ebp)
	call	SSL_CTX_free
	addl	$16, %esp
.L126:
	cmpl	$-1, -25528(%ebp)
	je	.L127
	subl	$12, %esp
	pushl	-25528(%ebp)
	call	close
	addl	$16, %esp
.L127:
	subl	$8, %esp
	pushl	$.LC27
	pushl	28(%ebp)
	call	strcpy
	addl	$16, %esp
	movl	$-1, -25740(%ebp)
.L87:
	movl	-25740(%ebp), %eax
	movl	-4(%ebp), %edi
	leave
	ret
	.size	SendReqSSL, .-SendReqSSL
	.section	.rodata
	.align 32
.LC28:
	.string	"POST %s HTTP/1.0\r\nAccept: */*\r\nConnection: close\r\nContent-type: application/x-www-form-urlencoded\r\nHost: %s:%d\r\nContent-length: %d\r\n\r\n%ss"
.LC29:
	.string	"sResData---->: %s\n"
	.align 32
.LC30:
	.string	"reply_cd=0271\nreply_msg=Socket \305\353\275\305 \277\300\267\371"
	.text
.globl SendReq
	.type	SendReq, @function
SendReq:
	pushl	%ebp
	movl	%esp, %ebp
	pushl	%edi
	subl	$25748, %esp
	movl	$-1, -25528(%ebp)
	movl	$0, -25724(%ebp)
	subl	$4, %esp
	pushl	$15
	pushl	$0
	leal	-25512(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$4, %esp
	pushl	$9096
	pushl	$0
	leal	-9112(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$4, %esp
	pushl	$8192
	pushl	$0
	leal	-17304(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$4, %esp
	pushl	$8192
	pushl	$0
	leal	-25496(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	subl	$12, %esp
	pushl	$0
	call	time
	addl	$16, %esp
	movl	%eax, -25516(%ebp)
	subl	$12, %esp
	leal	-25516(%ebp), %eax
	pushl	%eax
	call	localtime
	addl	$16, %esp
	movl	%eax, -25520(%ebp)
	movl	-25520(%ebp), %eax
	pushl	(%eax)
	movl	-25520(%ebp), %eax
	pushl	4(%eax)
	movl	-25520(%ebp), %eax
	pushl	8(%eax)
	movl	-25520(%ebp), %eax
	pushl	12(%eax)
	movl	-25520(%ebp), %eax
	movl	16(%eax), %eax
	incl	%eax
	pushl	%eax
	movl	-25520(%ebp), %eax
	movl	20(%eax), %eax
	addl	$1900, %eax
	pushl	%eax
	pushl	$.LC18
	leal	-25512(%ebp), %eax
	pushl	%eax
	call	sprintf
	addl	$32, %esp
	subl	$8, %esp
	leal	-25512(%ebp), %eax
	pushl	%eax
	pushl	$.LC19
	pushl	$.LC20
	pushl	8(%ebp)
	pushl	$.LC21
	leal	-17304(%ebp), %eax
	pushl	%eax
	call	sprintf
	addl	$32, %esp
	subl	$12, %esp
	pushl	20(%ebp)
	call	gethostbyname
	addl	$16, %esp
	movl	%eax, -25708(%ebp)
	cmpl	$0, -25708(%ebp)
	jne	.L129
	jmp	.L130
.L129:
	subl	$4, %esp
	pushl	$16
	pushl	$0
	leal	-25704(%ebp), %eax
	pushl	%eax
	call	memset
	addl	$16, %esp
	movl	-25708(%ebp), %eax
	movw	8(%eax), %ax
	movw	%ax, -25704(%ebp)
	movl	24(%ebp), %eax
	andl	$65535, %eax
	subl	$12, %esp
	pushl	%eax
	call	htons
	addl	$16, %esp
	movw	%ax, -25702(%ebp)
	movl	-25708(%ebp), %eax
	movl	16(%eax), %eax
	movl	(%eax), %eax
	movl	(%eax), %eax
	movl	%eax, -25700(%ebp)
	movl	$16, -25532(%ebp)
	subl	$4, %esp
	pushl	$0
	pushl	$1
	pushl	$2
	call	socket
	addl	$16, %esp
	movl	%eax, -25528(%ebp)
	cmpl	$0, -25528(%ebp)
	jns	.L131
	jmp	.L130
.L131:
	subl	$8, %esp
	pushl	$1
	pushl	$13
	call	signal
	addl	$16, %esp
	subl	$4, %esp
	pushl	$16
	leal	-25704(%ebp), %eax
	pushl	%eax
	pushl	-25528(%ebp)
	call	connect
	addl	$16, %esp
	testl	%eax, %eax
	jns	.L132
	jmp	.L130
.L132:
	movl	$1, -25532(%ebp)
	subl	$4, %esp
	leal	-25532(%ebp), %eax
	pushl	%eax
	pushl	$21537
	pushl	-25528(%ebp)
	call	ioctl
	addl	$16, %esp
	subl	$4, %esp
	leal	-17304(%ebp), %eax
	pushl	%eax
	leal	-17304(%ebp), %eax
	subl	$4, %esp
	pushl	%eax
	call	strlen
	addl	$8, %esp
	pushl	%eax
	pushl	24(%ebp)
	pushl	20(%ebp)
	pushl	16(%ebp)
	pushl	$.LC28
	leal	-9112(%ebp), %eax
	pushl	%eax
	call	sprintf
	addl	$32, %esp
	movl	%eax, -25532(%ebp)
	movl	$5, -25680(%ebp)
	movl	$0, -25676(%ebp)
	movl	$0, -25728(%ebp)
.L133:
	movl	-25728(%ebp), %eax
	cmpl	-25532(%ebp), %eax
	jl	.L135
	jmp	.L134
.L135:
	movl	$0, %eax
	movl	$32, %ecx
	leal	-25672(%ebp), %edi
#APP
	cld; rep; stosl
#NO_APP
	movl	%ecx, %eax
	movl	%eax, -25732(%ebp)
	movl	%edi, %eax
	movl	%eax, -25736(%ebp)
	movl	-25528(%ebp), %eax
	movl	%eax, %edx
	shrl	$5, %edx
	movl	-25528(%ebp), %eax
	andl	$31, %eax
#APP
	btsl %eax,-25672(%ebp,%edx,4)
#NO_APP
	subl	$12, %esp
	leal	-25680(%ebp), %eax
	pushl	%eax
	pushl	$0
	leal	-25672(%ebp), %eax
	pushl	%eax
	pushl	$0
	movl	-25528(%ebp), %eax
	incl	%eax
	pushl	%eax
	call	select
	addl	$32, %esp
	testl	%eax, %eax
	jg	.L137
	jmp	.L130
.L137:
	subl	$4, %esp
	movl	-25728(%ebp), %edx
	movl	-25532(%ebp), %eax
	subl	%edx, %eax
	pushl	%eax
	leal	-9112(%ebp), %eax
	addl	-25728(%ebp), %eax
	pushl	%eax
	pushl	-25528(%ebp)
	call	write
	addl	$16, %esp
	movl	%eax, -25716(%ebp)
	cmpl	$0, -25716(%ebp)
	jns	.L138
	call	__errno_location
	cmpl	$4, (%eax)
	je	.L133
	call	__errno_location
	cmpl	$11, (%eax)
	je	.L133
	jmp	.L130
.L138:
	cmpl	$0, -25716(%ebp)
	jne	.L141
	jmp	.L130
.L141:
	movl	-25716(%ebp), %edx
	leal	-25728(%ebp), %eax
	addl	%edx, (%eax)
	jmp	.L133
.L134:
	movl	$30, -25680(%ebp)
	movl	$0, -25676(%ebp)
	movl	$0, -25728(%ebp)
	movl	$0, -25720(%ebp)
	movl	$8191, -25532(%ebp)
.L142:
	movl	-25728(%ebp), %eax
	cmpl	-25532(%ebp), %eax
	jl	.L144
	jmp	.L143
.L144:
	movl	$0, %eax
	movl	$32, %ecx
	leal	-25672(%ebp), %edi
#APP
	cld; rep; stosl
#NO_APP
	movl	%ecx, %eax
	movl	%eax, -25736(%ebp)
	movl	%edi, %eax
	movl	%eax, -25732(%ebp)
	movl	-25528(%ebp), %eax
	movl	%eax, %edx
	shrl	$5, %edx
	movl	-25528(%ebp), %eax
	andl	$31, %eax
#APP
	btsl %eax,-25672(%ebp,%edx,4)
#NO_APP
	subl	$12, %esp
	leal	-25680(%ebp), %eax
	pushl	%eax
	pushl	$0
	pushl	$0
	leal	-25672(%ebp), %eax
	pushl	%eax
	movl	-25528(%ebp), %eax
	incl	%eax
	pushl	%eax
	call	select
	addl	$32, %esp
	testl	%eax, %eax
	jg	.L146
	jmp	.L130
.L146:
	subl	$4, %esp
	movl	-25728(%ebp), %edx
	movl	-25532(%ebp), %eax
	subl	%edx, %eax
	pushl	%eax
	leal	-25496(%ebp), %eax
	addl	-25728(%ebp), %eax
	pushl	%eax
	pushl	-25528(%ebp)
	call	read
	addl	$16, %esp
	movl	%eax, -25716(%ebp)
	cmpl	$0, -25716(%ebp)
	jns	.L147
	call	__errno_location
	cmpl	$4, (%eax)
	je	.L142
	call	__errno_location
	cmpl	$11, (%eax)
	je	.L142
	jmp	.L130
.L147:
	cmpl	$0, -25716(%ebp)
	jne	.L150
	cmpl	$0, -25720(%ebp)
	jne	.L143
	jmp	.L130
.L150:
	movl	-25716(%ebp), %edx
	leal	-25728(%ebp), %eax
	addl	%edx, (%eax)
	leal	-25496(%ebp), %eax
	addl	-25728(%ebp), %eax
	movb	$0, (%eax)
	cmpl	$0, -25724(%ebp)
	jne	.L142
	leal	-25496(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC24
	pushl	%eax
	call	strstr
	addl	$16, %esp
	movl	%eax, -25712(%ebp)
	cmpl	$0, -25712(%ebp)
	jne	.L154
	movl	-25728(%ebp), %eax
	cmpl	-25532(%ebp), %eax
	jl	.L142
	jmp	.L130
.L154:
	movl	$1, -25724(%ebp)
	leal	-25496(%ebp), %edx
	movl	-25712(%ebp), %eax
	subl	%edx, %eax
	movl	%eax, %edx
	movl	-25728(%ebp), %eax
	subl	%edx, %eax
	subl	$4, %eax
	movl	%eax, -25728(%ebp)
	leal	-25496(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC25
	pushl	%eax
	call	strstr
	addl	$16, %esp
	movl	%eax, -25736(%ebp)
	cmpl	$0, -25736(%ebp)
	jne	.L157
	leal	-25496(%ebp), %eax
	subl	$8, %esp
	pushl	$.LC26
	pushl	%eax
	call	strstr
	addl	$16, %esp
	movl	%eax, -25736(%ebp)
.L157:
	cmpl	$0, -25736(%ebp)
	je	.L158
	movl	-25736(%ebp), %eax
	addl	$16, %eax
	subl	$12, %esp
	pushl	%eax
	call	atoi
	addl	$16, %esp
	movl	%eax, -25532(%ebp)
	cmpl	$0, -25532(%ebp)
	jle	.L130
	cmpl	$8191, -25532(%ebp)
	ja	.L130
	jmp	.L161
.L158:
	movl	$1, -25720(%ebp)
	movl	$8191, -25532(%ebp)
.L161:
	subl	$4, %esp
	movl	-25728(%ebp), %eax
	incl	%eax
	pushl	%eax
	movl	-25712(%ebp), %eax
	addl	$4, %eax
	pushl	%eax
	leal	-25496(%ebp), %eax
	pushl	%eax
	call	memmove
	addl	$16, %esp
	jmp	.L142
.L143:
	subl	$8, %esp
	leal	-25496(%ebp), %eax
	pushl	%eax
	pushl	$.LC29
	call	printf
	addl	$16, %esp
	subl	$8, %esp
	leal	-25496(%ebp), %eax
	pushl	%eax
	pushl	28(%ebp)
	call	strcpy
	addl	$16, %esp
	subl	$12, %esp
	pushl	-25528(%ebp)
	call	close
	addl	$16, %esp
	movl	$0, -25740(%ebp)
	jmp	.L128
.L130:
	cmpl	$-1, -25528(%ebp)
	je	.L162
	subl	$12, %esp
	pushl	-25528(%ebp)
	call	close
	addl	$16, %esp
.L162:
	subl	$8, %esp
	pushl	$.LC30
	pushl	28(%ebp)
	call	strcpy
	addl	$16, %esp
	movl	$-1, -25740(%ebp)
.L128:
	movl	-25740(%ebp), %eax
	movl	-4(%ebp), %edi
	leave
	ret
	.size	SendReq, .-SendReq
	.section	.rodata
.LC31:
	.string	"%%%02X"
	.text
.globl _escape_string
	.type	_escape_string, @function
_escape_string:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$24, %esp
	movl	$0, -12(%ebp)
	cmpl	$0, 12(%ebp)
	jne	.L164
	movl	8(%ebp), %eax
	movb	$0, (%eax)
	jmp	.L163
.L164:
	subl	$12, %esp
	pushl	12(%ebp)
	call	strlen
	addl	$16, %esp
	movl	%eax, -12(%ebp)
	movl	$0, -8(%ebp)
	movl	$0, -4(%ebp)
.L165:
	movl	-4(%ebp), %eax
	cmpl	-12(%ebp), %eax
	jl	.L168
	jmp	.L166
.L168:
	call	__ctype_b_loc
	movl	%eax, %ecx
	movl	-4(%ebp), %eax
	addl	12(%ebp), %eax
	movsbl	(%eax),%eax
	leal	(%eax,%eax), %edx
	movl	(%ecx), %eax
	movw	(%eax,%edx), %ax
	andl	$65535, %eax
	andl	$8, %eax
	testl	%eax, %eax
	jne	.L169
	movl	-4(%ebp), %eax
	addl	12(%ebp), %eax
	cmpb	$95, (%eax)
	je	.L169
	movl	-4(%ebp), %eax
	addl	12(%ebp), %eax
	cmpb	$45, (%eax)
	je	.L169
	subl	$4, %esp
	movl	-4(%ebp), %eax
	addl	12(%ebp), %eax
	movb	(%eax), %al
	andl	$255, %eax
	pushl	%eax
	pushl	$.LC31
	movl	-8(%ebp), %eax
	addl	8(%ebp), %eax
	pushl	%eax
	call	sprintf
	addl	$16, %esp
	leal	-8(%ebp), %eax
	addl	$3, (%eax)
	jmp	.L167
.L169:
	movl	-8(%ebp), %eax
	movl	%eax, %edx
	addl	8(%ebp), %edx
	movl	-4(%ebp), %eax
	addl	12(%ebp), %eax
	movb	(%eax), %al
	movb	%al, (%edx)
	leal	-8(%ebp), %eax
	incl	(%eax)
.L167:
	leal	-4(%ebp), %eax
	incl	(%eax)
	jmp	.L165
.L166:
	movl	-8(%ebp), %eax
	addl	8(%ebp), %eax
	movb	$0, (%eax)
.L163:
	leave
	ret
	.size	_escape_string, .-_escape_string
	.section	.rodata
.LC32:
	.string	" \f\n\r\t\013"
	.text
.globl _ltrim
	.type	_ltrim, @function
_ltrim:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$8, %esp
	cmpl	$0, 8(%ebp)
	jne	.L172
	movl	8(%ebp), %eax
	movl	%eax, -8(%ebp)
	jmp	.L171
.L172:
	subl	$8, %esp
	pushl	$.LC32
	pushl	8(%ebp)
	call	strspn
	addl	$16, %esp
	movl	%eax, -4(%ebp)
	cmpl	$0, -4(%ebp)
	je	.L173
	subl	$4, %esp
	subl	$8, %esp
	pushl	8(%ebp)
	call	strlen
	addl	$12, %esp
	subl	-4(%ebp), %eax
	incl	%eax
	pushl	%eax
	movl	-4(%ebp), %eax
	addl	8(%ebp), %eax
	pushl	%eax
	pushl	8(%ebp)
	call	memmove
	addl	$16, %esp
.L173:
	movl	8(%ebp), %eax
	movl	%eax, -8(%ebp)
.L171:
	movl	-8(%ebp), %eax
	leave
	ret
	.size	_ltrim, .-_ltrim
.globl _rtrim
	.type	_rtrim, @function
_rtrim:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$8, %esp
	cmpl	$0, 8(%ebp)
	jne	.L175
	movl	8(%ebp), %eax
	movl	%eax, -8(%ebp)
	jmp	.L174
.L175:
	subl	$12, %esp
	pushl	8(%ebp)
	call	strlen
	addl	$16, %esp
	decl	%eax
	movl	%eax, -4(%ebp)
.L176:
	cmpl	$0, -4(%ebp)
	jns	.L179
	jmp	.L177
.L179:
	call	__ctype_b_loc
	movl	%eax, %ecx
	movl	-4(%ebp), %eax
	addl	8(%ebp), %eax
	movsbl	(%eax),%eax
	leal	(%eax,%eax), %edx
	movl	(%ecx), %eax
	movw	(%eax,%edx), %ax
	andl	$65535, %eax
	andl	$8192, %eax
	testl	%eax, %eax
	jne	.L178
	jmp	.L177
.L178:
	leal	-4(%ebp), %eax
	decl	(%eax)
	jmp	.L176
.L177:
	movl	-4(%ebp), %eax
	addl	8(%ebp), %eax
	incl	%eax
	movb	$0, (%eax)
	movl	8(%ebp), %eax
	movl	%eax, -8(%ebp)
.L174:
	movl	-8(%ebp), %eax
	leave
	ret
	.size	_rtrim, .-_rtrim
.globl Allat_trim
	.type	Allat_trim, @function
Allat_trim:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$8, %esp
	subl	$12, %esp
	pushl	8(%ebp)
	call	_ltrim
	addl	$4, %esp
	pushl	%eax
	call	_rtrim
	addl	$16, %esp
	leave
	ret
	.size	Allat_trim, .-Allat_trim
.globl initVL
	.type	initVL, @function
initVL:
	pushl	%ebp
	movl	%esp, %ebp
	subl	$8, %esp
	subl	$4, %esp
	pushl	$16384
	pushl	$0
	pushl	8(%ebp)
	call	memset
	addl	$16, %esp
	leave
	ret
	.size	initVL, .-initVL
	.section	.note.GNU-stack,"",@progbits
	.ident	"GCC: (GNU) 3.3.4"
