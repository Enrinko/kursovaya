<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <link href='../css/index.css' rel='stylesheet'/>
    <title>Тесты всё ок</title>
    <script defer src='../js/setTests.js'></script>
    <script defer type="text/javascript" src="../js/select.js"></script>
    <link href="../css/index-header.css" rel="stylesheet"/>
</head>
<?php
session_start();
function getElems() {
    include "../php/db.php";
    if (isset($_COOKIE['sort'])) {
        $tests = mysqli_query($link, (strcmp($_COOKIE['sort'], 'ByTime') != 0 ? "SELECT t.subject_id, t.theme 
FROM tests t WHERE t.subject_id = '" . $_COOKIE['sort'] . "'" : "SELECT t.subject_id, t.theme FROM tests t, subjects s ORDER BY t.updateTime ASC"));
        setcookie('sort', 1, 1);
    } else {
        $tests = mysqli_query($link, "SELECT subject_id, theme FROM tests ORDER BY theme");
    }
    $arrayOfThemes = array();
    $i = 0;
    while ($res = mysqli_fetch_array($tests, MYSQLI_ASSOC)) {
        $someArray = mysqli_fetch_array(mysqli_query($link, "SELECT subject_name FROM subjects WHERE subject_id =" .
                $res['subject_id'] . " "), MYSQLI_ASSOC)['subject_name'] . "-" . $res['theme'];
        $flag = true;
        for ($j = 0; $j < sizeof($arrayOfThemes); $j++) {
            $item = $arrayOfThemes[$j];
            if ($item == $someArray) {
                $flag = false;
            }
        }
        if ($flag) {
            $arrayOfThemes[$i] = $someArray;
            $i++;
        }
    }
echo implode(' ', $arrayOfThemes);
}

?>
<body onload="setTests('<?php getElems() ?>')">
<?php include 'header.html'?>

<div class="onsiteTitle">
    <div class='form-div'>
        <select class='div-select text' onchange="sortBySomething('<?php getElems() ?>')" id='subject'>
            <?php
            include "../php/db.php";
            $items = mysqli_query($link, "SELECT * FROM subjects");
            while ($res = mysqli_fetch_array($items, MYSQLI_ASSOC)) {
                echo "<option value='" . $res['subject_id'] . "'>" . $res['subject_name'] . "</option>";

            }
            ?>
            <option value="ByTime">По времени создания</option>
        </select>
    </div>
</div>

<div class="numberOfTests">
    <ul class="listOfTests">
        <li class="newTest">
            <div class="test">
                <ul class="test-ul text">
                    <li class="subject">Предмет</li>
                    <li class="theme">Тема теста</li>
                    <li class="score">1 из 1</li>
                </ul>
                <form class="test-form">
                    <button type="button" onclick="redirectTest('test.php', this.closest('.test'))" class="test-button text goToTest">Пройти тест</button>
                    <button type="button" onclick="redirectTest('add-edit-test.php', this.closest('.test'))" class="test-button needToCheck"><img src="../img/edit.png" class="img"></button>
                </form>
            </div>
        </li>
        <li class="AddnewTest needToCheck">
            <div>
                <button class="addTest" onclick="redirectTest('add-edit-test.php', this.closest('.test'))">
                    <img src="../img/addNewTest.png" class="img addNewTest">
                </button>
            </div>
        </li>
    </ul>
</div>
</body>
</html>
