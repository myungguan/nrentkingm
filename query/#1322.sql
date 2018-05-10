-- card_fix.mem_idx 컬럼 삭제
ALTER TABLE `card_fix`
	DROP COLUMN `mem_idx`,
	DROP INDEX `mem_idx`;