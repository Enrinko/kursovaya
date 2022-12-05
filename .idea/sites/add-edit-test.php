<!DOCTYPE html>
<?php
include_once "../php/db.php";
$arrayOfQuestions = array();
if (isset($_GET['theme'])) {
    $theme = mysqli_query($link, "SELECT * FROM tests WHERE theme LIKE (" . $_GET['theme'] . ")");
    $count = 0;
    setcookie('newTest', $_COOKIE['newTest'], 1);
    while ($res = mysqli_fetch_array($theme, MYSQLI_ASSOC)) {
        $arrayOfQuestions[$count] = implode(' ', $res);
        $count++;
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script defer src="../js/actionsOnTest.js" type="text/javascript"></script>
    <script defer src="../js/select.js" type="text/javascript"></script>
    <script defer src="../js/getTests.js" type="text/javascript"></script>
    <script defer src="../js/moreActions.js" type="text/javascript"></script>
    <link href="../css/workTest.css" rel="stylesheet"/>
    <link href="../css/index-header.css" rel="stylesheet"/>
</head>
<body onload="moreActions('<?php if (isset($_GET['theme'])) echo implode(' ', $arrayOfQuestions) ?>')">
<?php include 'header.html' ?>
<div class="title text">Добавить тест</div>
<div class="test text">
    <div class='form-div'>
        <select class='div-select text' id='subject'>
            <?php
            $items = mysqli_query($link, "SELECT subject_name FROM subjects");
            while ($res = mysqli_fetch_array($items, MYSQLI_ASSOC)) {
                echo "<option value='" . $res['subject_id'] . "'>" . $res['subject_name'] . "</option>";
            }
            ?>
        </select>
    </div>
    <input class="input input-non-repeat" id="theme" placeholder="Тема теста" type="text">
    <div class="question-with-answer">
        <div class="deleteQuestion">
            <button class="deleteQuestion" onclick="deleteSomething(this)" value="1">
                <img class="img trashcan-img" src="../img/trashcan.png">
            </button>
        </div>
        <div class="types">
            <div>
                <input class="typeOfAnswer multi" name="only"
                       onchange="checkTypes(this.value, this.closest('.question-with-answer'))" type="radio"
                       value="2">
                <span>2+</span>
            </div>
            <div>
                <input checked class="typeOfAnswer only"
                       name="only" onchange="checkTypes(this.value, this.closest('.question-with-answer'))"
                       type="radio"
                       value="1">
                <span>1</span>
            </div>
        </div>
        <input class="question-text input" name="question-text" placeholder="Тема вопроса" type="text">
        <button class="addAnswer button" onclick="add('question-answer', 'question-with-answer', this)" value="0">
            <img class="img addA-span" src="../img/delete.png"> <span>Добавить ответ</span>
        </button>
        <div class="question-answer">
            <input class="right" name="right" onchange="setRight(this)"
                   type="radio"
                   value="0">
            <div class="answer-input">
                <input class="input answer-text" name="answer" placeholder="Вопрос" type="text">
            </div>
            <div class="deleteAnswer">
                <button onclick="deleteSomething(this)" value="0"><img class="img delete-img"
                                                                       src="../img/delete.png">
                </button>
            </div>
        </div>
    </div>

</div>
<div class="actions text">
    <div class="actions-title">Панель управления</div>
    <div class="block-of-buttons">
        <button class="addQuestion button" onclick="add('question-with-answer', 'test', this)" value="1">
            <img class="img add-img" src="../img/delete.png"> <span class="actions-add"> Добавить вопрос </span>
        </button>
    </div>
    <form action="" method="post">
        <input class="save text" onclick="toPHP()" type="submit" value="Сохранить">
    </form>
</div>
</body>
</html>
<?php
function decode($entry)
{
    $json = sprintf('"%s"', $entry); # build json string
    return json_decode($json, true); # json decode
}

$nameOfCookie = 's';
if (isset($_COOKIE[$nameOfCookie])) {
    $question = explode(',', $_COOKIE[$nameOfCookie]);
    $newArray = array();
    $smallArray = array();
    $count = 0;
    for ($i = 0; $i < sizeof($question); $i++) {
        $smallArray[$i % 6] = str_contains($question[$i], '%u') ? decode(str_replace('%u', "\\u", $question[$i])) : $question[$i];
        if (($i + 1) % 6 == 0 && $i != 0) {
            $newArray[$count] = $smallArray;
            $count++;
            $smallArray = array();
        }
    }
    $checkArray = array();
    $check = mysqli_query($link, "SELECT * FROM tests WHERE theme LIKE ('" . $newArray[0][1] . "')");
    while ($res = mysqli_fetch_array($check, MYSQLI_ASSOC)) {
        $checkArray[$count] = $res;
    }
    if (sizeof($checkArray) != 0) {
        mysqli_query($link, "DELETE FROM `tests` WHERE `theme` LIKE ('" . $newArray[0][1] . "')");
    }
    for ($i = 0; $i < sizeof($newArray); $i++) {
        $item = $newArray[$i];
        mysqli_query($link, "INSERT INTO `tests`(`theme`, `question`, `type`, `rightOrNot`, `answer`, `subject_id`, `updateTime`) VALUES ('$item[1]','$item[2]','$item[3]','$item[4]','$item[5]','$item[0]', '" . date('Y-m-d G-i-s') . "')");
    }
}
?>