ALTER TABLE `site_marketdb`
	CHANGE COLUMN `zipcode` `zipcode` CHAR(5) NOT NULL COMMENT '(대여 우편번호 / addr만 사용)' AFTER `usepoint`,
	CHANGE COLUMN `addr1` `addr1` VARCHAR(200) NOT NULL COMMENT '대여 주소(addr 로 변경예정)' AFTER `zipcode`,
	CHANGE COLUMN `addr2` `addr2` VARCHAR(200) NOT NULL COMMENT '(대여 상세주소 / addr만 사용)' AFTER `addr1`,
	CHANGE COLUMN `rzipcode` `rzipcode` CHAR(5) NOT NULL COMMENT '(반납 우편번호 / raddr만 사용)' AFTER `addr2`,
	CHANGE COLUMN `raddr1` `raddr1` VARCHAR(200) NOT NULL COMMENT '반납 주소(raddr 로 변경예정)' AFTER `rzipcode`,
	CHANGE COLUMN `raddr2` `raddr2` VARCHAR(200) NOT NULL COMMENT '(반납 상세주소 / raddr만 사용)' AFTER `raddr1`,
	CHANGE COLUMN `mem_name` `mem_name` VARCHAR(50) NOT NULL COMMENT '(예약자 이름(site_member.name))' AFTER `cpsub`,
	CHANGE COLUMN `mem_id` `mem_id` VARCHAR(50) NOT NULL COMMENT '(예약자 id(site_member.id))' AFTER `mem_name`,
	CHANGE COLUMN `cp` `cp` VARCHAR(50) NOT NULL COMMENT '(예약자 휴대폰번호(site_member.cp))' AFTER `mem_id`,
	CHANGE COLUMN `model_idx` `model_idx` INT(10) UNSIGNED NOT NULL COMMENT '(site_vehicle_std.index_no)' AFTER `car_idx`,
	CHANGE COLUMN `ptype` `ptype` CHAR(1) NOT NULL COMMENT '대여방법(1: 배달대여 / 2: 지점방문)' AFTER `ddata2`,
	CHANGE COLUMN `sdate` `sdate` DATETIME NOT NULL COMMENT '대여일' AFTER `rtype`,
	CHANGE COLUMN `account1` `account1` INT(10) UNSIGNED NOT NULL COMMENT '장기대여시 요금(월)' AFTER `account`,
	CHANGE COLUMN `account2` `account2` INT(10) UNSIGNED NOT NULL COMMENT '장기대여시 요금(일)' AFTER `account1`;

ALTER TABLE `site_marketdb_tmp`
	CHANGE COLUMN `zipcode` `zipcode` CHAR(5) NOT NULL COMMENT '(대여 우편번호 / addr만 사용)',
	CHANGE COLUMN `addr1` `addr1` VARCHAR(200) NOT NULL COMMENT '대여 주소(addr 로 변경예정)' AFTER `zipcode`,
	CHANGE COLUMN `addr2` `addr2` VARCHAR(200) NOT NULL COMMENT '(대여 상세주소 / addr만 사용)' AFTER `addr1`,
	CHANGE COLUMN `rzipcode` `rzipcode` CHAR(5) NOT NULL COMMENT '(반납 우편번호 / raddr만 사용)' AFTER `addr2`,
	CHANGE COLUMN `raddr1` `raddr1` VARCHAR(200) NOT NULL COMMENT '반납 주소(raddr 로 변경예정)' AFTER `rzipcode`,
	CHANGE COLUMN `raddr2` `raddr2` VARCHAR(200) NOT NULL COMMENT '(반납 세부주소 / raddr만 사용)' AFTER `raddr1`,
	CHANGE COLUMN `ptype` `ptype` CHAR(1) NOT NULL COMMENT '대여방법(지점방문, 배달대여)' AFTER `ddata2`,
	CHANGE COLUMN `dan` `dan` CHAR(1) NOT NULL COMMENT '(진행상태(1: 예약확정 / 2: 대여중 / 3: 반납완료 / 4: 취소요청 / 5: 취소완료))' AFTER `makedate`,
	CHANGE COLUMN `buymethod` `buymethod` CHAR(1) NOT NULL COMMENT '(결제방식(C: 신용카드))' AFTER `totalaccount`,
	CHANGE COLUMN `usepoint` `usepoint` INT(10) UNSIGNED NOT NULL COMMENT '(포인트 결제)' AFTER `buymethod`,
	CHANGE COLUMN `model_idx` `model_idx` INT(10) UNSIGNED NOT NULL COMMENT '(site_vehicle_std.index_no)' AFTER `car_idx`,
	CHANGE COLUMN `account1` `account1` INT(10) UNSIGNED NOT NULL COMMENT '장기대여시 요금(월)' AFTER `account`,
	CHANGE COLUMN `account2` `account2` INT(10) UNSIGNED NOT NULL COMMENT '장기대여시 요금(일)' AFTER `account1`;

ALTER TABLE `site_board`
	CHANGE COLUMN `mem_idx` `mem_idx` INT(10) UNSIGNED NOT NULL COMMENT 'site_member.index_no' AFTER `issecret`,
	CHANGE COLUMN `sucount` `sucount` INT(10) UNSIGNED NOT NULL COMMENT '(사용안함)' AFTER `tailupdate`,
	CHANGE COLUMN `isview` `isview` CHAR(1) NOT NULL COMMENT '(사용안함)' AFTER `mdate`,
	CHANGE COLUMN `inpoint` `inpoint` INT(10) UNSIGNED NOT NULL COMMENT '(사용안함)' AFTER `isview`,
	CHANGE COLUMN `jackpoint` `jackpoint` INT(10) UNSIGNED NOT NULL COMMENT '(사용안함)' AFTER `isjack`,
	CHANGE COLUMN `nip` `nip` VARCHAR(20) NOT NULL COMMENT '작성자 아이피' AFTER `jackpoint`;

ALTER TABLE `site_board_conf`
	CHANGE COLUMN `read_priv` `read_priv` TINYINT(10) UNSIGNED NOT NULL DEFAULT '99' COMMENT '(읽기 권한)' AFTER `write_priv_sub`;

ALTER TABLE `site_coupen`
	CHANGE COLUMN `isuse` `isuse` CHAR(1) NOT NULL COMMENT '발급가능여부(Y/N)' AFTER `enddates`,
	CHANGE COLUMN `prod1` `prod1` CHAR(1) NOT NULL COMMENT '(사용안함)' AFTER `usesites`;