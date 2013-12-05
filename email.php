<?php
	require ("includes/defines.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Email</title>
		<style type="text/css">
			* {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			
			body {
				margin: 0; padding: 0;
				background: #DDD;
				text-align: center;
			}
			#header {
				background: url(<?=SITEURL?>/images/header.png) repeat-x;
				height: 51px;
				padding-left: 10px;
			}
			h2, h2 * {
				font-size: 14px;
			}
			
			/* Chart */
			.chart {
			  position: relative;
			}
			.chart * {
			  font-size: 8pt;
			}
			.chart .chart-item {
			  cursor: pointer;
			  position: relative;
			  background: #dbebf8;
			}
			.chart .chart-item .colour {
			  width: 100%;
			  margin: 0 0 3px 0;
			  background: url(<?=SITEURL?>/images/light_blue.png);
			  height: 25px;
			  padding: 7px 0px 0 0;
			}
			.chart .chart-item .text {
			  width: 100%;
			  position: absolute;
			  top: 8px;
			}
			.chart .chart-item .text .label {
			  white-space: nowrap;
			  width: 83%;
			  overflow: hidden;
			  position: absolute;
			  top: 0px;
			  left: 15%;
			}
			.chart .chart-item .text .label span {
			  position: relative;
			  -moz-user-select: none;
			  -webkit-user-select: none;
			  user-select: none;
			}
			.chart .chart-item .text .fancycheck {
			  /*position: absolute;
							top: 8px; left: 7px;*/
			
			  width: 15%;
			  background: url(<?=SITEURL?>/images/uncheck.png) center no-repeat;
			  height: 15px;
			  display: block;
			}
			.chart .chart-item .text .fancycheck input {
			  display: none;
			}
			.chart .chart-item .text .fancycheck.checked {
			  background-image: url(<?=SITEURL?>/images/check.png);
			}
			.chart .leading {
			  color: white;
			  width: 100%;
			}
			.chart .leading .colour {
			  background: url(<?=SITEURL?>/images/dark_blue.png);
			}
			.chart .chart_more div {
			  padding: 5px;
			  text-align: center;
			  border: thin dashed #dbebf8;
			}
			.chart .chart_more:hover {
			  text-decoration: none;
			}
			.chart .chart_more:hover div {
			  background: #dbebf8;
			}
			.chart .chart_add {
			  border: thin dashed #dbebf8;
			}
			.chart .chart_add input {
			  border: none;
			  width: 98%;
			  color: #707070;
			  margin: auto;
			  height: 25px;
			}
			.chart .chart_add input:focus {
			  color: black;
			}
		</style>
	</head>
	<body>
		<p>&nbsp;</p>
		<table width="400" cellpadding="0" cellspacing="0" align="center" style="border: thin solid #aaa;">
			<tr>
				<td id="header" align="left">
					<a href="<?=SITEURL?>" alt="PollGenome">
						<img src="<?=SITEURL?>/images/pollgenome.png" />
					</a>
				</td>
			</tr>
			<tr>
				<td bgcolor="white" style="padding: 10px;" align="left">
					<h2>Hi <a href="<?=$to?>"><?=$to?></a>,</h2>
					<strong><?=$from?></strong>(<a href="<?=$email?>"><?=$email?></a>) has sent this poll to you with this message:
					<p><?=$p_message?></p>
					<p>To vote in this poll, click <a href="<?=SITEURL."/".linkify("post", $post_id)?>">here</a></p>
					<div class="chart">
						<input type="hidden" name="email" value="<?=$to?>" />
						<?=$chart?>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html> 