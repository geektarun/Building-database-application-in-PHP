<?php

if ( !isset($_GET['name']) || strlen($_GET['name']) < 1 ) {
    die('Name parameter missing');
}

if ( strpos($_GET['name'], '@') === false ) {
    die('Name parameter is wrong');
}


if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

try 
{
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'fred', 'zap');
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    die();
}

$name = htmlentities($_GET['name']);

$status = false;  
$status_color = 'red';

if (isset($_POST['mileage']) && isset($_POST['year']) && isset($_POST['make'])) 
{
    if ( !is_numeric($_POST['mileage']) || !is_numeric($_POST['year']) ) 
    {
        $status = "Mileage and year must be numeric";
    } 
    else if (strlen($_POST['make']) < 1)
    {
        $status = "Make is required";
    }
    else 
    {
        $make = htmlentities($_POST['make']);
        $year = htmlentities($_POST['year']);
        $mileage = htmlentities($_POST['mileage']);

        $stmt = $pdo->prepare("
            INSERT INTO autos (make, year, mileage) 
            VALUES (:make, :year, :mileage)
        ");

        $stmt->execute([
            ':make' => $make, 
            ':year' => $year,
            ':mileage' => $mileage,
        ]);

        $status = 'Record inserted';
        $status_color = 'green';
    }
}

$autos = [];
$all_autos = $pdo->query("SELECT * FROM autos");

while ( $row = $all_autos->fetch(PDO::FETCH_OBJ) ) 
{
    $autos[] = $row;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Autos 537a67c4</title>
    </head>
    <body>
        <div>
            <h1>Tracking Autos for <?php echo $name; ?></h1>
            <?php
                if ( $status !== false ) 
                {
                    echo(
                        '<p>'.
                            htmlentities($status).
                        "</p>\n"
                    );
                }
            ?>
            <form method="post">
                <div>
                    <label for="make">Make:</label>
                    <div>
                        <input type="text" name="make" id="make">
                    </div>
                </div>
                <div>
                    <label for="year">Year:</label>
                    <div>
                        <input type="text" name="year" id="year">
                    </div>
                </div>
                <div>
                    <label for="mileage">Mileage:</label>
                    <div>
                        <input type="text" name="mileage" id="mileage">
                    </div>
                </div>
                <div>
                    <div>
                        <input type="submit" value="Add">
                        <input type="submit" name="logout" value="Cancel">
                    </div>
                </div>
            </form>

            <?php if(!empty($autos)) : ?>
                <h2>Automobiles</h2>
                <ul>
                    <?php foreach($autos as $auto) : ?>
                        <li>
                            <?php echo $auto->year; ?> <?php echo $auto->make; ?> <?php echo $auto->mileage; ?> 
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </div>
    </body>
</html>
