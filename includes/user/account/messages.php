<?php
session_start();

if ($_GET["folder"] == "sent") {
    $folder = "sent";
    $messages = select("tbl_messages", "WHERE user_id_from = '".$this->user->id."' ORDER BY id DESC");
} else { 
    $folder = "inbox";
    $messages = select("tbl_messages", "WHERE user_id_to = '".$this->user->id."' ORDER BY id DESC");
}
?>
<div id="tabs">
    <div id="activetab">
        <h1><?=ucwords($folder)?></h1>
    </div>
    <ul>
        <? if ($folder == "sent") { ?>
            <a href="<?=linkify("folder", "")?>"><li>Inbox</li></a>
            <? } else { ?>
            <a href="<?=linkify("folder", "sent")?>"><li>Sent</li></a>
        <? } ?>
        <a href="ajax/popout/compose.php" class="popout"><li>Compose</li></a>
    </ul>
</div>
<?
    while ($msgrow = mysql_fetch_assoc($messages)) {
        
    }
?>
<div class="sort-box">
    <?php
        sort_box(array("date", "name", "read"));
    ?>
</div>
<ul id="message_container">
    <? for ($count = 0; $count < 10; $count++) { ?>
    <li <?=rand(0,10) % 2 == 0 ? "class=\"unread\"" : ""?>>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="msg_checkbox"><input type="checkbox" /></td>
                <td class="msg_profilepic"><img src="images/qrcode/dhilipb.png" /></td>
                <td>
                    <div class="msg_name">Name</div>
                    <div class="msg_date">Date</div>
                </td>
                <td>
                    <div class="msg_subject">Subject</div>
                    <div class="msg_message">Message</div>
                </td>
                <td class="msg_delete">
                    <img src="images/bin.png" width="14px" />
                </td>
            </tr>
        </table>
    </li>
    <? } ?>
</ul>