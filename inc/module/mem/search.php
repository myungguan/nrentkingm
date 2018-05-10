<form name="mod_form" id="mod_form">
<input type='hidden' name='target' value='<?=$_REQUEST['target'];?>'>
<div style="">
	<select name='key'>
		<option value='id'>아이디</option>
		<option value='name'>이름</option>
		<option value='cp'>핸드폰</option>
	</select>
	<input type="text" name="keyword" id='keyword' class=""  onKeyPress="javascript:if(event.keyCode == 13) { search_id(); }">
	<span class="blackBtn" onclick="search_id();">검색</span>
</div>
</form>
<div style='margin-top:10px;'>
	<table class="listTable_gray2" style="width:100%;">
	<thead>
	<tr>
		<th>회원번호</th>
		<th>아이디</th>
		<th>이름</th>
		<th>연락처</th>
		<th>최종렌트차량</th>
		<th>가입일</th>
		<th>선택</th>
	</tr>
	</thead>
	<tbody id="memlist">

	</tbody>
	</table>
</div>	
