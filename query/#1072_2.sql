-- 가맹점 은행명 통일 정리
UPDATE rentshop SET bank = '농협' WHERE bank LIKE '농협%';
UPDATE rentshop SET bank = 'SC제일은행' WHERE bank = '한국스탠다드차타드은행';
UPDATE rentshop SET bank = '신협' WHERE bank = '신용협동조합';

-- 가맹점 은행명을 은행코드로 변경
UPDATE rentshop
SET bank =
	if(bank = 'SC제일은행', '023',
	if(bank = '경남은행', '039',
	if(bank = '광주은행', '034',
	if(bank = '국민은행', '004',
	if(bank = '기업은행', '003',
	if(bank = '농협', '011',
	if(bank = '대구은행', '031',
	if(bank = '부산은행', '032',
	if(bank = '새마을금고', '045',
	if(bank = '신한은행', '088',
	if(bank = '신협', '048',
	if(bank = '외환은행', '005',
	if(bank = '우리은행', '020',
	if(bank = '우체국', '071',
	if(bank = '전북은행', '037',
	if(bank = '제주은행', '035',
	if(bank = '중소기업은행', '003',
	if(bank = '하나은행', '081', bank))))))))))))))))));

-- 은행계좌 인증정보 컬럼 추가
ALTER TABLE `rentshop`
	ADD COLUMN `bankholder` VARCHAR(20) NOT NULL COMMENT '은행계좌의 인증정보(사업자등록번호 or 생년월일+식별자)' AFTER `bankname`;