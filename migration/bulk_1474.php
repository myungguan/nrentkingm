<?php
/**
 *
 * Created by Sanggoo.
 * Date: 2017-03-16
 * site_vehicle 필드 변경
 *
 * ex) ac1_1 -> ac1_1_1, ac1_1_2, ac1_1_3, ac1_1_4
 *
 * ~~1          AppWeb
 * ~~2          Intranet
 *
 * ---- ASIS
 * acN_1        주중 요금        int        acN_1_1, acN_1_2, acN_1,3, acN_1_4
 * acN_1per     주중 할인율		float	acN_1per_1, acN_1per_2, acN_1per_3, acN_1per_4
 * acN_2        지점(주말) 요금	int		acN_2_1, acN_2_2, acN_2_3, acN_2_4
 * acN_2per     주말 할인율		float	acN_2per_1, acN_2per_2, acN_2per_3, acN_2per_4
 * delN_ac      배반차료		int		delN_ac_1, delN_ac_2, delN_ac_3, delN_ac_4
 * delN_ac1     배달(주중)		int		delN_ac1_1, delN_ac1_2, delN_ac1_3, delN_ac1_4
 * delN_ac2     배달(주말)		int		delN_ac2_1, delN_ac2_2, delN_ac2_3, delN_ac2_4
 * insuN_1ac    일반자차		int		insuN_1ac_1, insuN_1ac_2, insuN_1ac_3, insuN_1ac_4
 * insuN_1stdac 일반자차 고객부담금	int
 * insuN_2ac    슈퍼자차		int		insuN_2ac_1, insuN_2ac_2, insuN_2ac_3, insuN_2ac_4
 * insuN_2stdac 슈퍼자차 고객부담금	int
 * insuN_3ac	완전자차		int		insuN_3ac_1, insuN_3ac_2, insuN_3ac_3, insuN_3ac_4
 * aclN			월장기			int		aclN_1, aclN_2, aclN_3
 * raclN		보증금			int		raclN_1, raclN_2, raclN_3
 *
 * ---- TOBE
 * table: site_vehicle_price
 * price					int		기본요금				ac1_1_1
 * price_hour				int		시간요금				ac1_1_1 / 12
 * price_holiday			int		주말요금				ac1_2_1
 * price_discount2			float	요금 인하율 (기간2)		ac1_1_2 / ac1_1_1
 * price_discount3			float	요금 인하율 (기간3)		ac1_1_3 / ac1_1_1
 * price_discount4			float	요금 인하율 (기간4) 	ac1_1_4 / ac1_1_1
 * price_del1				int		배반차료1				del1_ac_1
 * price_del2				int		배반차료2				del1_ac_2
 * price_del3				int		배반차료3				del1_ac_3
 * price_del4				int		배반차료4				del1_ac_4
 * price_insu				int		일반자차				insu1_1ac_1
 * price_insu_check			char(1)	일반자차 사용여부		insu1_1
 * price_insu_exem			int		일반자차 고객부담금			insu1_1stdac
 * price_insu_super			int		슈퍼차자				insu1_2ac_1
 * price_insu_super_check	char(1)	슈퍼자차 사용여부		insu1_2
 * price_insu_suepr_exem	int		슈퍼자차 고객부담금			insu1_2stdac
 * price_insu_perfect		int		완전자차				insu1_3ac_1
 * price_insu_perfect_check	char(1)	완전자차 사용여부		insu1_3
 * price_net				float	NET 할인율				0
 *
 * table: site_vehicle_price_longterm
 * price_longterm1			int		장기요금1				acl1_1
 * price_longterm2			int		장기요금2				acl1_2
 * price_longterm3			int		장기요금3				acl1_3
 * price_longterm_deposit1	int		장기보증금1				racl1_1
 * price_longterm_deposit2	int		장기보증금2				racl1_2
 * price_longterm_deposit3	int		장기보증금3				racl1_3
 * price_longterm_net		float	장기 NET 할인율			0
 *
 * license_limit			int		면허제한				0
 *
 *
 *
 *
*/

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";

