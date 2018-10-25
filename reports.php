<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = 'all';

if (!empty($_GET['filter'])) {
    $filter = explode(':', filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING));
}

include 'inc/header.php';
?>
<div class="col-container page-container">
    <div class="col col-70-md col-60-lg col-center">
        <div class="col-container">
            <h1 class='actions-header'>Reports</h1>
            <form action="reports.php" method="get" class="form-container form-report">
                <label for="filter">Filter:</label>
                <select id="filter" name="filter">
                    <option value="">Select One</option>
                    <?php
                    foreach (getProjectList() as $project) {
                        echo "<option value='project: " . $project['project_id'] . "'>";
                        echo $project['title'] . "</option>\n";
                    }
                    ?>
                </select>
                <input class="button" type="submit" name="" value="Run">
            </form>
        </div>
        <div class="section page">
            <div class="wrapper">
                <table>
                    <?php
                    $total = $project_id = $project_total = 0;
                    $tasks = getTaskList($filter);
                    foreach ($tasks as $task) {
                        if ($project_id !== $task['project_id']) {
                            $project_id = $task['project_id'];
                            echo "<thead>\n";
                            echo "<tr>\n";
                            echo "<th>". $task['project'] ."</th>";
                            echo "<th>Date</th>";
                            echo "<th>Time</th>";
                            echo "</tr>\n";
                            echo "</thead>\n";
                        }

                        $project_total += $task['time'];
                        $total += $task['time'];

                        echo "<tr>";
                        echo "<td>".$task['title']."</td>\n";
                        echo "<td>".$task['date']."</td>\n";
                        echo "<td>".$task['time']."</td>\n";
                        echo "</tr>";

                        if (next($tasks)['project_id'] != $task['project_id']) {
                            echo "<tr>\n";
                            echo "<th class='project-total-label' colspan='2'>Project Total</th>\n";
                            echo "<th class='project-total-number'>$project_total</th>\n";
                            echo "</tr>\n";
                            $project_total = 0;
                        }
                    }
                    ?>
                    <tr>
                        <th class='grand-total-label' colspan='2'>Grand Total</th>
                        <th class='grand-total-number'><?php echo $total;?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>

