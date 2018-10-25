<?php
//application functions
function getProjectList()
{
    include "connection.php";

    try {
        return $db->query('SELECT project_id, title, category FROM projects');
    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return [];
    }
}

function addProject($title, $category, $projectId = null)
{
    include "connection.php";

    if (!empty($projectId)) {
        $query = "UPDATE projects SET title = ?, category = ? WHERE project_id = ?";
    } else {
        $query = "INSERT INTO projects(title, category) VALUES(?, ?)";
    }

    try {

        $results = $db->prepare($query);

        $results->bindValue(1, $title, PDO::PARAM_STR);
        $results->bindValue(2, $category, PDO::PARAM_STR);

        if ($projectId) {
            $results->bindValue(3, $projectId, PDO::PARAM_INT);
        }

        $results->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return false;
    }

    return true;
}

function getTaskList($filter = null)
{
    include "connection.php";

    $query = "SELECT tasks.*, projects.title AS project FROM tasks"
        ." JOIN projects ON tasks.project_id = projects.project_id";

    $where = '';

    if (is_array($filter)) {
        switch ($filter[0]) {
            case 'project':
                $where = " WHERE projects.project_id = ?";
                break;
            case 'category':
                $where = " WHERE category = ?";
                break;
            case 'date':
                $where = " WHERE date >= ? AND date <= ?";
                break;
        }
    }

    $orderBy = ' ORDER BY date DESC';

    if ($filter) {
        $orderBy = ' ORDER BY projects.title ASC, date DESC';
    }

    try {
        $results = $db->prepare($query . $where . $orderBy);

        if (is_array($filter)) {
            $results->bindValue(1, $filter[1]);

            if ($filter[0] === 'date') {
                $results->bindValue(2, $filter[2], PDO::PARAM_STR);
            }
        }

        $results->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return [];
    }

    return $results->fetchAll(PDO::FETCH_ASSOC);
}

function addTask($projectId, $title, $date, $time, $taskId = null)
{
    include "connection.php";

    if (!empty($taskId)) {
        $query = "UPDATE tasks SET project_id = ?, title = ?, date = ?, time = ? WHERE task_id = ?";
    } else {
        $query = "INSERT INTO tasks(project_id, title, date, time)"
            ." VALUES(?, ?, ?, ?)";
    }

    try {

        $results = $db->prepare($query);

        $results->bindValue(1, $projectId, PDO::PARAM_INT);
        $results->bindValue(2, $title, PDO::PARAM_STR);
        $results->bindValue(3, $date, PDO::PARAM_STR);
        $results->bindValue(4, $time, PDO::PARAM_INT);

        if (!empty($taskId)) {
            $results->bindValue(5, $taskId, PDO::PARAM_INT);
        }

        $results->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return false;
    }

    return true;
}

function getProject($projectId)
{
    include "connection.php";

    try {
        $query = 'SELECT project_id, title, category FROM projects';
        $where = " WHERE project_id = ?";

        $result = $db->prepare($query.$where);

        $result->bindValue(1, $projectId, PDO::PARAM_INT);
        $result->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return [];
    }

    return $result->fetch();
}

function getTask($taskId)
{
    include "connection.php";

    try {
        $query = 'SELECT task_id, title, date, time, project_id FROM tasks';
        $where = " WHERE task_id = ?";

        $result = $db->prepare($query.$where);

        $result->bindValue(1, $taskId, PDO::PARAM_INT);
        $result->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return [];
    }

    return $result->fetch();
}

function deleteTask($taskId)
{
    include "connection.php";

    try {
        $query = "DELETE FROM tasks WHERE task_id = ?";

        $result = $db->prepare($query);

        $result->bindValue(1, $taskId, PDO::PARAM_INT);
        $result->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return [];
    }

    return true;
}
