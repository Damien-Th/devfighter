<?php
$title = "DevFighter";
$errer = null;
$success = null;
$loopEnd = false;
function chargerClasse($classe)
{
    require 'class/' . $classe . '.php';
}

spl_autoload_register('chargerClasse');

$bdd = new PDO('mysql:host=localhost:3306;dbname=test;charset=utf8', 'root', 'root');
$manager = new PlayerManager($bdd);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> <?= $title ?></title>
</head>

<body>

    <h1>Pour commencer la partie tu dois crée 2 personnages de chaque type</h1>

    <h2>Créé ton personnage</h2>

    <form action="" method="post">
        <input name="firstname" type="text" placeholder="FirstName">
        <input name="lastname" type="text" placeholder="LastName">
        <select name="selectPersonnage" id="perso-select">
            <option value="développeur">Développeur</option>
            <option value="chefDeProjet">Chef de projet</option>
            <option value="commercial">Commercial</option>
        </select>
        <br>
        <button type="submit">Create</button>
    </form>

    <?php

    // Verifie si firstname et lastname soit bien déclaré et superieur à 3 caractères
    if (!empty($_POST['firstname']) && !empty($_POST['lastname'])) {

        if (strlen($_POST['firstname']) > 3  && strlen($_POST['lastname']) > 3) {

            //verifie si il n'y a pas 2 personnages du même type
            if (!empty($_POST['selectPersonnage'])) {
                $select = $_POST['selectPersonnage'];

                $countPerso = $bdd->query("SELECT COUNT(type_perso) AS type_perso FROM akyos_game  WHERE type_perso='$select'");
                $NofPerso = $countPerso->fetch();

                if ($NofPerso['type_perso'] >= 2) {
                    $errer = 'Type de personnage indisponible il y en a déja 2';
                } else {



                    switch ($select) {
                        case 'développeur':
                            $health = 140;
                            $spec_atq = 0;
                            break;
                        case 'chefDeProjet':
                            $health = 160;
                            $spec_atq = 15;
                            break;
                        case 'commercial':
                            $health = 120;
                            $spec_atq = 15;
                            break;
                    }

                    $type = $select;

                    $perso = new Player([
                        'firstname' => $_POST['firstname'],
                        'lastname' => $_POST['lastname'],
                        'type_perso' => $type,
                        'health' => $health,
                        'spec_atq' => $spec_atq
                    ]);
                    $manager->add($perso);

                    $success = 'Perso enregisté';
                }
            } else {
                $errer = "minimun 3 caractères";
            }
        }
    }

    ?>


    <?php
    // update the health of player who was committed from the form "button"
    if (!empty($_POST['button'])) {
        $manager->update($_POST['button']);
    }

    if (!empty($_POST['endGame'])) {
        $manager->resetGame();
        $loopEnd = false;
    }

    // launch fight in auto through the btn " Run Game "
    if (!empty($_POST['lanceBoucle'])) {

        $game = true;
        while ($game) {
            //count number of player left in the table
            $NofPersoRestant = $manager->countPersoRestant();

            if ($NofPersoRestant['id'] > 1) {

                $randomPlayer = random_int(1, 6);
                //check the health 
                $health = $manager->checkHealth($randomPlayer);

                if (isset($health['health'])) {

                    // take off 10 from the health
                    $health = $health['health'] - 10;

                    if ($health > 0) {
                        $manager->updateHealth($health, $randomPlayer);
                    }

                    if ($health <= 0) {
                        $manager->removePlayer($randomPlayer);
                    }
                }

            } else {
                $loopEnd = true;
                $game = false;
            }
        };
    }

    // Get and display the list of all player
    $persos = $manager->getList();
    ?>
    <p>
        <?php
        foreach ($persos as $unPerso) {
            echo  htmlspecialchars($unPerso->firstname()), ' - ', htmlspecialchars($unPerso->lastname()), ' (type: ' . $unPerso->type_perso(), ' - ', ' health: ' . $unPerso->health(), ')<br />';
        ?>

    <form action="index.php" method="POST">
        <input type="submit" name="button" value="<?= $unPerso->id(); ?>" />
    </form>
    <br>
<?php

        }

?>
</p>
<?php


?>
<br>
<?= $errer ?>
<?= $success ?>
<br>
<br>

<?php

$NofPersoRestant = $manager->countPersoRestant();
if ($NofPersoRestant['id'] == 6) {

?>

    <form action="index.php" method="POST">
        <input style="width:75%;" type="submit" name="lanceBoucle" value="Run Game" />
    </form>


<?php
}

if ($loopEnd) {
    echo "Fin de la partie";

?>
    <form action="index.php" method="POST">
        <input style="width:75%;" type="submit" name="endGame" value="Start over" />
    </form>

<?php
}
?>

</body>

</html>