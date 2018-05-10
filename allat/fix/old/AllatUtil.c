		/******************************  Comment  *****************************
			File Name		    : AllatUtil.c
			File Description	: Allat Script API Utility Function(Class)
			[ Notice ]
			 이 파일은 NewAllatPay를 사용하기 위한 Utility Function을 구현한 
			Source Code입니다. 이 파일에 내용을 임의로 수정하실 경우 기술지원을 
			받으실 수 없음을 알려드립니다. 이 파일 내용에 문제가 있을 경우, 
			아래 연락처로 문의 주시기 바랍니다.

			TEL			    : 02-3783-9990
			EMAIL			: allatpay@allat.co.kr
			Homepage		: www.allatpay.com
		 ***********  Copyright Allat Corp. All Right Reserved  **************/
#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <netdb.h>
#include <errno.h>
#include <sys/socket.h>
#include <sys/select.h>
#include <sys/time.h>
#include <sys/types.h>
#include <unistd.h>
#include <stdlib.h>
#include <openssl/ssl.h>
#include <signal.h>
#include <sys/ioctl.h>
#include "AllatUtil.h"

void getValue(const char* sKey, const char* sMsg, char *sRet, int nLen) {
	char *p;
	int i, j=0, f=0;

	p=strstr(sMsg, sKey);
        if(!p) {
		sRet[0]=0;
		return;
	}
	p+=strlen(sKey);

	for(i=0; i<nLen-1; i++) {
		if(p[i]=='\r' || p[i]=='\n' || p[i]==0) break;
		if(!isspace(p[i])) f=1;
		if(f) sRet[j++]=p[i];
	}
	for(i=i-1; i>=0; i--) if(!isspace(sRet[i])) break;
	sRet[i+1]=0;
}

int _check_enc(const char *sMsg){
    char sSrc[4096+1];
    char *sBegin;
    
    memset(sSrc,0,sizeof(sSrc));
    
    if(sMsg == NULL ){
        return -1;   
    }
    strcpy(sSrc,sMsg);
       
    sBegin=strstr(sSrc,"allat_enc_data=");

    if(sBegin == NULL ){
        return -1;
    }
    
    if( strncmp(sBegin+15+5, "1", 1)==0 ){
        return 0;
    }else{
        return 1;
    }
}


int ApprovalReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt){
    
    int  nChkEnc=0;
    int  nRet=0;
       
    memset(sRetTxt, 0, sizeof(sRetTxt));
    
    if( strcmp(sSsl_flag,"SSL")==0 ){
        nRet=SendRepo( sAt_data, ALLAT_ADDR, APPROVAL_URI, ALLAT_HOST, 443, sRetTxt);
        if( nRet != 0 ){
            return nRet;
        }
    }else{
        nChkEnc=_check_enc( sAt_data );
        if( nChkEnc == 0 ){      /** 암호화 됨 **/
            nRet=SendRepo( sAt_data, ALLAT_ADDR, APPROVAL_URI, ALLAT_HOST, 80, sRetTxt);
            if( nRet != 0 ){
                return nRet;                
            }
        }else if( nChkEnc > 0 ){ /** 암호화 되지 않음  **/
            strcpy(sRetTxt,"reply_cd=0230\nreply_msg=암호화오류\n");
        }else{                   /** 에러 **/
            strcpy(sRetTxt,"reply_cd=0231\nreply_msg=암호화 체크 오류\n");
        }
    }
    return 0;
}

int SanctionReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt){

    int  nChkEnc=0;
    int  nRet=0;
       
    memset(sRetTxt, 0, sizeof(sRetTxt));
    
    if( strcmp(sSsl_flag,"SSL")==0 ){
        nRet=SendRepo( sAt_data, ALLAT_ADDR, SANCTION_URI, ALLAT_HOST, 443, sRetTxt);
        if( nRet != 0 ){
            return nRet;
        }
    }else{
        nChkEnc=_check_enc( sAt_data );
        if( nChkEnc == 0 ){      /** 암호화 됨 **/
            nRet=SendRepo( sAt_data, ALLAT_ADDR, SANCTION_URI, ALLAT_HOST, 80, sRetTxt);
            if( nRet != 0 ){
                return nRet;                
            }
        }else if( nChkEnc > 0 ){ /** 암호화 되지 않음  **/
            strcpy(sRetTxt,"reply_cd=0230\nreply_msg=암호화오류\n");
        }else{                   /** 에러 **/
            strcpy(sRetTxt,"reply_cd=0231\nreply_msg=암호화 체크 오류\n");
        }
    }
    return 0;
    
}

