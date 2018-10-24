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
