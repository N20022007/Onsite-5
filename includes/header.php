    <?php include 'includes/db.php'; ?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initialscale=1.0"> 
    <title><?= $pageTitle ?? 'Class Tournament' ?></title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
</head> 
<body> 
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4"> 
    <div class="container"> 
            <a class="navbar-brand" href="index.php">Class Tournament </a> 
        <div class="collapse navbar-collapse"> 
            <ul class="navbar-nav"> 
                <li class="nav-item"><a class="nav-link" href="teams.php">Teams</a></li> 
                <li class="nav-item"><a class="nav-link" href="matches.php">Matches</a></li> 
                <li class="nav-item"><a class="nav-link" href="standings.php">Standings</a></li> 
                <li class="nav-item"><a class="nav-link" href="schedule.php">Schedule</a></li> 
            </ul> 
        </div> 
    </div> 
</nav> 
<div class="container">