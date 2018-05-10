#include <stdio.h>
#include <string.h>
#include "AllatUtil.h" /* �þܰ��� �Լ� Include */

int main(int argc, char *argv[]){

    ALLAT_ENCDATA atEnc;
    char sMsg        [8191+1];
    char at_data     [8191+1];
    char at_cross_key[ 255+1];
    
    // ��� �� ���� 
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

    // ���� ��û ���� 
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

    
    // �ʱ�ȭ
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


    // �ʼ� �׸� 
    strcpy(at_cross_key  ,"CrossKey");                //CrossKey��(�ִ�200��)
    escapeString(szFixKey      ,"1234567890123456");  //ī��Ű(�ִ� 24��)
    escapeString(szSellMm      ,"00");                //�Һΰ�����(�ִ�  2��)
    escapeString(szAmt         ,"1000");              //�ݾ�(�ִ� 10��)
    escapeString(szBusinessType,"0");                 //������ ī������(�ִ� 1��)             : ����(0),����(1) 
    escapeString(szRegistryNo  ,"1234567890123");     //�ֹι�ȣ(�ִ� 13�ڸ�)                 : szBusinessType=0 �ϰ�� 
    escapeString(szBizNo       ,"");                  //����ڹ�ȣ(�ִ� 20�ڸ�)               : szBusinessType=1 �ϰ�� 
    escapeString(szShopId      ,"ShopId");            //����ID(�ִ� 20��)
    escapeString(szShopMemberId,"guest");             //ȸ��ID(�ִ� 20��)                     : ���θ�ȸ��ID    
    escapeString(szOrderNo     ,"test0001");          //�ֹ���ȣ(�ִ� 80��)                   : ���θ� ���� �ֹ���ȣ 
    escapeString(szProductCd   ,"AllatSampleProduct");//��ǰ�ڵ�(�ִ� 1000��) : ���� ��ǰ�� ��� ������ �̿�, ������('||':������ 2��)
    escapeString(szProductNm   ,"�þܻ��û�ǰ");      //��ǰ��(�ִ� 1000��)   : ���� ��ǰ�� ��� ������ �̿�, ������('||':������ 2��)
    escapeString(szCardCertYn  ,"N");                 //ī����������(�ִ� 1��)                : ����(Y),����������(N),���������(X)
    escapeString(szZerofeeYn   ,"N");                 //�Ϲ�/������ �Һ� ��� ����(�ִ� 1��)  : �Ϲ�(N), ������ �Һ�(Y) 
    escapeString(szBuyerNm     ,"�ڿþ�");            //�����ڼ���(�ִ� 20��)
    escapeString(szRecpNm      ,"���þ�");            //�����μ���(�ִ� 20��)
    escapeString(szRecpAddr    ,"����� ������ ���ﵿ");//�������ּ�(�ִ� 120��)        
    escapeString(szBuyerIp     ,"192.168.0.1");       //������ IP(�ִ�15��) - BuyerIp�� ������ ���ٸ� "Unknown"���� ����
    escapeString(szEmailAddr   ,"");                  //������ �̸��� �ּ�(50��)
    escapeString(szBonusYn     ,"N");                 //���ʽ�����Ʈ ��뿩��(�ִ�1��)        : ���(Y), ������(N)
    escapeString(szGender      ,"M");                 //������ ����(�ִ� 1��)                 : ����(M)/����(F)
    escapeString(szBirthYmd    ,"19791219");          //�������� �������(�ִ� 8��)           : YYYYMMDD����

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
    setValue(atEnc,"allat_pay_type"         ,   "FIX"           );  //��������(������� ����)
    setValue(atEnc,"allat_test_yn"          ,   "N"             );  //�׽�Ʈ :Y, ���� :N
    setValue(atEnc,"allat_opt_pin"          ,   "NOUSE"         );  //��������(�þ� ���� �ʵ�)
    setValue(atEnc,"allat_opt_mod"          ,   "APP"           );  //��������(�þ� ���� �ʵ�)
    sprintf(at_data,"allat_shop_id=%s"
                    "&allat_enc_data=%s"
                    "&allat_amt=%s"
                    "&allat_cross_key=%s",szShopId,atEnc,szAmt,at_cross_key);     

    if( ApprovalReq(at_data,"SSL",sMsg)!=0 ){
        /// ����ó��
    }


    // ��� �� ��������
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

        /**************  ��� �� ��� ******************/
        printf("����ڵ�        : %s\n",sReplyCd);
        printf("����޼���      : %s\n",sReplyMsg);
        printf("�ֹ���ȣ        : %s\n",sOrderNo);
        printf("���αݾ�        : %s\n",sAmt);
        printf("���Ҽ���        : %s\n",sPayType);
        printf("�����Ͻ�        : %s\n",sApprovalYMDHMS);
        printf("�ŷ��Ϸù�ȣ    : %s\n",sSeqNo);
        printf("========= �ſ� ī�� =============\n");
        printf("���ι�ȣ        : %s\n",sApprovalNo);
        printf("ī��ID          : %s\n",sCardId);
        printf("ī���          : %s\n",sCardNm);
        printf("�Һΰ���        : %s\n",sSellMm);
        printf("�����ڿ���      : %s\n",sZerofeeYn);
        printf("��������        : %s\n",sCertYn);
        printf("�����Ϳ���      : %s\n",sContractYn);
    }else{
        // reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
        // reply_msg �� ���п� ���� �޼���        
        printf("����ڵ�   : %s\n",sReplyCd);
        printf("����޼��� : %s\n",sReplyMsg);     
        
    }                    
    return 0;
}

