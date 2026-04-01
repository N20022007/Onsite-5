<?php  
$pageTitle = "Tournament Standings"; 
include 'includes/header.php';  
// Calculate additional stats for each team 
$teams = $pdo->query("SELECT  t.*, (t.goals_scored - t.goals_conceded) AS goal_difference, (SELECT COUNT(*) FROM matches m  WHERE (m.team1_id = t.id OR m.team2_id = t.id)) AS matches_played, (SELECT COUNT(*) FROM matches m WHERE ((m.team1_id = t.id AND m.team1_goals > m.team2_goals) OR (m.team2_id = t.id AND m.team2_goals > m.team1_goals))) AS wins, (SELECT COUNT(*) FROM matches m WHERE ((m.team1_id = t.id OR m.team2_id = t.id) AND  m.team1_goals = m.team2_goals)) AS draws FROM teams t ORDER BY t.points DESC, goal_difference DESC, t.goals_scored DESC ")->fetchAll(); ?> 
<div class="card mb-4"> 
    <div class="card-header bg-primary text-white"> 
        <h2>Tournament Standings</h2> 
    </div> 
    <div class="card-body"> 
        <div class="table-responsive"> 
            <table class="table table-striped table-hover"> 
                <thead class="table-dark"> 
                    <tr> 
                        <th>#</th> 
                        <th>Team</th> 
                        <th>P</th> 
                        <th>W</th>
                        <th>D</th> 
                        <th>L</th> 
                        <th>GF</th> 
                        <th>GA</th> 
                        <th>GD</th> 
                        <th>Pts</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php foreach ($teams as $index => $team): ?> 
                    <tr> 
                        <td><?= $index + 1 ?></td> 
                        <td><?= htmlspecialchars($team['class_name']) ?></td> 
                        <td><?= $team['matches_played'] ?></td> 
                        <td><?= $team['wins'] ?></td> 
                        <td><?= $team['draws'] ?></td> 
                        <td><?= $team['matches_played'] - $team['wins'] - $team['draws'] ?></td> 
                        <td><?= $team['goals_scored'] ?></td> 
                        <td><?= $team['goals_conceded'] ?></td> 
                        <td><?= $team['goal_difference'] ?></td> 
                        <td><strong><?= $team['points'] ?></strong></td> 
                    </tr> 
                    <?php endforeach; ?>
                </tbody> 
            </table> 
        </div> 
         
        <div class="mt-4"> 
            <h4>Legend:</h4> 
            <ul class="list-inline"> 
                <li class="list-inline-item"><strong>P:</strong> Matches Played</li> 
                <li class="list-inline-item"><strong>W:</strong> Wins</li> 
                <li class="list-inline-item"><strong>D:</strong> Draws</li> 
                <li class="list-inline-item"><strong>L:</strong> Losses</li> 
                <li class="list-inline-item"><strong>GF:</strong> Goals For</li> 
                <li class="list-inline-item"><strong>GA:</strong> Goals Against<li> 
                <li class="list-inline-item"><strong>GD:</strong>Goal Difference<li> 
                <li class="list-inline-item"><strong>Pts:</strong> Points</li> 
            </ul> 
        </div> 
    </div> 
</div> 
 
<?php include 'includes/footer.php'; ?>