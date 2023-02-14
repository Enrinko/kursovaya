<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Вход регистрация</title></head><?php $type = $_GET['type']; ?>
<body>
<div class="logo"><img src="../img/logo.png" class="logo-img img"></img></div>
<div class="login"><span class="title"><?php echo $type ?></span>
    <form action="" method="post"><input type="text" name="username" placeholder="Логин" class="field text">
        <input type="hidden" name="type" value="<?php echo $type ?>">
        <input type="password" name="password" placeholder="Пароль" class="field text">
        <input type="submit" value="<?php echo 'Войти' != $type ? 'Зарегистрируйтесь' : 'Войти' ?>" class="field text">
    </form>
    <div class="bottom-title text">
        <a href="login.php?type=<?php echo 'Войти' == $type ? 'Зарегистрируйтесь' : 'Войти' ?>" class="link">
            <?php echo 'Войти' == $type ? 'Зарегистрируйтесь' : 'Войти' ?>
        </a>
    </div>
</div>
</body>
<?php include_once "../php/db.php";
if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($type == 'Войти') {
        $find = mysqli_query($link, "SELECT * FROM users WHERE username LIKE ('" . $_POST['username'] . "') AND password LIKE ('" . $_POST['password'] . "')");
        $flag = false;
        $type;
        while ($res = mysqli_fetch_array($find, MYSQLI_ASSOC)) {
            $flag = true;
            $type = $res['type'];
        }
        if ($flag) {
            session_start();
            $_SESSION['user'] = $type;
            echo "<script type='text/javascript'> localStorage.setItem('user', " . $type . "); alert(localStorage.getItem('user')) </script>";
            echo "<script type='text/javascript'>window.location.replace('/kursovaya/.idea/sites/index.php')</script>";
        }
    } else {
        mysqli_query($link, "INSERT INTO users(username, password, type) VALUES('" . $_POST['username'] . "', '" . $_POST['password'] . "', 0)");
        session_start();
        $find = mysqli_query($link, "SELECT * FROM users WHERE username LIKE ('" . $_POST['username'] . "') AND password LIKE ('" . $_POST['password'] . "')");
        $_SESSION['user'] = mysqli_fetch_array($find, MYSQLI_ASSOC)['type'];
        echo "<script type='text/javascript'> localStorage.setItem('user', " . $type . ") alert(localStorage.getItem('user')) </script>";
        echo "<script type='text/javascript'>window.location.replace('/kursovaya/.idea/sites/index.php')</script>";
    }
} ?></html>