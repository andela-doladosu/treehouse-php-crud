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

function addProject($title, $category)
{
    include "connection.php";

    $query = "INSERT INTO projects(title, category) VALUES(?, ?)";

    try {

        $results = $db->prepare($query);

        $results->bindValue(1, $title, PDO::PARAM_STR);
        $results->bindValue(2, $category, PDO::PARAM_STR);

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

    $orderBy = ' ORDER BY date DESC';

    if ($filter) {
        $orderBy = ' ORDER BY projects.title ASC, date DESC';
    }

    try {

        $results = $db->prepare($query . $orderBy);
        $results->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return [];
    }

    return $results->fetchAll(PDO::FETCH_ASSOC);
}

function addTask($project_id, $title, $date, $time)
{
    include "connection.php";

    $query = "INSERT INTO tasks(project_id, title, date, time)"
        ." VALUES(?, ?, ?, ?)";

    try {

        $results = $db->prepare($query);

        $results->bindValue(1, $project_id, PDO::PARAM_INT);
        $results->bindValue(2, $title, PDO::PARAM_STR);
        $results->bindValue(3, $date, PDO::PARAM_STR);
        $results->bindValue(4, $time, PDO::PARAM_INT);

        $results->execute();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage()."<br/>";
        return false;
    }

    return true;
}
