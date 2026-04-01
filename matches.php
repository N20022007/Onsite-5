<?php
$pageTitle = "Matches Management";
require 'includes/db.php'; // ملف الاتصال بقاعدة البيانات
include 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_match'])) {

    try {
        $pdo->beginTransaction();

        $team1 = $_POST['team1'];
        $team2 = $_POST['team2'];
        $team1_goals = (int)$_POST['team1_goals'];
        $team2_goals = (int)$_POST['team2_goals'];
        $match_date = $_POST['match_date'];

        // منع اختيار نفس الفريق
        if ($team1 == $team2) {
            throw new Exception("You cannot select the same team twice.");
        }

        // إدخال المباراة
        $stmt = $pdo->prepare("
            INSERT INTO matches (team1_id, team2_id, team1_goals, team2_goals, match_date) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([$team1, $team2, $team1_goals, $team2_goals, $match_date]);

        // تحديث إحصائيات الفريقين
        updateTeamStats($pdo, $team1, $team1_goals, $team2_goals);
        updateTeamStats($pdo, $team2, $team2_goals, $team1_goals);

        $pdo->commit();

        echo "<div class='alert alert-success'>Match added successfully!</div>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }
}

// Function to update team statistics
function updateTeamStats($pdo, $teamId, $goalsFor, $goalsAgainst) {

    $points = 0;

    if ($goalsFor > $goalsAgainst) {
        $points = 3;
    } elseif ($goalsFor == $goalsAgainst) {
        $points = 1;
    }

    $stmt = $pdo->prepare("
        UPDATE teams 
        SET points = points + ?, 
            goals_scored = goals_scored + ?, 
            goals_conceded = goals_conceded + ?
        WHERE id = ?
    ");

    $stmt->execute([$points, $goalsFor, $goalsAgainst, $teamId]);
}

// Get teams for dropdown
$teams = $pdo->query("SELECT * FROM teams ORDER BY class_name")->fetchAll();
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h2>Add Match Result</h2>
    </div>
    <div class="card-body">
        <form method="post" class="row g-3">

            <div class="col-md-3">
                <select class="form-select" name="team1" required>
                    <option value="">Select Team 1</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= $team['id'] ?>">
                            <?= htmlspecialchars($team['class_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-1">
                <input type="number" class="form-control" name="team1_goals" min="0" required>
            </div>

            <div class="col-md-1">
                <input type="number" class="form-control" name="team2_goals" min="0" required>
            </div>

            <div class="col-md-3">
                <select class="form-select" name="team2" required>
                    <option value="">Select Team 2</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= $team['id'] ?>">
                            <?= htmlspecialchars($team['class_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <input type="datetime-local" class="form-control" name="match_date" required>
            </div>

            <div class="col-md-1">
                <button type="submit" name="add_match" class="btn btn-success w-100">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

<h3 class="mb-3">Match History</h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>Match</th>
            <th>Result</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $matches = $pdo->query("
            SELECT m.*, 
                   t1.class_name AS team1_name,
                   t2.class_name AS team2_name
            FROM matches m
            JOIN teams t1 ON m.team1_id = t1.id
            JOIN teams t2 ON m.team2_id = t2.id
            ORDER BY m.match_date DESC
        ")->fetchAll();

        foreach ($matches as $match):
        ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($match['match_date'])) ?></td>
                <td>
                    <?= htmlspecialchars($match['team1_name']) ?> 
                    vs 
                    <?= htmlspecialchars($match['team2_name']) ?>
                </td>
                <td>
                    <?= $match['team1_goals'] ?> - <?= $match['team2_goals'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>