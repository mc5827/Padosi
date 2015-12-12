<?php $hood=($_GET['hood']);
$con = mysql_connect('localhost', 'root', ''); 
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('dbproject');
$query="SELECT block FROM location WHERE hood='$hood'";
$result=mysql_query($query);

?>
<select name="block" class="form-control">
<option>Select Block</option>
<?php $counter=1;while ($row=mysql_fetch_array($result)) { ?>
<option value=<?php echo $row['block']?>><?php echo $row['block']?></option>
<?php } ?>
</select>
