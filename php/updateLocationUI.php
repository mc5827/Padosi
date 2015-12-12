<?php 
$con = mysql_connect('localhost', 'root', 'password'); 
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('dbproject');
$query="SELECT distinct(hood) FROM location";
$result=mysql_query($query);
?>
<html>
<head>
<title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" type="text/javascript">
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getBlock(hood) {		
		
		var strURL="findBlock.php?hood="+hood;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('blockdiv').innerHTML=req.responseText;
					} else {
						alert("Problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
</script>

<style type="text/css">
body {font-family: Arial, "Trebuchet MS";font-size: 17px;color: #52B6EB; }
a{font-weight: bold;letter-spacing: -1px;color: #52B6EB;text-decoration:none;}
a:hover{color: #99A607;text-decoration:underline;}
#top{width:43%;margin-top: 25px; height:60px; border:1px solid #BBBBBB; padding:10px;}
#tutorialHead{width:43%;margin-top: 12px; height:30px; border:1px solid #BBBBBB; padding:11px;}
.tutorialTitle{width:95%;float:left;color:#99A607}
.tutorialTitle  a{float:right;margin-left:20px;}
.tutorialLink{float:right;}
table
{
margin-top:70px;
border: 1px solid #BBBBBB;
padding:25px;
height: 35px;
}
</style>
</head>
<body>
<!-- <form method="post" action="" name="form1"> -->
<center>

<table width="45%"  cellspacing="0" cellpadding="0">
  <tr>
    <td width="75">Hood</td>
     <td width="50">:</td>
    <td  width="150"><select name="hood" onChange="getBlock(this.value)">
	<option value="">Select Hood</option>
	<?php while ($row=mysql_fetch_array($result)) { ?>
	<option value=<?php echo $row['hood']?>><?php echo $row['hood']?></option>
	<?php } ?>
	</select></td>
  </tr>
  <tr style="">
    <td>Block</td>
    <td width="50">:</td>
    <td ><div id="blockdiv"><select name="block" >
	<option>Select Block</option>
        </select></div></td>
  </tr>
  
</table>
</center>
<!-- </form> -->
</body>
</html>
