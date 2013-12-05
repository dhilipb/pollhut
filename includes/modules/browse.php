<style type="text/css">
	.browse {
		background-color: #eee;
		border: 1px solid #ccc;
		width: 310px;
		height: 250px;
		float: left;
		margin-bottom: 20px;
		margin-right: 30px;
		box-shadow: 0 0 5px #ccc;
		-moz-box-shadow: 0 0 5px #ccc;
	}
	.browse.third {
		margin-right: 0;
	}
	.browse .image {
		width: 100%;
		height: 100%;
/*		float: left;*/
		border-right: 1px solid #ccc;
		position: relative;
		background-repeat: no-repeat;
		background-position: top center;
		background-size: 310px;
	}
	.browse .image:hover .title {
		background: black;
	}
	.browse .image .title {
		background-color: rgba(0,0,0,0.7);
       	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#B2000000,endColorstr=#B2000000);
       	zoom: 1;
		width: 100%;
		color: white;
		position: absolute;
		top: 140px;
		padding: 10px 0;
		text-indent: 20px;
		font-size: 22px;
		font-family: WowM;
		text-shadow: 1px 1px 2px black;
	}
	.browse .image .info {
		background-color: white;
		width: 100%;
		padding: 10px 0;
/*		text-indent: 20px;*/
		border-top: 1px solid #ccc;
		border-bottom: 1px solid #ccc;
		position: absolute;
		bottom: -1px;
		color: #444444;
		font-size: 13px;
	}
	.browse .image .info .left {
		width: 150px;
		text-align: center;
		display: block;
		float: left;
	}
	.browse .image .info .right {
		width: 155px;
		text-align: center;
		display: block;
		border-left: 1px solid #CCC;
		float: right;
	}
	.browse > a:hover {
		text-decoration: none;
	}
	
	.browse .content {
		float: left;
		margin-left: 10px;
	}
	.browse .content dt {
		text-transform: uppercase;
		font-weight: bold;
		font-family: WowM;
		font-size: 16px;
	}
	.browse .content dt, .browse .content dd, .browse .content dd a {
		color: #777777;
		margin-bottom: 10px;
		font-size: 14px;
	}
	.browse .content dd {
		margin-left: 10px;
		max-width: 165px;
	}
</style>
<?php
$i = 1;
$catCount = array();
$qry = select("p_alias, count(p_alias) as count", "view_categories_parent", "group by p_alias");
while ($data = assoc($qry)) {
	$catCount[$data["p_alias"]] = $data["count"];
}

$qry = select("*", "view_categories_count");
while ($data = assoc($qry)) {
	?>
	<div class="browse <?=$i % 3 == 0 ? "third" : null?>">
		<a href="<?=linkify("cat", $data["alias"])?>">
			<div class="image" style="background-image: url('../images/cat/<?=$data["alias"]?>.jpg')">
				<div class="title">
					<?=$data["name"]?>
				</div>
				<div class="info">
					<span class='left'>
						<?=intval($data["count"]) . pluralize($data["count"], ' poll')?>
					</span>
					<span class='right'>
						<?=$catCount[$data["alias"]]?> sub-categories
					</span>
				</div>
			</div>
		</a>
	</div>
<? $i++; } ?> 
<div class="clear"></div>
