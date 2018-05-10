#define _GNU_SOURCE
#include <stdio.h>
#include <stdlib.h>

#include <stdio.h>
#include <string.h>
#include "AllatUtil.h" /* 올앳관련 함수 Include */

int main(int argc, char *argv[]){
    

    ALLAT_ENCDATA atEnc;
    char sMsg               [8191+1];
    char at_data            [8191+1];
	char at_cross_key       [ 255+1];

    // 결과 값 정의 
    //------------------------------------------------------------------------    
    char sReplyCd           [  4+1];
    char sReplyMsg          [400+1];
    char sCancelYMDHMS      [ 14+1];
    char sPartCancelFlag    [  1+1];
    char sRemainAmt         [ 10+1];
    char sPayType           [ 10+1];
    
    // 취소 요청 정보
    //------------------------------------------------------------------------    
	char szShopId           [ 40+1];
	char szAmt              [ 20+1];
	char szOrderNo          [160+1];
	char szPayType          [ 12+1];
	char szSeqNo            [ 20+1];    

    // 초기화
    //------------------------------------------------------------------------        
    memset(sMsg,            0,  sizeof(sMsg)            );
    memset(at_data,         0,  sizeof(at_data)         );
    memset(at_cross_key,    0,  sizeof(at_cross_key)    );

    memset(sReplyCd,        0,  sizeof(sReplyCd)        );
    memset(sReplyMsg,       0,  sizeof(sReplyMsg)       );    
    memset(sCancelYMDHMS,   0,  sizeof(sCancelYMDHMS)   );    
    memset(sPartCancelFlag, 0,  sizeof(sPartCancelFlag) );    
    memset(sRemainAmt,      0,  sizeof(sRemainAmt)      );    
    memset(sPayType,        0,  sizeof(sPayType)        );
    
    memset(szShopId,        0,  sizeof(szShopId)        );
    memset(szAmt,           0,  sizeof(szAmt)           );    
    memset(szOrderNo,       0,  sizeof(szOrderNo)       );    
    memset(szPayType,       0,  sizeof(szPayType)       );    
    memset(szSeqNo,         0,  sizeof(szSeqNo)         );    
    
    // 정보 입력
    //------------------------------------------------------------------------        
    strcpy(at_cross_key,"Cross Key");     //해당 CrossKey값
    escapeString(szShopId    ,"ShopId"   );     //ShopId 값               (최대  20 자리)
    escapeString(szAmt       ,"100"      );     //취소 금액               (최대  10 자리)
    escapeString(szOrderNo   ,"test00001");     //주문번호                (최대  80 자리)
    escapeString(szPayType   ,"CARD");          //원거래건의 결제방식[카드:CARD]
    escapeString(szSeqNo     ,"100000002");     //거래일련번호:옵션필드    (최대  10자리)

    initEnc(atEnc);
    setValue(atEnc, "allat_shop_id" ,  szShopId );    
    setValue(atEnc, "allat_order_no",  szOrderNo);
    setValue(atEnc, "allat_amt"     ,  szAmt    );
    setValue(atEnc, "allat_pay_type",  szPayType);
    setValue(atEnc, "allat_test_yn" ,  "N"      );    //테스트 :Y, 서비스 :N
    setValue(atEnc, "allat_opt_pin" ,  "NOUSE"  );    //수정금지(올앳 참조 필드)
    setValue(atEnc, "allat_opt_mod" ,  "APP"    );    //수정금지(올앳 참조 필드)
    setValue(atEnc, "allat_seq_no"  ,  szSeqNo  );    //옵션 필드( 삭제 가능함 )
    
    sprintf(at_data,"allat_shop_id=%s"
                    "&allat_enc_data=%s"
                    "&allat_amt=%s"
                    "&allat_cross_key=%s",szShopId,atEnc,szAmt,at_cross_key); 
                    
    if( CancelReq(at_data,"SSL",sMsg)!=0 ){
        /// 에러처리 
    }

    FILE *rfp;
    char *line = NULL;
    size_t len = 0;
    ssize_t read;


	// 결과 값 가져오기
    //--------------------------------------------------------------------------
    getValue("reply_cd=",           sMsg,sReplyCd,sizeof(sReplyCd));Allat_trim(sReplyCd);
    getValue("reply_msg=",          sMsg,sReplyMsg,sizeof(sReplyMsg));Allat_trim(sReplyMsg);
    
    if( strcmp(sReplyCd,"0000")==0){
        getValue("cancel_ymdhms=",sMsg,sCancelYMDHMS,sizeof(sCancelYMDHMS) );Allat_trim(sCancelYMDHMS);
        getValue("part_cancel_flag=",sMsg,sPartCancelFlag,sizeof(sPartCancelFlag) );Allat_trim(sPartCancelFlag);
        getValue("remain_amt=",sMsg,sRemainAmt,sizeof(sRemainAmt) );Allat_trim(sRemainAmt);
        getValue("pay_type=",sMsg,sPayType,sizeof(sPayType) );Allat_trim(sPayType);
        printf("결과코드        : %s\n",sReplyCd);
        printf("결과메세지      : %s\n",sReplyMsg);
        printf("취소일시        : %s\n",sCancelYMDHMS);
        printf("취소구분        : %s\n",sPartCancelFlag);
        printf("잔액            : %s\n",sRemainAmt);
        printf("거래방식구분    : %s\n",sPayType);
    }else{
        // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
        // reply_msg 는 실패에 대한 메세지        
        printf("결과코드   : %s\n",sReplyCd);
        printf("결과메세지 : %s\n",sReplyMsg);     
        
    }                    
    return 0;
}

