<?php
/*
 * user/view.php: View user profile. 
 * @param: Username
 */
 
$myProfile = false;

if (get("u") == user()->username) {
    $myProfile = true;
    $user = user();
    $user->db(); 
} else {
    $user = new User();
    $user->username = get("u");
    $user->db();
}

$this->title = $user->username;

$username = $user->username;

if ($myProfile) $this->toolslist = array("Edit My Profile" => linkify("account", "settings"));
else update("tbl_users", array("views" => "'views + 1"), "WHERE id = '".$user->id."'"); ?>
<style type="text/css">
	#userinfo td, #userinfo th {
		padding-top: 10px;
		font-size: 14px;
		width: 140px;
	}
	#userinfo tr td:last-child {
		padding-left: 20px;
	}

</style>
<h2><?=$user->group_name?></h2>
<table cellspacing="0" cellpadding="0" id="userinfo">
    <tr>
        <th>
            username
        </th>
        <td>
            <?=$username?>
        </td>
    </tr>
    <tr>
        <th>
            registered on
        </th>
        <td>
            <?=mysql_phpdate("d/m/Y", $user->timestamp)?>
        </td>
    </tr>
    <tr>
        <th>
            last logged in
        </th>
        <td>
            <?=time_since($user->lastloggedin)?>
        </td>
    </tr>
    <tr>
        <th>
            total posts
        </th>
        <td>
            <?=$user->posts?>
        </td>
    </tr>
    <tr>
        <th>
            total comments
        </th>
        <td>
            <?=$user->comments?>
        </td>
    </tr>
    <tr>
        <th>
            total likes
        </th>
        <td>
            <?=$user->likes?>
        </td>
    </tr>
    <tr>
        <th>
            profile views
        </th>
        <td>
            <?=$user->views?>
        </td>
    </tr>
</table>
<h2 style="margin-top: 20px;">My Posts</h2>
<div class="sort-box" style="margin-bottom: 10px;">
    <div style="float: left;">
        
        <? if ($myProfile) {
        	sort_box(array("Public Posts", "Private Posts"), "show"); 
        }?>
    </div>
    <?php
        sort_box(array("votes", "views", "recent"));
    ?>
</div>
<?
	require_once("includes/post/list.php");
?>