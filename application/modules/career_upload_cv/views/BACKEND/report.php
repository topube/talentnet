<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<style>
	*{font-size:14px!important;}
	.report_table  .td_1{
		overflow: hidden;
	}
	.report_table{

		border-collapse:collapse;
	}
	.report_table th{
		background:#00B3F1;
		color:#FFFFFF;
		font-weight:bold;
		padding:5px 0px 3px 5px;
		border:1px solid black;
	}
	.report_table td{
		padding:5px;
		border:1px solid black;
	}
	.report_table tr{
		background:#f4f4f4;
	}
	caption{background:#F7F7F7;color:#00B3F1;font-weight:bold;height:30px;line-height:30px;font-size:24px;}
</style>
<title>Danh sách câu hỏi</title>
</head>
<body>
<div id="loadingContent" style="float: left;clear: both">
	<table width='100%' class='report_table' cellspacing='1' >
	  <caption >
		Danh sách thành viên (<?=date('d/m/y H:i:s')?>)
	  </caption>	
		<tr>
            <th width='100'>No</th>
        	<th class="center">
				First name
			</th>
			<th class="center">
				Lastname
			</th>
			<th class="center">
				Email
			</th>	
			<th class="center">
				Phone
			</th>
			<th class="center">
				Current Title
			</th>
			<th class="center">
				Years of Experience
			</th>
			<th class="center">
				Linkedin
			</th>
			<th class="center field_created">Created</th>
		</tr>
		<? if(!empty($result)) { 
			$count= 1;
			foreach($result as $row)  {
		?>
		<tr>
            <td valign="top"><?=$count?></td>
			<td class="center vl-m" data-field=""><?=$row->firstname?></td>
			<td class="center vl-m" data-field=""><?=$row->lastname?></td>
			<td class="center vl-m" data-field=""><?=$row->email?></td>
			<td class="center vl-m" data-field=""><?=$row->phone?></td>
			<td class="center vl-m" data-field=""><?=$row->curtit?></td>
			<td class="center vl-m" data-field=""><?=$row->yearsex?></td>
			<td class="center vl-m" data-field=""><?=$row->linkedin?></td>
			<td class="center vl-m" data-field="created"><?=date('d/m/Y H:i:s', strtotime($row->created))?></td>
		</tr>
		<?
				$count++;
				}
			}
		?>
	</table>
</div>
<?php
	header("Content-type: application/x-www-form-urlencoded\r\n" );
    header('Content-Disposition: attachment; filename="subscriber_'.time().'.xls"');
?>
</body>
</html>
