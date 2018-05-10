#include <stdio.h>
#include <string.h>
#include "AllatUtil.h" /* 올앳관련 함수 Include */

int main(int argc, char *argv[]){

    ALLAT_ENCDATA atEnc;
    char sMsg        [8191+1];
    char at_data     [8191+1];
    char at_cross_key[ 255+1];
    
    // 결과 값 정의 
    //------------------------------------------------------------------------
    char sReplyCd           [  4+1];
    char sReplyMsg          [400+1];
    char sPayType           [ 10+1];    
    char sApprovalNo        [ 10+1];    
    char sOrderNo           [ 80+1];
    char sAmt               [ 10+1];
    char sApprovalYMDHMS    [ 14+1];
    char sSeqNo             [ 10+1];
    char sCardId            [  2+1];
    char sCardNm            [ 48+1];
    char sSellMm            [  2+1];
    char sZerofeeYn         [  1+1];
    char sCertYn            [  1+1];
    char sContractYn        [  1+1];

    // 결제 요청 정보 
    //------------------------------------------------------------------------
    char szFixKey           [ 24+1];
    char szSellMm           [  4+1];
    char szAmt              [ 20+1];
    char szBusinessType     [  2+1];
    char szRegistryNo       [ 14+1];
    char szBizNo            [ 40+1];
    char szShopId           [ 40+1];
    char szShopMemberId     [ 40+1];
    char szOrderNo          [160+1];
    char szProductCd        [1000+1];
    char szProductNm        [1000+1];
    char szCardCertYn       [  2+1];
    char szZerofeeYn        [  2+1];
    char szBuyerNm          [ 40+1];
    char szRecpNm           [ 40+1];
    char szRecpAddr         [240+1];
    char szBuyerIp          [ 30+1];
    char szEmailAddr        [511+1]; 
    char szBonusYn          [  2+1];
    char szGender           [  1+1];
    char szBirthYmd         [  8+1];

    
    // 초기화
    //------------------------------------------------------------------------        
    memset(sMsg           ,0,sizeof(sMsg           ));
    memset(at_data        ,0,sizeof(at_data        ));
    memset(at_cross_key   ,0,sizeof(at_cross_key   ));

    memset(sReplyCd       ,0,sizeof(sReplyCd       ));  
    memset(sReplyMsg      ,0,sizeof(sReplyMsg      ));  
    memset(sPayType       ,0,sizeof(sPayType       ));   
    memset(sApprovalNo    ,0,sizeof(sApprovalNo    ));   
    memset(sOrderNo       ,0,sizeof(sOrderNo       ));  
    memset(sAmt           ,0,sizeof(sAmt           ));  
    memset(sApprovalYMDHMS,0,sizeof(sApprovalYMDHMS));  
    memset(sSeqNo         ,0,sizeof(sSeqNo         ));  
    memset(sCardId        ,0,sizeof(sCardId        ));  
    memset(sCardNm        ,0,sizeof(sCardNm        ));  
    memset(sSellMm        ,0,sizeof(sSellMm        ));  
    memset(sZerofeeYn     ,0,sizeof(sZerofeeYn     ));  
    memset(sCertYn        ,0,sizeof(sCertYn        ));  
    memset(sContractYn    ,0,sizeof(sContractYn    ));  
                          
    memset(szFixKey       ,0,sizeof(szFixKey       ));
    memset(szSellMm       ,0,sizeof(szSellMm       ));
    memset(szAmt          ,0,sizeof(szAmt          ));
    memset(szBusinessType ,0,sizeof(szBusinessType ));
    memset(szRegistryNo   ,0,sizeof(szRegistryNo   ));
    memset(szBizNo        ,0,sizeof(szBizNo        ));
    memset(szShopId       ,0,sizeof(szShopId       ));
    memset(szShopMemberId ,0,sizeof(szShopMemberId ));
    memset(szOrderNo      ,0,sizeof(szOrderNo      ));
    memset(szProductCd    ,0,sizeof(szProductCd    ));
    memset(szProductNm    ,0,sizeof(szProductNm    ));
    memset(szCardCertYn   ,0,sizeof(szCardCertYn   ));
    memset(szZerofeeYn    ,0,sizeof(szZerofeeYn    ));
    memset(szBuyerNm      ,0,sizeof(szBuyerNm      ));
    memset(szRecpNm       ,0,sizeof(szRecpNm       ));
    memset(szRecpAddr     ,0,sizeof(szRecpAddr     ));
    memset(szBuyerIp      ,0,sizeof(szBuyerIp      ));
    memset(szEmailAddr    ,0,sizeof(szEmailAddr    )); 
    memset(szBonusYn      ,0,sizeof(szBonusYn      ));
    memset(szGender       ,0,sizeof(szGender       ));
    memset(szBirthYmd     ,0,sizeof(szBirthYmd     ));


    // 필수 항목 
    strcpy(at_cross_key  ,"CrossKey");                //CrossKey값(최대200자)
    escapeString(szFixKey      ,"1234567890123456");  //카드키(최대 24자)
    escapeString(szSellMm      ,"00");                //할부개월값(최대  2자)
    escapeString(szAmt         ,"1000");              //금액(최대 10자)
    escapeString(szBusinessType,"0");                 //결제자 카드종류(최대 1자)             : 개인(0),법인(1) 
    escapeString(szRegistryNo  ,"1234567890123");     //주민번호(최대 13자리)                 : szBusinessType=0 일경우 
    escapeString(szBizNo       ,"");                  //사업자번호(최대 20자리)               : szBusinessType=1 일경우 
    escapeString(szShopId      ,"ShopId");            //상점ID(최대 20자)
    escapeString(szShopMemberId,"guest");             //회원ID(최대 20자)                     : 쇼핑몰회원ID    
    escapeString(szOrderNo     ,"test0001");          //주문번호(최대 80자)                   : 쇼핑몰 고유 주문번호 
    escapeString(szProductCd   ,"AllatSampleProduct");//상품코드(최대 1000자) : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
    escapeString(szProductNm   ,"올앳샘플상품");      //상품명(최대 1000자)   : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
    escapeString(szCardCertYn  ,"N");                 //카드인증여부(최대 1자)                : 인증(Y),인증사용않음(N),인증만사용(X)
    escapeString(szZerofeeYn   ,"N");                 //일반/무이자 할부 사용 여부(최대 1자)  : 일반(N), 무이자 할부(Y) 
    escapeString(szBuyerNm     ,"박올앳");            //결제자성명(최대 20자)
    escapeString(szRecpNm      ,"조올앳");            //수취인성명(최대 20자)
    escapeString(szRecpAddr    ,"서울시 강남구 역삼동");//수취인주소(최대 120자)        
    escapeString(szBuyerIp     ,"192.168.0.1");       //결제자 IP(최대15자) - BuyerIp를 넣을수 없다면 "Unknown"으로 세팅
    escapeString(szEmailAddr   ,"");                  //결제자 이메일 주소(50자)
    escapeString(szBonusYn     ,"N");                 //보너스포인트 사용여부(최대1자)        : 사용(Y), 사용않음(N)
    escapeString(szGender      ,"M");                 //구매자 성별(최대 1자)                 : 남자(M)/여자(F)
    escapeString(szBirthYmd    ,"19791219");          //구매자의 생년월일(최대 8자)           : YYYYMMDD형식

    initEnc(atEnc);
    setValue(atEnc,"allat_card_key"         ,   szFixKey        );
    setValue(atEnc,"allat_sell_mm"          ,   szSellMm        );          
    setValue(atEnc,"allat_amt"              ,   szAmt           );
    setValue(atEnc,"allat_business_type"    ,   szBusinessType  );
    if(strcmp(szBusinessType,"0")==0){
        setValue(atEnc,"allat_registry_no"  ,   szRegistryNo    );
    }else{
        setValue(atEnc,"allat_biz_no"       ,   szBizNo         );
    }
    setValue(atEnc,"allat_shop_id"          ,   szShopId        );
    setValue(atEnc,"allat_shop_member_id"   ,   szShopMemberId  );
    setValue(atEnc,"allat_order_no"         ,   szOrderNo       );
    setValue(atEnc,"allat_product_cd"       ,   szProductCd     );
    setValue(atEnc,"allat_product_nm"       ,   szProductNm     );
    setValue(atEnc,"allat_cardcert_yn"      ,   szCardCertYn    );
    setValue(atEnc,"allat_zerofee_yn"       ,   szZerofeeYn     );
    setValue(atEnc,"allat_buyer_nm"         ,   szBuyerNm       );
    setValue(atEnc,"allat_recp_name"        ,   szRecpNm        );
    setValue(atEnc,"allat_recp_addr"        ,   szRecpAddr      );
    setValue(atEnc,"allat_user_ip"          ,   szBuyerIp       );
    setValue(atEnc,"kvp_quota"              ,   szSellMm        );
    setValue(atEnc,"allat_email_addr"       ,   szEmailAddr     );          
    setValue(atEnc,"allat_bonus_yn"         ,   szBonusYn       );
    setValue(atEnc,"allat_gender"           ,   szGender        );
    setValue(atEnc,"allat_birth_ymd"        ,   szBirthYmd      );
    setValue(atEnc,"allat_pay_type"         ,   "FIX"           );  //수정금지(결제방식 정의)
    setValue(atEnc,"allat_test_yn"          ,   "N"             );  //테스트 :Y, 서비스 :N
    setValue(atEnc,"allat_opt_pin"          ,   "NOUSE"         );  //수정금지(올앳 참조 필드)
    setValue(atEnc,"allat_opt_mod"          ,   "APP"           );  //수정금지(올앳 참조 필드)
    sprintf(at_data,"allat_shop_id=%s"
                    "&allat_enc_data=%s"
                    "&allat_amt=%s"
                    "&allat_cross_key=%s",szShopId,atEnc,szAmt,at_cross_key);     

    if( ApprovalReq(at_data,"SSL",sMsg)!=0 ){
        /// 에러처리
    }


    // 결과 값 가져오기
    //--------------------------------------------------------------------------
    getValue("reply_cd=",           sMsg,sReplyCd,sizeof(sReplyCd));Allat_trim(sReplyCd);
    getValue("reply_msg=",          sMsg,sReplyMsg,sizeof(sReplyMsg));Allat_trim(sReplyMsg);
    
    if( strcmp(sReplyCd,"0000")==0){
        getValue("order_no="        ,sMsg,sOrderNo,sizeof(sOrderNo) )               ;Allat_trim(sOrderNo);
        getValue("amt="             ,sMsg,sAmt,sizeof(sAmt) )                       ;Allat_trim(sAmt);
        getValue("approval_ymdhms=" ,sMsg,sApprovalYMDHMS,sizeof(sApprovalYMDHMS) ) ;Allat_trim(sApprovalYMDHMS);
        getValue("seq_no="          ,sMsg,sSeqNo, sizeof(sSeqNo) )                  ;Allat_trim(sSeqNo);
        getValue("card_id="         ,sMsg,sCardId, sizeof(sCardId) )                ;Allat_trim(sCardId);
        getValue("card_nm="         ,sMsg,sCardNm, sizeof(sCardNm) )                ;Allat_trim(sCardNm);
        getValue("sell_mm="         ,sMsg,sSellMm, sizeof(sSellMm) )                ;Allat_trim(sSellMm);
        getValue("zerofee_yn="      ,sMsg,sZerofeeYn, sizeof(sZerofeeYn) )          ;Allat_trim(sZerofeeYn);
        getValue("cert_yn="         ,sMsg,sCertYn, sizeof(sCertYn) )                ;Allat_trim(sCertYn);
        getValue("contract_yn="     ,sMsg,sContractYn, sizeof(sContractYn) )        ;Allat_trim(sContractYn);
        getValue("pay_type="        ,sMsg,sPayType,sizeof(sPayType) )               ;Allat_trim(sPayType);    
        getValue("approval_no="     ,sMsg,sApprovalNo,sizeof(sApprovalNo) )         ;Allat_trim(sApprovalNo);

        /**************  결과 값 출력 ******************/
        printf("결과코드        : %s\n",sReplyCd);
        printf("결과메세지      : %s\n",sReplyMsg);
        printf("주문번호        : %s\n",sOrderNo);
        printf("승인금액        : %s\n",sAmt);
        printf("지불수단        : %s\n",sPayType);
        printf("승인일시        : %s\n",sApprovalYMDHMS);
        printf("거래일련번호    : %s\n",sSeqNo);
        printf("========= 신용 카드 =============\n");
        printf("승인번호        : %s\n",sApprovalNo);
        printf("카드ID          : %s\n",sCardId);
        printf("카드명          : %s\n",sCardNm);
        printf("할부개월        : %s\n",sSellMm);
        printf("무이자여부      : %s\n",sZerofeeYn);
        printf("인증여부        : %s\n",sCertYn);
        printf("직가맹여부      : %s\n",sContractYn);
    }else{
        // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
        // reply_msg 는 실패에 대한 메세지        
        printf("결과코드   : %s\n",sReplyCd);
        printf("결과메세지 : %s\n",sReplyMsg);     
        
    }                    
    return 0;
}

