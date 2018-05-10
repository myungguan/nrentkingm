<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/module/searchbar.htm 000001971 */ ?>
<form name="searchBar" id="searchBar" action="/rent/search.php" method="get">
	<ul class="search">
		<li class="sdate">
			<input type='text' name='sdate' id="sdate" class="dateTimePicker" placeholder="픽업일시 / 시간" value="<?php echo $TPL_VAR["global"]["sdate"]?>" readonly
				data-parent="body"
				data-field-date="#sdateDate"
				data-field-time="#sdateTime"
				data-end="#edate"
				data-min-date="<?php echo $TPL_VAR["global"]["mindate"]?>"
				data-max-date-origin="today"
				data-max-date="40d">
			<label for="sdate">
				<span class="placeholder">픽업일시</span>
				<span class="date" id="sdateDate"><?php echo substr($TPL_VAR["global"]["sdate"], 0, 10)?></span>
				<span class="time" id="sdateTime"><?php echo substr($TPL_VAR["global"]["sdate"], 11, 5)?></span>
			</label>
		</li>
		<li class="edate">
			<input type='text' name='edate' id="edate" class="dateTimePicker" placeholder="반납일시 / 시간" value="<?php echo $TPL_VAR["global"]["edate"]?>" readonly
				data-parent="body"
				data-field-date="#edateDate"
				data-field-time="#edateTime"
				data-template="1w,1M,2M,3M,6M,1y"
				data-start="#sdate"
				data-min-date-origin="#sdate"
				data-min-date="1d"
				data-max-date-origin="#sdate"
				data-max-date="1y">
			<label for="edate">
				<span class="placeholder">반납일시</span>
				<span class="date" id="edateDate"><?php echo substr($TPL_VAR["global"]["edate"], 0, 10)?></span>
				<span class="time" id="edateTime"><?php echo substr($TPL_VAR["global"]["edate"], 11, 5)?></span>
			</label>
		</li>
		<li class="submit">
			<button type="submit">차량 검색</button>
		</li>
	</ul>
	<a href="/partials/help/searchbar.m.html" class="help-tooltip white openPopup" data-title="검색" title="도움말" data-width="730">도움말</a>
</form>