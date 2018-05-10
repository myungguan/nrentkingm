UPDATE member_license SET nums = CONCAT(codes, nums);


ALTER TABLE `member_license`
	DROP COLUMN `codes`;

UPDATE marketdb SET numsub = CONCAT(codesub, numsub);


ALTER TABLE `marketdb`
	DROP COLUMN `codesub`;