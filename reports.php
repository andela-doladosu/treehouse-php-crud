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
            <h1 class='actions-header'>Report on <?php
            if (!is_array($filter)) {
                echo "All tasks by Project";
            } else {
                echo ucwords($filter[0]) . ": ";
                switch ($filter[0]) {
                    case 'project':
                        $project = getProject($filter[1]);
                        echo $project['title'];
                        break;
                    case 'category':
                        echo $filter[1];
                        break;
                    case 'date':
                        echo "$filter[1] - $filter[2]";
                        break;
                }
            }?></h1>
            <form action="reports.php" method="get" class="form-container form-report">
                <label for="filter">Filter:</label>
                <select id="filter" name="filter">
                    <option value="">Select One</option>
                    <optgroup label="Project">
                    <?php
                    foreach (getProjectList() as $project) {
                        echo "<option value='project: " . $project['project_id'] . "'>";
                        echo $project['title'] . "</option>\n";
                    }
                    ?>
                    </optgroup>
                    <optgroup label="Category">
                        <option value="category:Billable">Billable</option>
                        <option value="category:Charity">Charity</option>
                        <option value="category:Personal">Personal</option>
                    </optgroup>
                    <optgroup label="Date">
                        <option value="date:<?php
                        echo date('m/d/Y', strtotime('-2, Sunday'));
                        echo ":";
                        echo date('m/d/Y', strtotime('-1, Saturday'));
                        ?>">Last Week</option>
                        <option value="date:<?php
                        echo date('m/d/Y', strtotime('-1, Sunday'));
                        echo ":";
                        echo date('m/d/Y');
                        ?>">This Week</option>
                        <option value="date:<?php
                        echo date('m/d/Y', strtotime('first day of last month'));
                        echo ":";
                        echo date('m/d/Y', strtotime('last day of last month'));
                        ?>">Last Month</option>
                        <option value="date:<?php
                        echo date('m/d/Y', strtotime('first day of this month'));
                        echo ":";
                        echo date('m/d/Y', strtotime('this month'));
                        ?>">This Month</option>
                    </optgroup>
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