$vehicle = [
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1770','rundistan'=>44000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1789','rundistan'=>42000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1790','rundistan'=>43000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1791','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1792','rundistan'=>48000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1793','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1794','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1795','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1796','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1797','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-04','carnum'=>'28하1798','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-04','carnum'=>'28하1799','rundistan'=>47000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-04','carnum'=>'28하3801','rundistan'=>47000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>114,'fuel'=>75,'outdate'=>'2016-06','carnum'=>'28하3802','rundistan'=>43000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1731','rundistan'=>48000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1732','rundistan'=>45000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1733','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1734','rundistan'=>48000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1735','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1736','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1737','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1738','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1739','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1740','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1741','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1742','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1743','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1744','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1745','rundistan'=>51000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1746','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1747','rundistan'=>23000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1748','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1749','rundistan'=>57000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1750','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1751','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1752','rundistan'=>48000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1753','rundistan'=>51000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1754','rundistan'=>43000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1755','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1756','rundistan'=>48000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1757','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1758','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1759','rundistan'=>47000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>23,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1760','rundistan'=>52000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1761','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1762','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1763','rundistan'=>56000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1764','rundistan'=>52000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1765','rundistan'=>53000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1766','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1767','rundistan'=>52000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1768','rundistan'=>56000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1769','rundistan'=>53000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1776','rundistan'=>57000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1778','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1779','rundistan'=>56000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1780','rundistan'=>56000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1781','rundistan'=>56000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>19,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1782','rundistan'=>56000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1701','rundistan'=>55000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1702','rundistan'=>55000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1703','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1704','rundistan'=>53000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1705','rundistan'=>53000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1706','rundistan'=>51000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1707','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1708','rundistan'=>51000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1709','rundistan'=>48000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1710','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1711','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1712','rundistan'=>46000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1713','rundistan'=>52000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1714','rundistan'=>52000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1715','rundistan'=>55000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1716','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1717','rundistan'=>51000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1718','rundistan'=>55000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1719','rundistan'=>51000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1720','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1721','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1722','rundistan'=>52000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1723','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1724','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1725','rundistan'=>54000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1726','rundistan'=>49000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1727','rundistan'=>52000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1728','rundistan'=>48000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1729','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>38,'fuel'=>77,'outdate'=>'2016-03','carnum'=>'28하1730','rundistan'=>50000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2016-10','carnum'=>'28하3813','rundistan'=>41000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2016-10','carnum'=>'28하3814','rundistan'=>44000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2016-10','carnum'=>'28하3815','rundistan'=>35000,'color'=>'실버','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>10,'fuel'=>75,'outdate'=>'2016-06','carnum'=>'28하3800','rundistan'=>36000,'color'=>'블랙','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>41,'fuel'=>75,'outdate'=>'2016-03','carnum'=>'28하1700','rundistan'=>41000,'color'=>'블랙','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>41,'fuel'=>75,'outdate'=>'2016-06','carnum'=>'28하3810','rundistan'=>34000,'color'=>'블랙','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>180,'fuel'=>76,'outdate'=>'2016-06','carnum'=>'28하3811','rundistan'=>29000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>180,'fuel'=>76,'outdate'=>'2016-06','carnum'=>'28하3812','rundistan'=>35000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>49,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1771','rundistan'=>41000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>49,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1772','rundistan'=>43000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>116,'fuel'=>76,'outdate'=>'2016-10','carnum'=>'28하3816','rundistan'=>27000,'color'=>'실버','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>116,'fuel'=>76,'outdate'=>'2016-10','carnum'=>'28하3817','rundistan'=>25000,'color'=>'실버','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>116,'fuel'=>76,'outdate'=>'2016-10','carnum'=>'28하3818','rundistan'=>24000,'color'=>'실버','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>51,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1777','rundistan'=>46000,'color'=>'블루','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>127,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1783','rundistan'=>42000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>127,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1788','rundistan'=>40000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>57,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1773','rundistan'=>50000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>57,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1774','rundistan'=>49000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>57,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'28하1775','rundistan'=>49000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>57,'fuel'=>76,'outdate'=>'2016-06','carnum'=>'28하1784','rundistan'=>41000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>57,'fuel'=>76,'outdate'=>'2016-06','carnum'=>'28하1785','rundistan'=>41000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>57,'fuel'=>76,'outdate'=>'2016-05','carnum'=>'28하1786','rundistan'=>46000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>57,'fuel'=>76,'outdate'=>'2016-05','carnum'=>'28하1787','rundistan'=>45000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>365,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'75호5350','rundistan'=>48000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>365,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'75호5351','rundistan'=>40000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>365,'fuel'=>76,'outdate'=>'2016-06','carnum'=>'75호5364','rundistan'=>38000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>365,'fuel'=>76,'outdate'=>'2016-06','carnum'=>'75호5365','rundistan'=>38000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'75호5355','rundistan'=>54000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'75호5356','rundistan'=>54000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'75호5357','rundistan'=>47000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'75호5358','rundistan'=>58000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-03','carnum'=>'75호5359','rundistan'=>49000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-04','carnum'=>'75호5360','rundistan'=>44000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-04','carnum'=>'75호5361','rundistan'=>43000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-04','carnum'=>'75호5362','rundistan'=>40000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>14,'fuel'=>76,'outdate'=>'2016-05','carnum'=>'75호5363','rundistan'=>38000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>381,'fuel'=>75,'outdate'=>'2017-04','carnum'=>'28하3821','rundistan'=>22000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>381,'fuel'=>75,'outdate'=>'2017-04','carnum'=>'28하3822','rundistan'=>22000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>381,'fuel'=>75,'outdate'=>'2017-04','carnum'=>'28하3823','rundistan'=>20000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2017-04','carnum'=>'28하3819','rundistan'=>22000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2017-04','carnum'=>'28하3820','rundistan'=>20000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>30,'fuel'=>76,'outdate'=>'2017-04','carnum'=>'28하3824','rundistan'=>17000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>30,'fuel'=>76,'outdate'=>'2017-04','carnum'=>'28하3825','rundistan'=>16000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>30,'fuel'=>76,'outdate'=>'2017-04','carnum'=>'28하3826','rundistan'=>18000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>193,'fuel'=>75,'outdate'=>'2017-05','carnum'=>'28하3828','rundistan'=>13000,'color'=>'블랙','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>193,'fuel'=>75,'outdate'=>'2017-05','carnum'=>'28하3838','rundistan'=>13000,'color'=>'블랙','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>41,'fuel'=>75,'outdate'=>'2017-04','carnum'=>'28하3830','rundistan'=>17000,'color'=>'블루','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>27,'fuel'=>75,'outdate'=>'2017-06','carnum'=>'28하3831','rundistan'=>14000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>27,'fuel'=>75,'outdate'=>'2017-06','carnum'=>'28하3832','rundistan'=>13000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>27,'fuel'=>75,'outdate'=>'2017-06','carnum'=>'28하3833','rundistan'=>14000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>27,'fuel'=>75,'outdate'=>'2017-06','carnum'=>'28하3834','rundistan'=>14000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>27,'fuel'=>75,'outdate'=>'2017-06','carnum'=>'28하3835','rundistan'=>13000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2017-07','carnum'=>'28하3836','rundistan'=>12000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2017-07','carnum'=>'28하3837','rundistan'=>12000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2017-07','carnum'=>'28하3839','rundistan'=>12000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2017-07','carnum'=>'28하3840','rundistan'=>12000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>113,'fuel'=>75,'outdate'=>'2017-07','carnum'=>'28하3841','rundistan'=>13000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>4,'fuel'=>75,'outdate'=>'2017-09','carnum'=>'28하3842','rundistan'=>6000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>4,'fuel'=>75,'outdate'=>'2017-09','carnum'=>'28하3843','rundistan'=>6000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>4,'fuel'=>75,'outdate'=>'2017-09','carnum'=>'28하3844','rundistan'=>6000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>4,'fuel'=>75,'outdate'=>'2017-09','carnum'=>'28하3845','rundistan'=>7000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>4,'fuel'=>75,'outdate'=>'2017-09','carnum'=>'28하3846','rundistan'=>6000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-09','carnum'=>'28하3847','rundistan'=>4000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-09','carnum'=>'28하3848','rundistan'=>4000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-09','carnum'=>'28하3849','rundistan'=>4000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-09','carnum'=>'28하3850','rundistan'=>4000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-09','carnum'=>'28하3851','rundistan'=>4000,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>148,'fuel'=>75,'outdate'=>'2017-10','carnum'=>'28하3852','rundistan'=>1000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>148,'fuel'=>75,'outdate'=>'2017-10','carnum'=>'28하3853','rundistan'=>1000,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>148,'fuel'=>75,'outdate'=>'2017-10','carnum'=>'28하3854','rundistan'=>100,'color'=>'화이트','rentage'=>26,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-11','carnum'=>'28하3855','rundistan'=>100,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-11','carnum'=>'28하3856','rundistan'=>100,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-11','carnum'=>'28하3857','rundistan'=>100,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-11','carnum'=>'28하3858','rundistan'=>100,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
	['rentshop_idx'=>229,'model_idx'=>338,'fuel'=>77,'outdate'=>'2017-11','carnum'=>'28하3859','rundistan'=>300,'color'=>'화이트','rentage'=>21,'insu_per'=>'무한','insu_goods'=>'20000000','insu_self'=>'15000000','license_limit'=>1,'onsale'=>'N'],
];

