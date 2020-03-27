<?php 
	
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

//		if (empty($_POST['task'])) {
//			$errors = "You must fill in the task";
//		}else{
			$task = $_POST['task'];
            $Owner = $_POST['Owner'];
			$query = "INSERT INTO tasks (task,Owner,task_done) VALUES ('$task','$Owner',0)";
			mysqli_query($conn, $query);
			header('location: index.php');
//		}
	}	

//    if (isset($_POST['update'])){
//        $task_done = $_POST['task_done'];
//        if ($task_done != 1){
//            $task_done = 0;
//        }
//        $query1 = "UPDATE tasks SET task_done='$task_done' WHERE task='$task'";
//        mysqli_query($conn, $query1);
//        header('location: index.php');
//    }

	// delete task
	if (isset($_GET['del_task'])) {
		$id = $_GET['del_task'];

		mysqli_query($conn, "DELETE FROM tasks WHERE task=".$id);
        header('location: index.php');
	}
echo "taskdone=".$task_done;
    // Update task
	if (isset($_GET['task_done'])) {
		$task_done = $_GET['task_done'];
        $updt_task = $_GET['upd_task'];
        echo "taskdone=".$task_done;
		mysqli_query($conn, "UPDATE tasks SET task_done='$task_done' WHERE task=$updt_task");
        header('location: index.php');
	}

//    if (isset($_GET['task_done'])) {
//		$task_done = $_GET['task_done'];
//    }

    //echo $tasks;
    //while ($taskrow = mysqli_fetch_assoc($tasks))
    //{
    //    echo $taskrow;
    //}
?>
<!DOCTYPE html>
<html>

<head>
	<title>ToDo List Application PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

	<div class="heading">
		<h2 style="font-style: 'Hervetica';">ToDo List Application PHP and MySQL database</h2>
	</div>


	<form method="post" action="index.php" class="input_form">
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
				<th style="width: 10%; text-align: left;">N</th>
				<th style="width: 50%; text-align: left;">Tasks</th>
                <th style="width: 20%; text-align: left;">Owner</th>
				<th style="width: 20%; text-align: center;">Action</th>
			</tr>
		</thead>

		<tbody>
			<?php 
                // select all tasks if page is visited or refreshed
                $tasks = mysqli_query($conn, "SELECT * FROM tasks");

                $i = 1; while ($row = mysqli_fetch_array($tasks)) { ?>
<!--                    <form method="post" action="index.php" class="input_form">-->
                    <tr>
                        <td style="width: 10%; text-align: left;"> <?php echo $i; ?> </td>
                        <td class="task"> <?php echo $row['task']; ?> </td>
                        <td class="Owner"> <?php echo $row['Owner']; ?> </td>
                        <td class="delete"> 
                            <input type="checkbox" id="task_done" name="task_done" value="<?php echo $row['task_done']; ?>"<?php
                                if($row['task_done'] == '1'){
                                     echo " checked='checked'";
                                }
                                else {}
                                echo "/>"
                                ?>
                            <a href="index.php?del_task='<?php echo $row['task']; ?>'">x</a> 
                            <a href="index.php?upd_task='<?php echo $row['task']; ?>'&task_done='<?php echo $row['task_done']; ?>'">Update</a>
<!--                            <button type="update" name="update" id="upd_btn" class="add_btn">Update</button>-->
                        </td>
                    </tr>
<!--                    <form method="post" action="index.php" class="input_form">-->
		  <?php $i++; } ?>		
		</tbody>
	</table>
</body>
</html>