int CancelReq(const char *sAt_data, const char *sSsl_flag,char *sRetTxt ){

    int  nChkEnc=0;
    int  nRet=0;
       
    memset(sRetTxt, 0, sizeof(sRetTxt));
    
    if( strcmp(sSsl_flag,"SSL")==0 ){
        nRet=SendRepo( sAt_data, ALLAT_ADDR, CANCEL_URI, ALLAT_HOST, 443, sRetTxt);
        if( nRet != 0 ){
            return nRet;
        }
    }else{
        nChkEnc=_check_enc( sAt_data );
        if( nChkEnc == 0 ){      /** 암호화 됨 **/
            nRet=SendRepo( sAt_data, ALLAT_ADDR, CANCEL_URI, ALLAT_HOST, 80, sRetTxt);
            if( nRet != 0 ){
                return nRet;                
            }
        }else if( nChkEnc > 0 ){ /** 암호화 되지 않음  **/
            strcpy(sRetTxt,"reply_cd=0230\nreply_msg=암호화오류\n");
        }else{                   /** 에러 **/
            strcpy(sRetTxt,"reply_cd=0231\nreply_msg=암호화 체크 오류\n");
        }
    }
    return 0;
        
}

int CashRegReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt){

    int  nChkEnc=0;
    int  nRet=0;
       
    memset(sRetTxt, 0, sizeof(sRetTxt));
    
    if( strcmp(sSsl_flag,"SSL")==0 ){
        nRet=SendRepo( sAt_data, ALLAT_ADDR, CASHREG_URI, ALLAT_HOST, 443, sRetTxt);
        if( nRet != 0 ){
            return nRet;
        }
    }else{
        nChkEnc=_check_enc( sAt_data );
        if( nChkEnc == 0 ){      /** 암호화 됨 **/
            nRet=SendRepo( sAt_data, ALLAT_ADDR, CASHREG_URI, ALLAT_HOST, 80, sRetTxt);
            if( nRet != 0 ){
                return nRet;                
            }
        }else if( nChkEnc > 0 ){ /** 암호화 되지 않음  **/
            strcpy(sRetTxt,"reply_cd=0230\nreply_msg=암호화오류\n");
        }else{                   /** 에러 **/
            strcpy(sRetTxt,"reply_cd=0231\nreply_msg=암호화 체크 오류\n");
        }
    }
    return 0;
            
}

int CashAppReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt){

    int  nChkEnc=0;
    int  nRet=0;
       
    memset(sRetTxt, 0, sizeof(sRetTxt));
    
    if( strcmp(sSsl_flag,"SSL")==0 ){
        nRet=SendRepo( sAt_data, ALLAT_ADDR, CASHAPP_URI, ALLAT_HOST, 443, sRetTxt);
        if( nRet != 0 ){
            return nRet;
        }
    }else{
        nChkEnc=_check_enc( sAt_data );
        if( nChkEnc == 0 ){      /** 암호화 됨 **/
            nRet=SendRepo( sAt_data, ALLAT_ADDR, CASHAPP_URI, ALLAT_HOST, 80, sRetTxt);
            if( nRet != 0 ){
                return nRet;                
            }
        }else if( nChkEnc > 0 ){ /** 암호화 되지 않음  **/
            strcpy(sRetTxt,"reply_cd=0230\nreply_msg=암호화오류\n");
        }else{                   /** 에러 **/
            strcpy(sRetTxt,"reply_cd=0231\nreply_msg=암호화 체크 오류\n");
        }
    }
    return 0;
    
}

int CashCanReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt){

    int  nChkEnc=0;
    int  nRet=0;
       
    memset(sRetTxt, 0, sizeof(sRetTxt));
    
    if( strcmp(sSsl_flag,"SSL")==0 ){
        nRet=SendRepo( sAt_data, ALLAT_ADDR, CASHCAN_URI, ALLAT_HOST, 443, sRetTxt);
        if( nRet != 0 ){
            return nRet;
        }
    }else{
        nChkEnc=_check_enc( sAt_data );
        if( nChkEnc == 0 ){      /** 암호화 됨 **/
            nRet=SendRepo( sAt_data, ALLAT_ADDR, CASHCAN_URI, ALLAT_HOST, 80, sRetTxt);
            if( nRet != 0 ){
                return nRet;                
            }
        }else if( nChkEnc > 0 ){ /** 암호화 되지 않음  **/
            strcpy(sRetTxt,"reply_cd=0230\nreply_msg=암호화오류\n");
        }else{                   /** 에러 **/
            strcpy(sRetTxt,"reply_cd=0231\nreply_msg=암호화 체크 오류\n");
        }
    }
    return 0;
   
}

int EscrowChkReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt){

    int  nChkEnc=0;
    int  nRet=0;
       
    memset(sRetTxt, 0, sizeof(sRetTxt));
    
    if( strcmp(sSsl_flag,"SSL")==0 ){
        nRet=SendRepo( sAt_data, ALLAT_ADDR, ESCROWCHK_URI, ALLAT_HOST, 443, sRetTxt);
        if( nRet != 0 ){
            return nRet;
        }
    }else{
        nChkEnc=_check_enc( sAt_data );
        if( nChkEnc == 0 ){      /** 암호화 됨 **/
            nRet=SendRepo( sAt_data, ALLAT_ADDR, ESCROWCHK_URI, ALLAT_HOST, 80, sRetTxt);
            if( nRet != 0 ){
                return nRet;                
            }
        }else if( nChkEnc > 0 ){ /** 암호화 되지 않음  **/
            strcpy(sRetTxt,"reply_cd=0230\nreply_msg=암호화오류\n");
        }else{                   /** 에러 **/
            strcpy(sRetTxt,"reply_cd=0231\nreply_msg=암호화 체크 오류\n");
        }
    }
    return 0;
            
}

int SendRepo(const char *sAt_data, const char *sAt_addr, const char *sAt_uri, const char *sAt_host , int iAt_Port, char *sRetTmp){
    
    int  nTmp=0;
    int  nRePort=443;
    char sReIp  [15+1];
    char sRePort[ 5+1];
    char sRetCd [ 4+1];
    
    memset(sRetCd,0,sizeof(sRetCd));
    if( iAt_Port == 80 ){
        nTmp=SendReq(sAt_data, sAt_addr, sAt_uri, sAt_host, iAt_Port, sRetTmp );
printf("\n<br>80연결\n<br>\n");        
    }else{
        nTmp=SendReqSSL(sAt_data, sAt_addr, sAt_uri, sAt_host, iAt_Port, sRetTmp );
printf("\n<br>443연결\n<br>\n");                
    }
    if( nTmp == 0 ){
        getValue("reply_cd=", sRetTmp, sRetCd, sizeof(sRetCd) );
        if( strcmp(sRetCd,"0290") == 0 ){
            getValue("redirect_ip=", sRetTmp, sReIp, sizeof(sReIp) );
            getValue("redirect_port=", sRetTmp, sRePort, sizeof(sRePort) );
            nRePort=atoi(sRePort);
            if( iAt_Port == 80 ){
                nTmp=SendReq(sAt_data, sReIp, sAt_uri, sAt_host, nRePort, sRetTmp );
            }else{
                nTmp=SendReqSSL(sAt_data, sReIp, sAt_uri, sAt_host, nRePort, sRetTmp );
            }
            if( nTmp!=0 ){
                /*** 에러 처리 ***/
                /*** sRetTmp  ***/
                return -1;
            }
        }
    }else{
        /*** 에러 처리 ***/
        /*** sRetTmp  ***/
        return -1;   
    }
    return 0;
}

