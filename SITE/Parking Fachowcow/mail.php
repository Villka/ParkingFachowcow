<?php
// если была нажата кнопка "Отправить"
if($_POST['submit']) {
        // $_POST['title'] содержит данные из поля "Тема"
                $title = $_POST['title'];
                $mess =  $_POST['mess'];
        // $to - кому отправляем
                $to = 'mail@parkingfachowcow.eu';
        // $from - от кого
                $from='mail@parkingfachowcow.eu';
        // функция, которая отправляет наше письмо.
                mail($to, $title, $mess, 'from:'.$from, '-f'.$from);
                echo 'Спасибо! Ваше письмо отправлено.';
                } ?>
<form action="" method=post>
   <p>Вводный текст перед формой <p>
   <div align="center">Тeма<br />
   <input id="suka" type="text" name="title" size="40"><br />Сообщение<br />
   <textarea id="suka" name="mess" rows="10" cols="40"></textarea>
   <br />
   <input type="submit" value="Отправить" name="submit"></div>
</form>