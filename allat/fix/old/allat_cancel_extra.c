#define _GNU_SOURCE
#include <stdio.h>
#include <stdlib.h>

#include <stdio.h>
#include <string.h>
#include "AllatUtil.h" /* �þܰ��� �Լ� Include */

int main(int argc, char *argv[]){
    

    ALLAT_ENCDATA atEnc;
    char sMsg               [8191+1];
    char at_data            [8191+1];
	char at_cross_key       [ 255+1];

    // ��� �� ���� 
    //------------------------------------------------------------------------    
    char sReplyCd           [  4+1];
    char sReplyMsg          [400+1];
    char sCancelYMDHMS      [ 14+1];
    char sPartCancelFlag    [  1+1];
    char sRemainAmt         [ 10+1];
    char sPayType           [ 10+1];
    
    // ��� ��û ����
    //------------------------------------------------------------------------    
	char szShopId           [ 40+1];
	char szAmt              [ 20+1];
	char szOrderNo          [160+1];
	char szPayType          [ 12+1];
	char szSeqNo            [ 20+1];    

    // �ʱ�ȭ
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
    
    // ���� �Է�
    //------------------------------------------------------------------------        
    strcpy(at_cross_key,"Cross Key");     //�ش� CrossKey��
    escapeString(szShopId    ,"ShopId"   );     //ShopId ��               (�ִ�  20 �ڸ�)
    escapeString(szAmt       ,"100"      );     //��� �ݾ�               (�ִ�  10 �ڸ�)
    escapeString(szOrderNo   ,"test00001");     //�ֹ���ȣ                (�ִ�  80 �ڸ�)
    escapeString(szPayType   ,"CARD");          //���ŷ����� �������[ī��:CARD]
    escapeString(szSeqNo     ,"100000002");     //�ŷ��Ϸù�ȣ:�ɼ��ʵ�    (�ִ�  10�ڸ�)

    initEnc(atEnc);
    setValue(atEnc, "allat_shop_id" ,  szShopId );    
    setValue(atEnc, "allat_order_no",  szOrderNo);
    setValue(atEnc, "allat_amt"     ,  szAmt    );
    setValue(atEnc, "allat_pay_type",  szPayType);
    setValue(atEnc, "allat_test_yn" ,  "N"      );    //�׽�Ʈ :Y, ���� :N
    setValue(atEnc, "allat_opt_pin" ,  "NOUSE"  );    //��������(�þ� ���� �ʵ�)
    setValue(atEnc, "allat_opt_mod" ,  "APP"    );    //��������(�þ� ���� �ʵ�)
    setValue(atEnc, "allat_seq_no"  ,  szSeqNo  );    //�ɼ� �ʵ�( ���� ������ )
    
    sprintf(at_data,"allat_shop_id=%s"
                    "&allat_enc_data=%s"
                    "&allat_amt=%s"
                    "&allat_cross_key=%s",szShopId,atEnc,szAmt,at_cross_key); 
                    
    if( CancelReq(at_data,"SSL",sMsg)!=0 ){
        /// ����ó�� 
    }

    FILE *rfp;
    char *line = NULL;
    size_t len = 0;
    ssize_t read;


	// ��� �� ��������
    //--------------------------------------------------------------------------
    getValue("reply_cd=",           sMsg,sReplyCd,sizeof(sReplyCd));Allat_trim(sReplyCd);
    getValue("reply_msg=",          sMsg,sReplyMsg,sizeof(sReplyMsg));Allat_trim(sReplyMsg);
    
    if( strcmp(sReplyCd,"0000")==0){
        getValue("cancel_ymdhms=",sMsg,sCancelYMDHMS,sizeof(sCancelYMDHMS) );Allat_trim(sCancelYMDHMS);
        getValue("part_cancel_flag=",sMsg,sPartCancelFlag,sizeof(sPartCancelFlag) );Allat_trim(sPartCancelFlag);
        getValue("remain_amt=",sMsg,sRemainAmt,sizeof(sRemainAmt) );Allat_trim(sRemainAmt);
        getValue("pay_type=",sMsg,sPayType,sizeof(sPayType) );Allat_trim(sPayType);
        printf("����ڵ�        : %s\n",sReplyCd);
        printf("����޼���      : %s\n",sReplyMsg);
        printf("����Ͻ�        : %s\n",sCancelYMDHMS);
        printf("��ұ���        : %s\n",sPartCancelFlag);
        printf("�ܾ�            : %s\n",sRemainAmt);
        printf("�ŷ���ı���    : %s\n",sPayType);
    }else{
        // reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
        // reply_msg �� ���п� ���� �޼���        
        printf("����ڵ�   : %s\n",sReplyCd);
        printf("����޼��� : %s\n",sReplyMsg);     
        
    }                    
    return 0;
}