int SendReqSSL(const char *sAt_data, const char *aAt_addr, const char *sAt_uri, const char *sAt_host, int iAt_Port, char *sRetTmp){
    char sRequest  [9095+1];   
    char sReqData  [8191+1]; /** Request Data  **/
    char sResData  [8191+1]; /** Response Data **/

    /** Apply Time Check **/
    char sApplyYMDHMS[14+1];
    time_t ti;
    struct tm *tp;
    
    /** Socket Connect **/
    int e;
	int fd=-1;
	int len;
	SSL_CTX *ctx=NULL;
	SSL *ssl=NULL;

	fd_set fds;
	struct timeval tv;

	struct sockaddr_in server;
	struct hostent *pstHe;

	char *ptr;
	int n, ncl, header, total;

	header=0;
    memset(sApplyYMDHMS,0,sizeof(sApplyYMDHMS));
    memset(sRequest,0,sizeof(sRequest));
    memset(sReqData,0,sizeof(sReqData));
    memset(sResData,0,sizeof(sResData));


    ti=time(NULL);
    tp=localtime(&ti);
    
    sprintf(sApplyYMDHMS,"%04d%02d%02d%02d%02d%02d",
             tp->tm_year+1900, tp->tm_mon+1, tp->tm_mday, tp->tm_hour, tp->tm_min, tp->tm_sec);
    
    sprintf(sReqData,"%s&allat_opt_lang=%s&allat_opt_ver=%s&allat_apply_ymdhms=%s",
            sAt_data,UTIL_LANG,UTIL_VER,sApplyYMDHMS);
	/************* Thread에 안전 하지 않습니다. **************************/
	pstHe=gethostbyname(sAt_host);		// AllatServer IP

	if(!pstHe) goto _bad;

	memset(&server, 0, sizeof(server));
	server.sin_family=pstHe->h_addrtype;
	server.sin_port=htons(iAt_Port);
	server.sin_addr.s_addr=((unsigned long *) (pstHe->h_addr_list[0]))[0];
	/********************************************************************/

	len=sizeof(server);

	fd=socket(AF_INET, SOCK_STREAM, 0);
	if(fd<0) goto _bad;		

	signal(SIGPIPE, SIG_IGN);
	if(connect(fd, (struct sockaddr *)&server, sizeof server) < 0) goto _bad;
	    
	SSL_library_init();
	if(!(ctx=SSL_CTX_new(SSLv3_method()))) goto _bad;
	if(!(ssl=SSL_new(ctx))) goto _bad;
	SSL_set_connect_state(ssl);
	if(SSL_set_fd(ssl, fd) == -1) goto _bad;
	if(SSL_connect(ssl) == -1) goto _bad;
	
	len=1;
	ioctl(fd, FIONBIO, &len);

	len=sprintf(sRequest,					// Check Send Config
			"POST %s HTTP/1.0\r\n"
			"Accept: */*\r\n"
			"Connection: close\r\n"
			"Content-type: application/x-www-form-urlencoded\r\n"
			"Host: %s:%d\r\n"
			"Content-length: %d\r\n"
			"\r\n"
			"%s", sAt_uri, sAt_host, iAt_Port, strlen(sReqData), sReqData);
printf("진짜로 보내는 전문은(%d) : %s",strlen(sRequest),sRequest);
	tv.tv_sec=5;
	tv.tv_usec=0;
	total=0;
	
	while(total<len) {
		FD_ZERO(&fds);
		FD_SET(fd, &fds);
		if(select(fd+1, NULL, &fds, NULL, &tv)<=0) goto _bad; // interrupt 무시

		n=SSL_write(ssl, sRequest+total, len-total);
		if(n<0) {
			e=SSL_get_error(ssl, n);
			if( e!=SSL_ERROR_WANT_READ && e!=SSL_ERROR_WANT_WRITE) n=0;
			else continue;
		}
		if(n==0) goto _bad;
		total+=n;
	}

	tv.tv_sec=22;
	tv.tv_usec=0;
	total=0;
	ncl=0;
	len=sizeof(sResData)-1;			

	while(total<len) {
		FD_ZERO(&fds);
		FD_SET(fd, &fds);
		if(select(fd+1, &fds, NULL, NULL, &tv)<=0) goto _bad; // interrupt 무시

		n=SSL_read(ssl, sResData+total, len-total);		///Server RESPONSE
		if (n<0) {
			if(SSL_get_error(ssl, n)==SSL_ERROR_WANT_READ) continue;
			else goto _bad;
		}
		if(n==0) {
			if(!ncl) goto _bad;
			else break;
		}
		total+=n;
		sResData[total]=0;

		if(!header) {
			char *p;
			ptr=strstr(sResData, "\r\n\r\n");
			if(!ptr) {
				if(total>=len) goto _bad; // bad header length
				else continue;
			}
			header=1;
			total=total-(ptr-sResData)-4;

			p=strstr(sResData, "Content-Length:");
			if(!p) p=strstr(sResData, "Content-length:");
			if(p) {
				len=atoi(p+16);
				if(len<=0 || len>sizeof(sResData)-1) goto _bad; // bad body length
			}
			else {
				ncl=1;
				len=sizeof(sResData)-1;
			}

			memmove(sResData, ptr+4, total+1);
		}
	}
	strcpy(sRetTmp,sResData);
	SSL_shutdown(ssl);
	SSL_free(ssl);
	SSL_CTX_free(ctx);
	close(fd);
    return 0;
_bad:
	if(ssl) SSL_free(ssl);
	if(ctx) SSL_CTX_free(ctx);
	if(fd!=-1) close(fd);
    strcpy(sRetTmp,"reply_cd=0261\nreply_msg=SSL Socket 통신 오류");
	return -1;    
}

