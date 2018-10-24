<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = 'all';

include 'inc/header.php';
?>
<div class="col-container page-container">
    <div class="col col-70-md col-60-lg col-center">
        <div class="col-container">
            <h1 class='actions-header'>Reports</h1>
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

