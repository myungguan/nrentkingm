-- 오픈플렛폼 테스트를 위한 계정 생성 (비밀번호 : 오픈플렛폼!)
INSERT INTO `member` (`name`, `id`, `passwd`, `birth`, `email`, `phone`, `cp`, `zipcode`, `addr1`, `pushser`, `businessnum`, `businessnum_sub`, `paperimg`, `daename`, `businessk1`, `businessk2`, `signdate`, `mempoints`, `memgrade`, `canconnect`, `rmemo`, `social_type`, `social_code`, `up_idx`, `up_ok`, `remember_token`) VALUES ('오픈플렛폼', 'openplatform@rentking.co.kr', '*03A0F3CD31AFD43624A586005FC2B5AB42511CA6', '1982-07-02', 'openplatform@rentking.co.kr', ' ', '01011111111', ' ', ' ', 'Y', ' ', ' ', ' ', ' ', ' ', ' ', '2017-09-25 16:35:09', '0', '100', 'Y', ' ', ' ', ' ', '0', ' ', ' ');

-- 테스트 계정에 admin 권한 설정
INSERT INTO `auth_admin` (`member_idx`, `grade`) VALUES ((SELECT idx FROM member WHERE id='openplatform@rentking.co.kr'), '8');

#################################### 여기까지 배포됨
