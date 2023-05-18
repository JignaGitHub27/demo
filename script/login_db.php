<?php
include('../conn/dbtest_mysql.php');
$stmt = $mysqli -> stmt_init();

date_default_timezone_set('Asia/Calcutta');
$date = date('Y/m/d H:i:s', time());

if($_GET['status'] == 0)
{
	$uname = $_POST['Username'];
	$pass = $_POST['Password'];
	
	$query = "select Username from user where Username = ? and Password = ?";
	if($stmt->prepare($query))
	{
		$stmt->bind_param('ss',$uname,$pass);
		$stmt->execute();
		$stmt->bind_result($Username);
		$stmt->fetch();
			
		$user = $Username;
		
		if($uname == $Username)
		{
			$unm = $Username;
			$query1 = "select Password from user where Username = ? and Password = ?";
			$stmt->prepare($query1);
			$stmt->bind_param('ss',$uname,$pass);
			$stmt->execute();
			$stmt->bind_result($Password);
			$stmt->fetch();
			
			$pas = $Password;
			$stmt->free_result();

			if($pas == $pass)
			{
				header("location:../userlist.php");
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}

}
else if($_GET['status'] == 1)//add & create user
{
	$flag = 0;

	$Username = $_POST['Username'];
	$Password = $_POST['Password'];
	$location = $_POST['location'];
	$userroleid = $_POST['userroleid'];
	

	try
	{
		mysqli_autocommit($mysqli,FALSE);

		// prepare and bind
		$sql = "INSERT INTO user (Username, Password, Location) VALUES (?,?,?)";
		if($stmt->prepare($sql))
		{
			$stmt->bind_param("sss",$Username,$Password,$location);
			$insert = $stmt->execute();	

			if($insert)
			{
				$qry = "select max(Userid) as userid from user";
				$stmt->prepare($qry);
				$stmt->execute();
				$stmt->bind_result($userid);
				$stmt->fetch();

				$userid = $userid;

				foreach ($userroleid as $key => $value) 
				{
					$sql1 = "INSERT INTO user_role (Userid, Roleid) VALUES (?,?)";
					if($stmt->prepare($sql1))
					{
						$stmt->bind_param("ss",$userid,$userroleid[$key]);
						$insert = $stmt->execute();
					}
				}
			}		
		}

		if(!$insert)
			$flag = 1;
		
	} 
	catch(Exception $e)
	{
		mysqli_rollback($mysqli);
		throw $e;
		$flag = 1;		
	}


	if($flag == 0)
	{
		$mysqli->commit();
		mysqli_autocommit($mysqli,TRUE);
		header('location:../userlist.php?msg=suc');
	}
	else if($flag == 1)
	{
		header('location:../userlist.php?msg=err');
	}

}
else if($_GET['status'] == 2)
{
	$id = $_POST['id'];
	$sql = "SELECT a.Userid, a.Username, a.Password, a.Location FROM `user` a WHERE a.Userid = ?";
	$stmt->prepare($sql);
	$stmt->bind_param("s",$id);
	$stmt->execute();
	$stmt->bind_result($Userid,$Username,$Password,$Location);
	$stmt->fetch();

	$sql1 = "select a.Usre_role_id , a.Roleid, b.Role from user_role a left join role b on a.Roleid = b.RoleID where Userid = ?";
	$stmt->prepare($sql1);
	$stmt->bind_param("s",$id);
	$stmt->execute();
	$stmt->bind_result($Usre_role_id,$Roleid,$Role);
	while($stmt->fetch())
	{
		$role[] = $Role;
	}

	print_r($role);

	

	?>
	<form action="script/login_db.php?status=3" method="post" id="edituser">
        <div>
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="text" name="Username" class="form-control border border-primary" id="exampleInputEmail1" aria-describedby="emailHelp" autocomplete="off" value="<?php echo $Username; ?>">
        </div>
        <div>
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="text" name="Password" class="form-control border border-primary" id="exampleInputPassword1" autocomplete="off" value="<?php echo $Password; ?>">
        </div> 
        <div>
            <label for="exampleInputLocation1" class="form-label">Location</label>
            <input type="text" name="location" class="form-control border border-primary" id="exampleInputLocation1" autocomplete="off" value="<?php echo $Location; ?>">
        </div>
        <div>
            <label for="exampleInputLocation1" class="form-label">Role</label>
            <input type="text" name="location" class="form-control" id="exampleInputLocation1" autocomplete="off" value="<?php echo $Role; ?>" readonly>
        </div> 
        <div>
            <label for="exampleInputPassword1" class="form-label">Role</label><br/>
            <select class="form-select form-control border border-primary" name="roleid[]" style="width:100%;" id="userroleid" multiple>
                <option value="">Select Roles</option>
                <?php
                $count = 0;
                $sql = "select * from role order by Role asc";
                if($stmt->prepare($sql))
                {
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc())
                    {  
                ?>
                <option value="<?php echo $row['RoleID']; ?>"><?php echo $row['Role']; ?></option>
                <?php $count++; } } ?>
            </select>       
            <input type="hidden" name="userroleidp" value="<?php echo $Usre_role_id; ?>">         
            <input type="hidden" name="Userid" value="<?php echo $id; ?>">        
        </div>                
    </form>
	<?php
}
else if($_GET['status'] == 3)
{
	$flag = 0;

	$Username = $_POST['Username'];
	$Password = $_POST['Password'];
	$location = $_POST['location'];
	$userroleidp = $_POST['userroleidp'];
	$Userid = $_POST['Userid'];
	$roleid = $_POST['roleid'];
	

	try
	{
		mysqli_autocommit($mysqli,FALSE);

		// prepare and bind
		$sql = "Update user set Location = ? where Userid = ?";
		if($stmt->prepare($sql))
		{
			$stmt->bind_param("ss",$location,$Userid);
			$update = $stmt->execute();	

			if($update)
			{
				$qry = "select max(Userid) as userid from user";
				$stmt->prepare($qry);
				$stmt->execute();
				$stmt->bind_result($userid);
				$stmt->fetch();

				$userid = $userid;

				foreach ($userroleid as $key => $value) 
				{
					$sql1 = "Update user_role set Roleid = ? where Userid = ? and Usre_role_id = ?";
					if($stmt->prepare($sql1))
					{
						$stmt->bind_param("ss",$userid,$userroleid[$key]);
						$update = $stmt->execute();
					}
				}
			}		
		}

		if(!$update)
			$flag = 1;
		
	} 
	catch(Exception $e)
	{
		mysqli_rollback($mysqli);
		throw $e;
		$flag = 1;		
	}


	if($flag == 0)
	{
		$mysqli->commit();
		mysqli_autocommit($mysqli,TRUE);
		header('location:../userlist.php?msg=suc');
	}
	else if($flag == 1)
	{
		header('location:../userlist.php?msg=err');
	}
}

?>
<script type="text/javascript">
    
    $(document).ready( function ()
    {
        $("#userroleid").select2({
            dropdownParent: $('#editmodal')
        });

    });
  </script>