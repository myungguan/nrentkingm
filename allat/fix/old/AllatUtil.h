#ifndef _ALLATUTIL_H_
#define _ALLATUTIL_H_

#define UTIL_LANG      "C"
#define UTIL_VER       "1.0.0.6"
#define APPROVAL_URI   "/servlet/AllatPay/pay/approval.jsp"
#define SANCTION_URI   "/servlet/AllatPay/pay/sanction.jsp"
#define CANCEL_URI     "/servlet/AllatPay/pay/cancel.jsp"
#define CASHREG_URI    "/servlet/AllatPay/pay/cash_registry.jsp"
#define CASHAPP_URI    "/servlet/AllatPay/pay/cash_approval.jsp"
#define CASHCAN_URI    "/servlet/AllatPay/pay/cash_cancel.jsp"
#define ESCROWCHK_URI  "/servlet/AllatPay/pay/escrow_check.jsp"
#define ALLAT_ADDR     "tx.allatpay.com"
#define ALLAT_HOST     "tx.allatpay.com"
#define ALLAT_ENCDATA_LEN 16383

typedef char ALLAT_ENCDATA[ALLAT_ENCDATA_LEN+1];

int _check_enc(const char *sMsg);
int ApprovalReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt);
int SanctionReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt);
int CancelReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt);
int CashRegReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt);
int CashAppReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt);
int CashCanReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt);
int EscrowChkReq(const char *sAt_data, const char *sSsl_flag, char *sRetTxt);
int SendRepo(const char *sAt_data, const char *sAt_addr, const char *sAt_uri, const char *sAt_host , int iAt_Port, char *sRetTmp);   
int SendReq(const char *sAt_data, const char *aAt_addr, const char *sAt_uri, const char *sAt_host, int iAt_Port, char *sRetTmp);
int SendReqSSL(const char *sAt_data, const char *aAt_addr, const char *sAt_uri, const char *sAt_host, int iAt_Port, char *sRetTmp);
void escapeString(char *dst, char *src);
char* _ltrim(char *szInput);
char* _rtrim(char *szInput); 
char* Allat_trim(char *szInput);
void initEnc(ALLAT_ENCDATA atEnc);
int validEnc(ALLAT_ENCDATA atEnc);
int setValue(ALLAT_ENCDATA atEnc, const char *sKey, const char *sValue);
void getValue(const char* sKey, const char* sMsg, char *sRet, int nLen);

#endif
