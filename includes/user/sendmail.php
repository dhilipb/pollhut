<?php
$recp = explode(",", post("recp") . ",");
array_pop($recp);
$subject = "Check this poll!";
$from = (!emptypost("name") ? post("name") : (user()->loggedin ? user()->username : "PollHut"));
$email = (!emptypost("email") ? post("email") : (user()->loggedin ? user()->email : "noreply@pollhut.com"));
$headers = "MIME-Version: 1.0 \r\n".
            "Content-type: text/html; charset=iso-8859-1 \r\n".
            "From: $from <$email> \r\n" .
            "Reply-To: $email \r\n" .
            "X-Mailer: PHP/" . phpversion();
$p_message = str_replace("\n", "<br />", post("message"));

// post information
$post_id = post("post");
$post = new Post();
$post->db($post_id);

// get chart
require_once("includes/chart/get.php");
ob_start();
drawPostChart($post, INFINITE, "email");
$chart = ob_get_contents();
ob_end_clean();

foreach($recp as $to) {
	insert("tbl_emails", array("post_id" => $post_id,
								"to_email" => $to,
								"user_id" => user()->id,
								"from_email" => post("email"),
								"from_name" => post("name"),
								"message" => post("message")));
    ob_start();
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
						height: 47px;
						padding-left: 10px;
						padding-top: 4px;
					}
					h2, h2 * {
						font-size: 14px;
					}
					img {border: 0}
				.chart {
				  position: relative;
				}
				.chart * {
				  font-size: 8pt;
				}
				.chart .chart-item {
				  position: relative;
				  background: #dbebf8;
				}
				.chart .chart-item .colour {
				  width: 100%;
				  margin: 0 0 3px 0;
				  background: url(http://www.dhilip.co.uk/choicelion/style/../images/light_blue.png);
				  height: 25px;
				  padding: 7px 0px 0 0;
				}
				.chart .chart-item .text {
				  width: 100%;
				  position: absolute;
				  top: 8px;
				}
				.chart .chart-item .text .value {
				  position: absolute;
				  top: 1px;
				  right: 3px;
				  width: 30px;
				  text-align: right;
				}
				.chart .chart-item .text .label {
				  white-space: nowrap;
				  width: 70%;
				  overflow: hidden;
				  position: absolute;
				  top: 1px;
				  left: 15%;
				}
				.chart .chart-item .text .label span {
				  position: relative;
				  -moz-user-select: none;
				  -webkit-user-select: none;
				  user-select: none;
				}
				.chart .chart-item .text .chartcheck {
				  width: 15%;
				  background: url(http://www.dhilip.co.uk/choicelion/style/../images/uncheck.png) center no-repeat;
				  height: 15px;
				  display: block;
				}
				.chart .chart-item .text .chartcheck input {
				  display: none;
				}
				.chart .chart-item .text .chartcheck.checked {
				  background-image: url(http://www.dhilip.co.uk/choicelion/style/../images/check.png);
				}
				.chart .leading {
				  color: white;
				  width: 100%;
				}
				.chart .leading .colour {
				  background: url(http://www.dhilip.co.uk/choicelion/style/../images/dark_blue.png);
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
				<p>&nbsp;</p>
			</body>
		</html>           
    <?
    $message = ob_get_contents();
    ob_end_clean();
    $file = fopen("logemail.html", "w");
    fwrite($file, $message);
    fclose($file);
    mail($to, $subject, $message, $headers);
}
$this->title = "Email Complete";

?>
Thank you.. your email has been sent successfully to the following recipients:
<ol>
	<?php
	foreach($recp as &$to) {
		echo "<li>$to</li>";
	}
	?>
</ol>