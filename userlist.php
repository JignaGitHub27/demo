<?php
include('conn/dbtest_mysql.php');
$stmt = $mysqli -> stmt_init();
$msg = '';
if(isset($_GET['msg']))
{
    $msg = $_GET['msg'];    
    if($msg=="err")
    {
        $msg = "<span style='color:red;' id='read'>!Error,Please try again.</span>";
    }
    else
    {
        $msg = "<span style='color:green;' id='read'>User created succesfully.</span>";
    }
}
?>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYSQLI_INIT_STMT EXAMPLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <link href='css/select2.min.css' rel='stylesheet' type='text/css'>
  </head>
<style type="text/css">
    @import url(https://unpkg.com/@webpixels/css@1.1.5/dist/index.css);
    @import url("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.4.0/font/bootstrap-icons.min.css");
    .select2-container 
    {
        max-width: 100%;
    }
    .select2-container .select2-selection--single
    {
        height:33.5px !important;
        padding-top: 2px;
    }
    .select2-container--default .select2-selection--single
    {
        border: 1px solid #5c60f5 !important;
    }
</style>
<body>
    <!-- Banner -->
    <a href="www.seplcables.com" class="btn w-full btn-primary text-truncate rounded-0 py-2 border-0 position-relative" style="z-index: 1000;">
        <strong>Welcome to demo of MYSQLI_INIT_STMT</strong>
    </a>

    <!-- Dashboard -->
    <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
        
        <!-- Main content -->
        <div class="h-screen flex-grow-1 overflow-y-lg-auto">
            <!-- Header -->
            <header class="bg-surface-primary border-bottom pt-6">
                <div class="container-fluid">
                    <div class="mb-npx">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-12 mb-4 mb-sm-0">
                                <!-- Title -->
                                <h1 class="h2 mb-0 ls-tight"><a href="userlist.php">Users</a></h1>
                            </div>
                            <div class="col-sm-4 col-12 mb-4 mb-sm-0 text-center fw-bold">
                                <span><?php echo $msg; ?></span>
                            </div>
                            <!-- Actions -->
                            <div class="col-sm-4 col-12 text-sm-end">
                                <div class="mx-n1">
                                    <a href="#" class="btn d-inline-flex btn-sm btn-neutral border-base mx-1">
                                       <span>View Category</span>
                                    </a>
                                    <a href="#" class="btn d-inline-flex btn-sm btn-primary mx-1"  data-bs-toggle="modal" data-bs-target="#addmodal">
                                        <span class=" pe-2">
                                            <i class="bi bi-plus"></i>
                                        </span>
                                        <span>Create</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Nav -->
                        <ul class="nav nav-tabs mt-4 overflow-x border-0">
                            <li class="nav-item ">
                                <a href="#" class="nav-link active">All</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link font-regular">Active List</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link font-regular">Inactive List</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <!-- Main -->
            <main class="py-6 bg-surface-secondary">
                <div class="container-fluid">
                    <!-- Card stats -->
                    
                    <div class="card shadow border-0 mb-7">
                        <div class="table-responsive" id="changeContent">
                            <table class="table table-hover table-nowrap">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Password</th>
                                        <th scope="col">Staus</th>
                                        <th scope="col">Date & Time</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "select * from user";
                                        if($stmt->prepare($sql))
                                        {
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc())
                                            {                                            
                                    ?>
                                    <tr>
                                        <td>
                                            <img alt="..." src="https://images.unsplash.com/photo-1502823403499-6ccfcf4fb453?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80" class="avatar avatar-sm rounded-circle me-2">                                            
                                        </td>
                                        <td>
                                            <?php echo $row['Username']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['Password']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['Status']; ?>
                                        </td>
                                        <td>
                                            <?php echo date("d-M-Y (D)",strtotime($row['TimeStamp'])); ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-neutral edit" data-val="<?php echo $row['Userid']; ?>"  data-bs-toggle="modal" data-bs-target="#editmodal">
                                                <span class=" pe-2"><i class="bi bi-pencil"></i></span>
                                                <span>Edit</span>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-square btn-neutral text-danger-hover">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } } ?>                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>        
  </body>
</html>
<script src='js/select2.min.js' type='text/javascript'></script>
<script type="text/javascript">
    
    $(document).ready( function ()
    {
        $("#userroleidadd").select2({
            dropdownParent: $('#addmodal')
        });

    });

    $(document).on('click','.edit',function()
    {   
        var id = $(this).data('val');
        $.ajax(
        {
            url:"script/login_db.php?status=2",
            type:'post',
            data:{id:id},
            success: function(data)
            {
                $('.modal-body-edit').html(data);
            }
        });        
    });
</script>

<!----------------------------------------------------Add user modal------------------------------------------>
<div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="addmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addmodalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="script/login_db.php?status=1" method="post" id="adduser">
            <div>
                <label for="exampleInputEmail1" class="form-label">Username</label>
                <input type="text" name="Username" class="form-control border border-primary" id="exampleInputEmail1" aria-describedby="emailHelp" autocomplete="off" required>
            </div>
            <div>
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="Password" class="form-control border border-primary" id="exampleInputPassword1" autocomplete="off" required>
            </div> 
            <div>
                <label for="exampleInputLocation1" class="form-label">Location</label>
                <input type="text" name="location" class="form-control border border-primary" id="exampleInputLocation1" autocomplete="off" required>
            </div> 
            <div>
                <label for="exampleInputPassword1" class="form-label">Role</label><br/>
                <select class="form-select form-control border border-primary" name="userroleid[]" id="userroleidadd" multiple style="width: 100%;" required>
                    <option value="">Select Roles</option>
                    <?php
                        $sql = "select * from role order by Role asc";
                        if($stmt->prepare($sql))
                        {
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc())
                            {  
                    ?>
                    <option value="<?php echo $row['RoleID'] ?>"><?php echo $row['Role']; ?></option>
                    <?php } } ?>
                </select>
            </div>                
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="adduser">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-----------------------------------------------------------end---------------------------------------------->


<!----------------------------------------------------edit user modal------------------------------------------>
<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editmodalLabel">EDIT User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body-edit p-5">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="edituser">Update</button>
      </div>
    </div>
  </div>
</div>
<!-----------------------------------------------------------end---------------------------------------------->
