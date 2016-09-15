<?php
    session_start();
    if (isset($_POST["ok"])) { // скрипт начнет работать только после нажатия кнопки ок
        //print_r($_POST); // проверка что массив пост формируется правильно
        $from = htmlspecialchars($_POST["from"]); // проверяем наличие спец символов
        $to = htmlspecialchars($_POST["to"]);
        $subj = htmlspecialchars($_POST["subj"]);
        $message = htmlspecialchars($_POST["message"]);
        $_SESSION["from"] = $from; // в случае ошибки и перезагрузки формы пользователю не прийдется второй раз все вводить
        $_SESSION["to"] = $to;
        $_SESSION["subj"] = $subj;
        $_SESSION["message"] = $message;
        $error_from = ""; // сообщения об шибки будут выводится справа от поля
        $error_to = "";
        $error_subj = "";
        $error_message = "";
        $error = false;
        if ($from == "" || !preg_match("/@/", $from)) { // проверяем наличие в поле cимвола @
            $error_from = "Введите корректную почту";
            $error = true;
        }
        if ($to == "" || !preg_match("/@/", $to)) {
            $error_to = "Введите корректную почту";
            $error = true;
        }
        if (strlen($subj) == 0) { // проверям поле на пустоту
            $error_subj = "Введите тему";
            $error = true;
        }
        if (strlen($message) == 0) {
            $error_message = "Введите сообщение";
            $error = true;
        }
        if (!$error) { // ошибок нет высылаем письмо
            $subgMail = "=?utf-8?B?".base64_encode($subj)."?="; // коректное отоброжение темы сообщения всеми сборщиками почты
            $headers = "From: $from\r\nReply-to: $from\r\nContent-type: text/plain; charset=utf-8\r\n";
            mail($to, $subgMail, $message, $headers); // шлем письмо
            header ("location: success.php?send=1"); // редирект после успешной отправки, send=1 передаем методом get чтобы на странице success проверить что пользователь попал туда именно после отправки сообщения.
            /* success.php
            <?php
                if ($_GET["send"] == 1)
                echo "Сообщение успешно отправлено"
            ?>
            */
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма отправки</title>
</head>
<body>
<div class="mainWrap">
    <form name="messageForm" action="" method="post">
        <label>Имя</label><br>
        <input type="text" name="from" value="<?=$_SESSION["from"]?>"><span style="color:#ff3598"><?=$error_from?></span><br>
        <label>От кого</label><br>
        <input type="text" name="to" value="<?=$_SESSION["to"]?>"><span style="color:#ff3598"><?=$error_to?></span><br>
        <label>Тема</label><br>
        <input type="text" name="subj" value="<?=$_SESSION["subj"]?>"><span style="color:#ff3598"><?=$error_subj?></span><br>
        <label>Сообщение</label><br>
        <textarea name="message" cols="20" rows="10"><?=$_SESSION["message"]?></textarea><span style="color:#ff3598"><?=$error_message?></span><br>
        <input type="submit" name="ok" value="Ok">
    </form>
</div>
</body>
</html>