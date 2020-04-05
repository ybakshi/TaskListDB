<?php 
    session_start();

    $time = $_SERVER['REQUEST_TIME'];

    /**
    * for a 30 minute timeout, specified in seconds
    */
    $timeout_duration = 7200;
    
    //See if the user is valid
    if (strcmp($_SESSION["user"], "auth") !== 0) {
        header("Location: login.php");
        exit();
    }
        
	$errors = "";

	// connect to database heroku DB
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $conn = new mysqli($server, $username, $password, $db);

	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {

		if (empty($_POST['task'])) {
			$errors = "You must fill in the task";
		}else{
			$task = $_POST['task'];
            $Owner = $_POST['Owner'];
			$query = "INSERT INTO tasks (task,Owner,task_done) VALUES ('$task','$Owner',0)";
			mysqli_query($conn, $query);
			header('location: index.php');
		}
	}	

	// delete task
	if (isset($_GET['del_task'])) {
		$id = $_GET['del_task'];
		mysqli_query($conn, "DELETE FROM tasks WHERE task=".$id);
        header('location: index.php');
	}
    
    // Update task
	if (isset($_GET['upd_task'])) {
        $updt_task = $_GET['upd_task'];
        $task_done = $_SESSION["checked"];
        $todaydate = date("Y-m-d");
        $sqlDate = date('Y-m-d', strtotime($todaydate));
        mysqli_query($conn, "UPDATE tasks SET task_done='$task_done', DoC='$sqlDate' WHERE task=$updt_task");
        header('location: index.php');
	}  

?>
<!DOCTYPE html>
<html>

<head>
	<title>ToDo List Application PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

	<div class="heading">
		<h2 style="font-style: 'Hervetica';">Welcome to Brainizen Task List Board!</h2>
	</div>

	<form method="post" action="index.php" class="input_form" style="align-content: center;">
		<?php if (isset($errors)) { ?>
			<p><?php echo $errors; ?></p>
		<?php } ?>
		<label for="task_label">Task:</label>
        <input type="text" name="task" class="task_input">
        <label for="Owner_label">Owner:</label>
        <input type="text" name="Owner" class="Owner_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Task</button>
	</form>

	<table>
		<thead>
			<tr>
				<th style="width: 5%; text-align: left;">N</th>
				<th style="width: 55%; text-align: left;">Tasks</th>
                <th style="width: 10%; text-align: left;">Owner</th>
                <th style="width: 10%; text-align: left;">DoC</th>
				<th style="width: 20%; text-align: center;">Action</th>
			</tr>
		</thead>

		<tbody>
			<?php 
                // select all tasks if page is visited or refreshed
                $tasks = mysqli_query($conn, "SELECT * FROM tasks");

                $i = 1; while ($row = mysqli_fetch_array($tasks)) { 
                    //Print only "Open" tasks
                    if ($row['task_done'] == '0'){
            ?>
                        <tr>
                            <td style="width: 10%; text-align: left;"> <?php echo $i; ?> </td>
                            <td class="task"> <?php echo $row['task']; ?> </td>
                            <td class="Owner"> <?php echo $row['Owner']; ?> </td>
                            <td class="Owner"> <?php echo $row['DoC']; ?> </td>
                            <td class="delete"> 
                                <input type="checkbox" id="task_done" name="task_done" onclick="<?php if(this.checked != true){$_SESSION["checked"]="1";}else{$_SESSION["checked"]="0";} ?>"/>
                                <a href="index.php?del_task='<?php echo $row['task']; ?>'">x</a> 
                                <a href="index.php?upd_task='<?php echo $row['task']; ?>'">Update</a>
                            </td>
                        </tr>
            <?php
                    }else{}
                    $i++;
                }
            ?>
                        <tr style="width: 5%; text-align: center;"><td colspan="5" style="font-size: 19px; color: #6B8E23;"><b>Completed Tasks</b></td></tr>
            <?php
                // select all tasks if page is visited or refreshed
                $tasks = mysqli_query($conn, "SELECT * FROM tasks");
            
                $i = 1; while ($row = mysqli_fetch_array($tasks)) { 
                    //Print only "Completed" tasks
                    if ($row['task_done'] == '1'){
            ?>
                    <tr>
                        <td style="width: 10%; text-align: left;"> <?php echo $i; ?> </td>
                        <td class="task"> <?php echo $row['task']; ?> </td>
                        <td class="Owner"> <?php echo $row['Owner']; ?> </td>
                        <td class="Owner"> <?php echo $row['DoC']; ?> </td>
                        <td class="delete"> 
                            <input type="checkbox" id="task_done" name="task_done" value="<?php echo $row['task_done']; ?>" checked='checked' onclick="<?php if(this.checked == true){$_SESSION["checked"]="0";}else{$_SESSION["checked"]="1";} ?>"/>
                            <a href="index.php?del_task='<?php echo $row['task']; ?>'">x</a>
                            <a href="index.php?upd_task='<?php echo $row['task']; ?>'">Update</a>
                        </td>
                    </tr>
            <?php
                    }else{}
		          $i++; 
                } 
            ?>		
		</tbody>
	</table>
</body>
</html>