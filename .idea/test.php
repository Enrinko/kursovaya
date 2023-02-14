<?php
include_once "../php/db.php";$arrayOfQuestions = array();$theme = mysqli_query($link, "SELECT * FROM tests WHERE theme LIKE (" . $_GET['theme'] . ")");$count = 0;while ($res = mysqli_fetch_array($theme, MYSQLI_ASSOC)) {
    $res['subject_id'] = mysqli_fetch_array(mysqli_query($link, "SELECT subject_name FROM subjects WHERE subject_id = " . $res['subject_id']), MYSQLI_ASSOC)['subject_name'];
    $arrayOfQuestions[$count] = implode(' ', $res);
    $count++;
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="../css/test.css" rel="stylesheet"/>
    <link href="../css/index-header.css" rel="stylesheet"/>
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script defer src="../js/outputTest.js"></script>
</head>
<body onload="moreActions('<?php echo implode(' ', $arrayOfQuestions) ?>')"><?php include 'header.html' ?>
<form method="get">
    <div class="outputTest text">
        <div class="outputSubject"><span class="subjectText">Предмет</span></div>
        <div class="outputTheme"><span class="themeText">SDSDSDSD</span></div>
        <div class="outputQuestion">
            <div class="question"><span class="questionText"></span>
                <input type="hidden" name="checkbox[question]" class="hidden"></div>
            <div class="questionAnswers">
                <div class="answer"><input type="checkbox" name="checkbox[answer]" class="checkedOrNot"><label
                            class="answerText"></label></div>
            </div>
        </div>
    </div>
    <div class="actions text"><input class="save text" onclick="check()" type="submit" value="Сохранить"></div>
    <input type="hidden" name="theme" value="<?php echo $_GET['theme'] ?>"></form>
</body>
</html> <?php if (isset($_GET['checkbox'])) {
    $checkboxMassiv = $_GET['checkbox'];
    $theme = $_GET['theme'];
    $needToFind = mysqli_query($link, "SELECT question, rightOrNot, answer FROM tests WHERE theme LIKE ($theme)");
    $massiv = array();
    $count = 0;
    while ($res = mysqli_fetch_array($needToFind, MYSQLI_ASSOC)) {
        if ($res['rightOrNot'] == 0) {
            continue;
        }
        $massiv[$count]['question'] = $res['question'];
        $massiv[$count]['rightOrNot'] = $res['rightOrNot'];
        $massiv[$count]['answer'] = $res['answer'];
        $count++;
    }
    $allRights = sizeof($massiv);
    $thisRights = 0;
    foreach ($massiv as $right) {
        for ($j = 0; $j < sizeof($checkboxMassiv); $j++) {
            $right['question'] == $checkboxMassiv['question'] ? $right['answer'] == $checkboxMassiv['answer'] ? $thisRights++ : "" : "";
            $j++;
        }
    }
    echo "<script> alert('Вы ответили правильно на ' + $thisRights + 'вопросов из' + $allRights ) </script>";
    setcookie($theme, ("$thisRights из $allRights"));
} ?>