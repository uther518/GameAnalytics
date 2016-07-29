<?php
if( !defined( 'IN_INU' ) )
{
    return;
}
include('header.php');
include('menu.php');
?>
<div class="pannel">
    <form method="GET" action="?">
    <b>用户ID:</b><input name="input_uid" type="text" value="<?php echo $_GET[ 'input_uid' ];?>"/>
    <input type="submit" value="导入配置" class="btn"/>
    <input type="hidden" name="f" value="<?php echo $_GET[ 'f' ];?>"/>
    <input type="hidden" name="do" value="update"/>
    
    <b>配置(UID:<?php echo $userInfo[ 'uid' ] ?>):</b>
    <textarea name="user_json" style="width:400px;height:200px;"><?php echo $userInfoJSON; ?></textarea>
    (注：部分数据项暂时无法导入)
    </form>
</div>
<?php
include('footer.php');
?>