<?php
include('../conn/dbtest_sqlite.php');

date_default_timezone_set('Asia/Calcutta');
$date = date('Y/m/d H:i:s', time());


if($_GET['status'] == 0)//add & create user using pdo preapared statement
{
	$flag = 0;

	$Username = $_POST['Username'];
	$Password = $_POST['Password'];
	$location = $_POST['location'];
	$userroleid = $_POST['userroleid'];
	

	try
	{
		$conn->beginTransaction();
		
		// prepare and bind
		$sql = "INSERT INTO [user] (Username, Password, Location) VALUES (?,?,?)";
		$stmt = $conn->prepare($sql);
		if($stmt)
		{
			
			$insert = $stmt->execute([$Username, $Password, $location]);

			if($insert)
			{
				$qry = "select max(Userid) as userid from [user]";
				$stmtid = $conn->prepare($qry);
		        $stmtid->execute();
		        $data = $stmtid->fetch(PDO::FETCH_ASSOC);
		        $userid = $data['userid'];

				// $qry = "select max(Userid) as userid from user";
				// $stmtid = $conn->prepare($qry);
				// $conn->execute();
				// $conn->bind_result($userid);
				// $conn->fetch();
				//$userid = $userid;

				foreach ($userroleid as $key => $value) 
				{
					$sql1 = "INSERT INTO user_role (Userid, Roleid) VALUES (?,?)";
					$stmt1 = $conn->prepare($sql1);
					if($stmt1)
					{
						$insert1 = $stmt1->execute([$userid,$userroleid[$key]]);						
					}
				}
			}		
		}

		if(!$insert || !$insert1)
			$flag = 1;
	

	} 
	catch(Exception $e)
	{
		$conn->rollBack();
		throw $e;
		$flag = 1;		
	}
	catch (PDOException $e) {
    // Handle any database-related errors
		    $conn->rollBack();
		    echo "Error: " . $e->getMessage();
		}

	if($flag == 0)
	{
		$conn->commit();
		header('location:../userlist.php?msg=suc');
	}
	else if($flag == 1)
	{
		header('location:../userlist.php?msg=err');
	}

}
else if($_GET['status'] == 1)// 
{
	$flag = 0;

	$Username = $_POST['Username'];
	$Password = $_POST['Password'];
	$location = $_POST['location'];
	$userroleid = $_POST['userroleid'];
	
	try 
	{
	    $conn->beginTransaction();

	    $sql = "INSERT INTO [user] (Username, Password, Location) VALUES (?, ?, ?)";
	    $stmt = $conn->prepare($sql);
	    $stmt->execute([$Username, $Password, $location]);

	    $userid = $conn->lastInsertId();

	    foreach ($userroleid as $key => $value) 
	    {
	        $sql1 = "INSERT INTO user_role (Userid, Roleid) VALUES (?, ?)";
	        $stmt = $conn->prepare($sql1);
	        $stmt->execute([$userid, $userroleid[$key]]);
	    }

	    // Check if any query failed
	    if ($stmt->rowCount() == 0) 
	    {
	        $conn->rollBack();
	        header('location:../userlist.php?msg=err');
	    } 
	    else 
	    {
	        $conn->commit();
	        header('location:../userlist.php?msg=suc');
	    }
	} 
	catch (PDOException $e) 
	{
	    $conn->rollBack();
	    echo "Error: " . $e->getMessage();
	    header('location:../userlist.php?msg=err');
	}
	catch(Exception $e)
	{
		$conn->rollBack();
		throw $e;
		echo "Transaction rolled back. Error: " . $e->getMessage();
		header('location:../userlist.php?msg=err');
	}
}

function lastInsertId()
{
	$qry = "SELECT MAX(Userid) AS userid FROM [user]";
	$run = sqlsrv_prepare($conn, $qry);
	$row = sqlsrv_fetch_array($run, SQLSRV_FETCH_ASSOC);
 	
 	$userid = $row['userid'];
 	echo $userid;
}
?>