$option = [
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','','있음'],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','열선시트','','있음','','있음','있음','있음','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','','','있음','','','있음','',''],
	['','','','있음','','','있음','',''],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','있음','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','있음','있음','있음','있음'],
	['','열선시트','','있음','','있음','있음','있음','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','','','있음','','있음','있음','있음','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','','','있음','','있음','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
	['','열선시트','','있음','','','있음','','있음'],
];

$query = "DELETE FROM vehicle WHERE rentshop_idx=229";
mysql_query($query);

$count = 0;
foreach($vehicle as $item) {
	$query = "INSERT INTO vehicle(rentshop_idx, model_idx, fuel, outdate, carnum, rundistan, color, rentage, insu_per, insu_goods, insu_self, license_limit, onsale, dt_create, dt_update) VALUES (
		{$item['rentshop_idx']}, {$item['model_idx']}, {$item['fuel']}, '{$item['outdate']}', '{$item['carnum']}', {$item['rundistan']}, '{$item['color']}', 
		{$item['rentage']}, '{$item['insu_per']}', 
		{$item['insu_goods']}, {$item['insu_self']}, {$item['license_limit']}, '{$item['onsale']}', NOW(), NOW()
	)";
	$r = mysql_query($query);
	$idx = mysql_insert_id();

	$opNames = ['천장','시트','하이패스','네비게이션','블랙박스','후방카메라','후방센서','스마트키','블루투스'];

	$countOption = 1;
	foreach($option[$count] as $opt) {
		$query = "INSERT INTO vehicle_opt(car_idx, op_idx, op_name, op_data) VALUES ($idx, $countOption, '{$opNames[$countOption-1]}', '$opt')";
		mysql_query($query);
		$countOption++;
	}
	echo $idx.'<br />';
	$count++;
}