int SendReq(const char *sAt_data, const char *aAt_addr, const char *sAt_uri, const char *sAt_host, int iAt_Port, char *sRetTmp){
    char sRequest  [9095+1];   
    char sReqData  [8191+1]; /** Request Data  **/
    char sResData  [8191+1]; /** Response Data **/

    /** Apply Time Check **/
    char sApplyYMDHMS[14+1];
    time_t ti;
    struct tm *tp;
    
    /** Socket Connect **/
	int fd=-1;
	int len;

	fd_set fds;
	struct timeval tv;

	struct sockaddr_in server;
	struct hostent *pstHe;

	char *ptr;
	int n, ncl, header, total;

	header=0;
    memset(sApplyYMDHMS,0,sizeof(sApplyYMDHMS));
    memset(sRequest,0,sizeof(sRequest));
    memset(sReqData,0,sizeof(sReqData));
    memset(sResData,0,sizeof(sResData));

    ti=time(NULL);
    tp=localtime(&ti);
    
    sprintf(sApplyYMDHMS,"%04d%02d%02d%02d%02d%02d",
             tp->tm_year+1900, tp->tm_mon+1, tp->tm_mday, tp->tm_hour, tp->tm_min, tp->tm_sec);
    
    sprintf(sReqData,"%s&allat_opt_lang=%s&allat_opt_ver=%s&allat_apply_ymdhms=%s",
            sAt_data,UTIL_LANG,UTIL_VER,sApplyYMDHMS);
	/************* Thread에 안전 하지 않습니다. **************************/
	pstHe=gethostbyname(sAt_host);		// AllatServer IP

	if(!pstHe) goto _bad;

	memset(&server, 0, sizeof(server));
	server.sin_family=pstHe->h_addrtype;
	server.sin_port=htons(iAt_Port);
	server.sin_addr.s_addr=((unsigned long *) (pstHe->h_addr_list[0]))[0];
	/********************************************************************/
	len=sizeof(server);

	fd=socket(AF_INET, SOCK_STREAM, 0);
	if(fd<0) goto _bad;		

	signal(SIGPIPE, SIG_IGN);
	if(connect(fd, (struct sockaddr *)&server, sizeof server) < 0) goto _bad;
	    
	len=1;
	ioctl(fd, FIONBIO, &len);
	len=sprintf(sRequest,					// Check Send Config
			"POST %s HTTP/1.0\r\n"
			"Accept: */*\r\n"
			"Connection: close\r\n"
			"Content-type: application/x-www-form-urlencoded\r\n"
			"Host: %s:%d\r\n"
			"Content-length: %d\r\n"
			"\r\n"
			"%ss", sAt_uri, sAt_host, iAt_Port, strlen(sReqData), sReqData);
			
	tv.tv_sec=5;
	tv.tv_usec=0;
	total=0;
	while(total<len) {
		FD_ZERO(&fds);
		FD_SET(fd, &fds);
		if(select(fd+1, NULL, &fds, NULL, &tv)<=0) goto _bad; // interrupt 무시

		n=write(fd, sRequest+total, len-total);		///SEND REQUEST
		if(n<0) {
			if(errno!=EINTR && errno!=EAGAIN) goto _bad;
            else continue;    
		}
		if(n==0) goto _bad;
		total+=n;
	}

	tv.tv_sec=30;
	tv.tv_usec=0;
	total=0;
	ncl=0;
	len=sizeof(sResData)-1;			

	while(total<len) {
		FD_ZERO(&fds);
		FD_SET(fd, &fds);
		if(select(fd+1, &fds, NULL, NULL, &tv)<=0) goto _bad; // interrupt 무시

		n=read(fd, sResData+total, len-total);		///Server RESPONSE
		if (n<0) {
			if(errno!=EINTR && errno!=EAGAIN) goto _bad;
            else continue;
		}
		if(n==0) {
			if(!ncl) goto _bad;
			else break;
		}
		total+=n;
		sResData[total]=0;

		if(!header) {
			char *p;
			ptr=strstr(sResData, "\r\n\r\n");
			if(!ptr) {
				if(total>=len) goto _bad; // bad header length
				else continue;
			}
			header=1;
			total=total-(ptr-sResData)-4;

			p=strstr(sResData, "Content-Length:");
			if(!p) p=strstr(sResData, "Content-length:");
			if(p) {
				len=atoi(p+16);
				if(len<=0 || len>sizeof(sResData)-1) goto _bad; // bad body length
			}
			else {
				ncl=1;
				len=sizeof(sResData)-1;
			}

			memmove(sResData, ptr+4, total+1);
		}
	}
printf("sResData---->: %s\n",sResData);
	strcpy(sRetTmp,sResData);
	close(fd);
    return 0;
_bad:
	if(fd!=-1) close(fd);
    strcpy(sRetTmp,"reply_cd=0271\nreply_msg=Socket 통신 오류");
	return -1;    
}


