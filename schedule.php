<?php  
$pageTitle = "Match Schedule"; 
include 'includes/header.php';  
// Get upcoming and past matches 
$upcomingMatches = $pdo->query(" SELECT m.*, t1.class_name as team1_name, t2.class_name as team2_name FROM matches m 
JOIN teams t1 ON m.team1_id = t1.id JOIN teams t2 ON m.team2_id = t2.id WHERE m.match_date > NOW() ORDER BY m.match_date ASC ")->fetchAll(); 
$pastMatches = $pdo->query(" SELECT m.*, t1.class_name as team1_name, t2.class_name as team2_name FROM matches m JOIN teams t1 ON m.team1_id = t1.id JOIN teams t2 ON m.team2_id = t2.id WHERE m.match_date <= NOW() ORDER BY m.match_date DESC LIMIT 5 ")->fetchAll(); ?> 

<div class="card mb-4"> 
    <div class="card-header bg-primary text-white"> 
        <h2>Match Schedule</h2> 
    </div> 
    <div class="card-body"> 
        <ul class="nav nav-tabs" id="scheduleTabs" role="tablist"> 
            <li class="nav-item" role="presentation">
                <button class="navlink active" id="upcoming-tab" data-bs-toggle="tab"  data-bs-target="#upcoming" type="button" role="tab"> 
                    Upcoming Matches 
                </button> 
            </li> 
            <li class="nav-item" role="presentation"> 
                <button class="nav-link" id="past-tab" data-bs-toggle="tab"  data-bs-target="#past" type="button" role="tab"> 
                    Recent Results 
                </button> 
            </li> 
        </ul> 
         
    <div class="tab-content p-3 border border-top-0 rounded-bottom"> 
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel"> 
            <?php if (count($upcomingMatches) > 0): ?> 
                <div class="list-group"> 
                    <?php foreach ($upcomingMatches as $match): ?> 
                        <div class="list-group-item"> 
                            <div class="d-flex w-100 justify-content-between"> 
                                <h5 class="mb-1"><?= htmlspecialchars($match['team1_name']) ?> vs <?= htmlspecialchars($match['team2_name']) ?></h5> 
                                <small><?= date('D, M j', strtotime($match['match_date'])) ?></small> 
                            </div> 
                            <p class="mb-1"> 
                                <span class="badge bg-info text-dark"> 
                                    <?= date('g:i A', strtotime($match['match_date'])) ?>
                                </span> 
                                <span class="badge bg-secondary"> 
                                    Field #1 
                                </span> 
                            </p> 
                        </div> 
                        <?php endforeach; ?> 
                    </div> 
                <?php else: ?> 
                    <div class="alert alert-info">No upcoming matches scheduled yet.</div> 
                <?php endif; ?> 
            </div> 
             
            <div class="tab-pane fade" id="past" role="tabpanel"> 
                <?php if (count($pastMatches) > 0): ?>
                    <div class="listgroup"> 
                        <?php foreach ($pastMatches as $match): ?> 
                        <div class="list-group-item"> 
                            <div class="d-flex w-100 justify-content-between"> 
                                <h5 class="mb-1"> 
                                    <?= htmlspecialchars($match['team1_name']) ?>  
                                    <span class="badge bg-primary"><?= $match['team1_goals'] ?></span> 
                                    -  
                                    <span class="badge bg-primary"><?= $match['team2_goals'] ?></span> 
                                    <?= htmlspecialchars($match['team2_name']) ?> 
                                </h5> 
                                <small><?= date('M j', strtotime($match['match_date'])) ?></small> 
                            </div> 
                            <p class="mb-1"> 
                                <?php 
                                $winner = ($match['team1_goals'] > $match['team2_goals']) ? $match['team1_name'] :  
                                         (($match['team2_goals'] > $match['team1_goals']) ? $match['team2_name'] : null); 
                                ?> 
                                <?php if ($winner): ?>
                                    <span class="badge bg-success"><?= htmlspecialchars($winner) ?> won</span> 
                                <?php else: ?> 
                                    <span class="badge bg-warning textdark">Draw</span> 
                                <?php endif; ?> 
                            </p> 
                        </div> 
                        <?php endforeach; ?> 
                    </div> 
                <?php else: ?> 
                    <div class="alert alert-info">No match results available yet.</div> 
                <?php endif; ?> 
            </div> 
        </div> 
    </div> 
</div> 
 
<?php include 'includes/footer.php'; ?>