<?php
require '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
       
    </style>
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">

        <!-- Header Section -->
        <div class="task-header">
            <h2 class="mb-0"><i class="fa-solid fa-tasks"></i> Task Manager</h2>
            <p>Organize your daily tasks efficiently!</p>
        </div>

        <!-- Message Display -->
        <div>
            <p id="taskMsg"></p>
        </div>

        <!-- Add Task Section -->
        <div class="task-container">
            <h4 class="mb-3"><i class="fa-solid fa-plus-circle"></i> Add a New Task</h4>
            <form method="post" id="taskForm">
                <div class="input-group mb-3">
                    <input type="text" id="taskTitle" name="taskTitle" class="form-control" placeholder="Enter task title..." required>
                    <input type="text" id="taskDescription" name="taskDescription" class="form-control" placeholder="Enter task description..." required>
                    <button type="submit" id="addTaskBtn" name="addTaskBtn" class="btn btn-add-task"><i class="fas fa-plus"></i> Add Task</button>
                </div>
            </form>
        </div>

        <!-- Task List Section -->
        <div class="table-container mt-4">
            <h4 class="mb-3"><i class="fa-solid fa-list"></i> Your Tasks</h4>
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="taskTable">
                    <?php foreach ($tasks as $task) : ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title']); ?></td>
                            <td><?= htmlspecialchars($task['description']); ?></td>
                            <td>
                                <?php if ($task['status'] == 'completed') : ?>
                                    <span class="badge bg-success status-badge">Completed</span>
                                <?php else : ?>
                                    <span class="badge bg-warning text-dark status-badge">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="../actions/edit_task.php?id=<?= $task['id']; ?>" class="btn btn-sm btn-primary btn-task"><i class="fa-solid fa-edit"></i> Edit</a>
                                <a href="../actions/delete_task.php?id=<?= $task['id']; ?>" class="btn btn-sm btn-danger btn-task"><i class="fa-solid fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>

</html>


<script type="text/javascript">
    $(document).ready(function() {
        // Function to load tasks
        function loadTask() {
            $.ajax({
                url: "../actions/show.php", // Ensure correct path
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        let taskList = "";
                        $.each(data, function(index, task) {
                            taskList += `<tr id="taskRow-${task.id}"> 
                            <td class="align-middle">${task.title}</td>
<td class="align-middle">${task.description}</td>
<td class="align-middle">
    <select class="form-select task-status px-3 py-2 border-0 shadow-sm rounded-3" 
            data-id="${task.id}" 
            style="background: #f8f9fa; cursor: pointer;">
        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>🟡 Pending</option>
        <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>✅ Completed</option>
    </select>
</td>
<td class="align-middle d-flex justify-content-center">
    <a href="edit_task.php?task_id=${task.id}" class="edit-task me-3" style="text-decoration: none;">
        <i class="fa-solid fa-pen-to-square text-primary fs-4" style="transition: 0.3s;"></i>
    </a>
    <button class="delete-task border-0 bg-transparent" data-id="${task.id}" style="cursor: pointer;">
        <i class="fa-solid fa-trash text-danger fs-4" style="transition: 0.3s;"></i>
    </button>
</td>

                        </tr>`;
                        });

                        $("#taskTable").html(taskList); // ✅ Use taskList here, NOT "data"
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error loading tasks: " + xhr.responseText);
                }
            });
        }

        // Update task status on change
        $(document).on("change", ".task-status", function() {
            let task_id = $(this).data("id");
            let new_status = $(this).val();

            $.ajax({
                url: "../actions/update_status.php",
                type: "POST",
                data: {
                    task_id: task_id,
                    status: new_status
                },
                success: function(response) {
                    if (response.trim() === "success") {
                        alert("Task status updated successfully!");
                        loadTask(); // Refresh task list
                    } else {
                        alert("Error updating status: " + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX Error: " + xhr.responseText);
                }
            });
        });

        // Handle delete button click
        $(document).on("click", ".delete-task", function() {
            let taskId = $(this).data("id");

            if (confirm("Are you sure you want to delete this task?")) {
                $.ajax({
                    url: "../actions/delete.php",
                    type: "POST",
                    data: {
                        id: taskId
                    },
                    success: function(response) {
                        if (response.trim() === "success") {
                            $("#taskRow-" + taskId).fadeOut(); // Remove the row visually
                        } else {
                            alert("Error deleting task: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX Error: " + xhr.responseText);
                    }
                });
            }
        });

        // Handle form submission
        $("#taskForm").submit(function(e) {
            e.preventDefault(); // Prevent page reload

            $.ajax({
                method: "POST",
                url: "../actions/add.php", // Ensure correct path
                data: $(this).serialize(),
                dataType: "text",
                success: function(response) {
                    if (response.trim() === "success") {
                        $('#taskMsg').html('<span class="text-success">Task added successfully!</span>');
                        $("#taskTitle").val('');
                        $("#taskDescription").val('');
                        loadTask(); // Reload task list
                    } else {
                        $('#taskMsg').html('<span class="text-danger">Error: ' + response + '</span>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#taskMsg').html('<span class="text-danger">AJAX Error: ' + xhr.responseText + '</span>');
                }
            });
        });

        // Load tasks when the page loads
        loadTask();
    });
</script>