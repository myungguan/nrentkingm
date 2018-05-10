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

mysql_query('TRUNCATE TABLE site_vehicle_price');
mysql_query('TRUNCATE TABLE site_vehicle_price_longterm');
$r = mysql_query("SELECT r.index_no rentshop, r.mem_idx member, v.* FROM site_vehicle v LEFT JOIN site_rentshop r ON v.com_idx = r.index_no");
while($row = mysql_fetch_array($r)) {
	$index_no = $row['index_no'];
	$member = $row['member'];
	$rentshop = $row['rentshop'];
	$ac1_1 = unserialize($row['ac1_1']);
	$ac1_1per = unserialize($row['ac1_1per']);
	$ac1_2 = unserialize($row['ac1_2']);
	$ac1_2per = unserialize($row['ac1_2per']);
	$del1_ac = unserialize($row['del1_ac']);
	$del1_ac1 = unserialize($row['del1_ac1']);
	$del1_ac2 = unserialize($row['del1_ac2']);
	$insu1_1ac = unserialize($row['insu1_1ac']);
	$insu1_2ac = unserialize($row['insu1_2ac']);
	$insu1_3ac = unserialize($row['insu1_3ac']);
	$acl1 = unserialize($row['acl1']);
	$racl1 = unserialize($row['racl1']);
	
	$price = intval($ac1_1[0]);
	$price_holiday = intval($ac1_2[0]);
	$price_discount2 = $price > 0 ? intval($ac1_1[1]) / $price : 1;
	$price_discount3 = $price > 0 ? intval($ac1_1[2]) / $price : 1;
	$price_discount4 = $price > 0 ? intval($ac1_1[3]) / $price : 1;
	$price_del1 = intval($del1_ac[0]);
	$price_del2 = intval($del1_ac[1]);
	$price_del3 = intval($del1_ac[2]);
	$price_del4 = intval($del1_ac[3]);
	$price_insu_check = $row['insu1_1'];
	$price_insu1 = intval($insu1_1ac[0]);
	$price_insu2 = intval($insu1_1ac[1]);
	$price_insu3 = intval($insu1_1ac[2]);
	$price_insu4 = intval($insu1_1ac[3]);
	$price_insu_exem = intval($row['insu1_1stdac']);
	$price_insu_super_check = $row['insu1_2'];
	$price_insu_super1 = intval($insu1_2ac[0]);
	$price_insu_super2 = intval($insu1_2ac[1]);
	$price_insu_super3 = intval($insu1_2ac[2]);
	$price_insu_super4 = intval($insu1_2ac[3]);
	$price_insu_super_exem = intval($row['insu1_2stdac']);
	$price_insu_perfect_check = $row['insu1_3'];
	$price_insu_perfect1 = intval($insu1_3ac[0]);
	$price_insu_perfect2 = intval($insu1_3ac[1]);
	$price_insu_perfect3 = intval($insu1_3ac[2]);
	$price_insu_perfect4 = intval($insu1_3ac[3]);


	$query = "INSERT INTO site_vehicle_price(
		member_idx, 
		rentshop_idx, 
		price, 
		price_holiday, 
		price_discount2, 
		price_discount3, 
		price_discount4, 
		price_del1, 
		price_del2, 
		price_del3,
		price_del4,
		price_insu_check,
		price_insu1,
		price_insu2,
		price_insu3,
		price_insu4,
		price_insu_exem,
		price_insu_super_check,
		price_insu_super1,
		price_insu_super2,
		price_insu_super3,
		price_insu_super4,
		price_insu_super_exem,
		price_insu_perfect_check,
		price_insu_perfect1,
		price_insu_perfect2,
		price_insu_perfect3,
		price_insu_perfect4,
		dt_create
	) VALUES (
		$member,
		$rentshop,
		'$price', 
		'$price_holiday', 
		'$price_discount2', 
		'$price_discount3', 
		'$price_discount4', 
		'$price_del1', 
		'$price_del2', 
		'$price_del3',
		'$price_del4',
		'$price_insu_check',
		'$price_insu1',
		'$price_insu2',
		'$price_insu3',
		'$price_insu4',
		'$price_insu_exem',
		'$price_insu_super_check',
		'$price_insu_super1',
		'$price_insu_super2',
		'$price_insu_super3',
		'$price_insu_super4',
		'$price_insu_super_exem',
		'$price_insu_perfect_check',
		'$price_insu_perfect1',
		'$price_insu_perfect2',
		'$price_insu_perfect3',
		'$price_insu_perfect4',
		NOW()
	)";

	mysql_query($query);
	$idx = mysql_insert_id();
	mysql_query("UPDATE site_vehicle SET price_idx=$idx WHERE index_no=$index_no");

	$price_longterm1 = intval($acl1[0]);
	$price_longterm2 = intval($acl1[1]);
	$price_longterm3 = intval($acl1[2]);
	$price_longterm_deposit1 = intval($racl1[0]);
	$price_longterm_deposit2 = intval($racl1[1]);
	$price_longterm_deposit3 = intval($racl1[2]);
	$query = "INSERT INTO site_vehicle_price_longterm (
		member_idx, 
		rentshop_idx, 
		price_longterm1, 
		price_longterm2, 
		price_longterm3, 
		price_longterm_deposit1, 
		price_longterm_deposit2, 
		price_longterm_deposit3,
		dt_create
	) VALUES (
		$member,
		$rentshop,
		$price_longterm1,
		$price_longterm2,
		$price_longterm3,
		$price_longterm_deposit1,
		$price_longterm_deposit2,
		$price_longterm_deposit3,
		NOW()
	)
	";

	mysql_query($query);
	$idx = mysql_insert_id();
	mysql_query("UPDATE site_vehicle SET price_longterm_idx=$idx WHERE index_no=$index_no");
}