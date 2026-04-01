<?php
$pageTitle = "Teams Management";
require 'includes/db.php';
include 'includes/header.php';

// إضافة فريق
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_team'])) {

    $className = trim($_POST['class_name']);

    if (!empty($className)) {

        $stmt = $pdo->prepare("
            INSERT INTO teams (class_name, points, goals_scored, goals_conceded) 
            VALUES (?, 0, 0, 0)
        ");

        try {
            $stmt->execute([$className]);
            echo "<div class='alert alert-success'>Team added successfully!</div>";
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Class already exists!</div>";
        }
    }
}

// حذف فريق
if (isset($_GET['delete'])) {

    $stmt = $pdo->prepare("DELETE FROM teams WHERE id = ?");
    $stmt->execute([$_GET['delete']]);

    echo "<div class='alert alert-warning'>Team deleted successfully!</div>";
}

// جلب الفرق
$teams = $pdo->query("
    SELECT * FROM teams 
    ORDER BY points DESC, (goals_scored - goals_conceded) DESC
")->fetchAll();
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h2>Teams Management</h2>
    </div>

    <div class="card-body">

        <form method="post" class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" class="form-control" name="class_name" placeholder="Class Name" required>
            </div>
            <div class="col-md-6">
                <button type="submit" name="add_team" class="btn btn-success">
                    Add Team
                </button>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Points</th>
                    <th>Goals For</th>
                    <th>Goals Against</th>
                    <th>Goal Difference</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($teams as $team): ?>
                    <tr>
                        <td><?= htmlspecialchars($team['class_name']) ?></td>
                        <td><?= $team['points'] ?></td>
                        <td><?= $team['goals_scored'] ?></td>
                        <td><?= $team['goals_conceded'] ?></td>
                        <td><?= $team['goals_scored'] - $team['goals_conceded'] ?></td>
                        <td>
                            <a href="?delete=<?= $team['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>
</div>

<?php include 'includes/footer.php'; ?>