void escapeString(char *dst, char *src) {
    int i, p;
	int srclen=0;
	
	if(src==NULL){
	 dst[0]=0;
	 return;   
	}
	srclen=strlen(src);

    p=0;
    for(i=0; i<srclen; i++) {
            if(!isalnum(src[i]) && src[i]!='_' && src[i]!='-') {
                    sprintf(dst+p, "%%%02X", (unsigned char)src[i]);
                    p+=3;
            }
            else {
                    dst[p++]=src[i];
            }
    }
    dst[p]=0;
}

char* _ltrim(char *szInput) {
    int nSize;
    if(!szInput) return szInput;

    nSize=strspn(szInput, " \f\n\r\t\v");
    if(nSize) memmove(szInput, szInput+nSize, strlen(szInput)-nSize+1);
            
    return szInput;
}

char* _rtrim(char *szInput) {
    int i;
    if(!szInput) return szInput;

    for(i=strlen(szInput)-1; i>=0; i--) {
        if(!isspace(szInput[i])) break;
    }
    szInput[i+1]=0;
    return szInput;
}

char* Allat_trim(char *szInput) {
    return _rtrim(_ltrim(szInput));
}

void initEnc(ALLAT_ENCDATA atEnc){
    memset(atEnc, 0, ALLAT_ENCDATA_LEN+1);
    strcpy(atEnc,"00000010");    
}

int validEnc(ALLAT_ENCDATA atEnc){
    int i, nvl, cx=0;

    if(!atEnc) return 0;
    nvl=strlen(atEnc);

    if(nvl==0) return 1;
    if( nvl== 8 && (strcmp(atEnc,"00000010")==0 )) return 1;
    if(nvl>ALLAT_ENCDATA_LEN || atEnc[nvl-1]!=24) return 0;

    for(i=0; i<nvl; i++) {
        if(atEnc[i]==3) {
            if(cx) return 0;
            cx=!cx;
        }else if(atEnc[i]==24) {
            if(!cx) return 0;
            cx=!cx;
        }
    }
    return 1;    
}

/******************************
   return
   -3: 잘못된 VL (InitVL 필요)
   -2: 파라미터 오류
   -1: 실패
    0: 성공
    1: 중복(over write)
*******************************/
int setValue(ALLAT_ENCDATA atEnc, const char *sKey, const char *sValue){
    int nFind=0;
    int nPos=0;
    int novalue=0;

    int nvl, nKey, nValue;

    char *p, *pp;

    if(!validEnc(atEnc)) return -3;
    if(!sKey || !sValue) return -2;
    if( strchr(sKey, 3) || strchr(sKey, 24) || strchr(sValue, 3) || strchr(sValue, 24) ) return -2;

    nvl=strlen(atEnc);
    nKey=strlen(sKey);
    nValue=strlen(sValue);

    pp=atEnc;
    for(;;) {
        p=strstr(pp, sKey);
        if(!p) break;

        if(p[nKey]==3) {
            if(p==atEnc || p[-1]==24) {
                nFind=1;
                nPos=p-atEnc;
                pp=p+nKey+1;
                p=strchr(pp, 24);
                novalue=p-pp;
                break;
            }
        }
        pp=p+nKey;
    }

    if(nFind) {
        if(ALLAT_ENCDATA_LEN-nvl < nValue-novalue) return -1;
        memmove(atEnc+nPos, p+1, strlen(p+1)+1);
    }else {
        if(ALLAT_ENCDATA_LEN-nvl < nKey+nValue+2) return -1;
    }

    strcat(atEnc, sKey);
    strcat(atEnc, "\003");
    strcat(atEnc, sValue);
    strcat(atEnc, "\030");

    return nFind;
}


