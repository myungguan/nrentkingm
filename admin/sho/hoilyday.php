<?php
/**
 * Created by IntelliJ IDEA.
 * User: smkang
 * Date: 2017-12-27
 * Time: 14:46
 */

/**
 * 휴일 설정
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$mode = $_REQUEST['mode'];
$dateArr = $_REQUEST['hoilyday'];

if ($mode == 'w') {

    mysql_query('TRUNCATE TABLE hoilyday');

	$q = "INSERT INTO hoilyday(date) VALUES";
	$i = 0;
	foreach($dateArr as $date) {
        $i++;
	    $q .= '(\''.$date.'\')';
        if(sizeof($dateArr) != $i) {
            $q .= ', ';
        }
    }
    mysql_query($q);

//	echo "<script type='text/javascript'>alert('휴일 설정 저장이 완료되었습니다'); </script>";
}

?>
<form name="regiform" id="regiform" method="post" onsubmit="return confirm('설정된 휴일을 저장하시겠습니까?');">
<div id="pop_contents">
	<span class="subTitle">* 주말요금과 동일하게 적용되는 휴일을 설정합니다.</span>
	<div class="row-fluid">
            <div class="span6">
                <input type='hidden' name='mode' value='w'>
                <span class="subTitle">* 휴일등록</span>
                <table class="detailTable2">
                    <tbody>
                    <tr>
                        <th>휴일등록</th>
                        <td>
                            <input type='text' id='setHoilyday' class="datePicker" data-parent="body" readonly>
                        </td>
                    </tr>
                    </tbody>
                </table>
        <?
                $q = "SELECT * FROM hoilyday ORDER BY date ASC";
                $r = mysql_query($q);
        ?>
                    <span class="subTitle">* 설정된 휴일</span>
                    <?
                    if( mysql_num_rows($r) > 0) {
                    ?>
                    <table class="listTableColor">
                        <tbody>
                        <?
                        while ($row = mysql_fetch_array($r)) {
                            ?>
                            <tr>
                                <td><?= $row['date']; ?></td>
                                <td>
                                    <input type="hidden" name="hoilyday[]" value="<?= $row['date']; ?>"/>
                                    <span class="blackBtn" onclick="delHoilyday(this)">삭제</span>
                                </td>
                            </tr>
                            <?
                        }
                        ?>
                        </tbody>
                    </table>
                    <?
                }else{
                    ?>
                        <table class="listTableColor" id="empty"><tr><td>설정된 휴일이 없습니다.</td></tr></table>
<?
                }
?>
            </div>
        </div>
	</div>
    <div class="btn_wrap btn_center btn_bottom">
        <span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">저장</a></span>
    </div>
</div>
</form>
<script type="text/javascript">
    function delHoilyday(obj){
        $(obj).parent().parent().remove();
    }

    $('#setHoilyday').on('dateChanged',function(e){
        var registHoilyday = $("#setHoilyday").val();
        var isDuplicate = false;
        $.each($('table.listTableColor input'),function(i){
           if($(this).val() == registHoilyday){
               alert('이미 설정된 휴일입니다.');
               isDuplicate = true;
           }
        });

        if(!isDuplicate) {
            $("#empty").remove();
            if($('.listTableColor').length == 0)
            {
                $('.span6').append('<table class="listTableColor"><tbody></tbody></table>');
            }
            $('table.listTableColor tbody').append('<tr><td>'+registHoilyday+'</td><td>' +'<input type="hidden" name="hoilyday[]" value="'+registHoilyday+'"/><span class="blackBtn" onclick="delHoilyday(this)">삭제</span></td></tr>');
        }
        $("#setHoilyday").val('');
    });

</script>