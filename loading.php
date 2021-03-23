<?php
header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');

$data               = array();
$data['return_day'] = array();
$data['status']     = '';
$data['error']      = '';

# Подгружаем забронированные дни из файла
if($_POST['type'] == 'loading'){
    # Проверяем существование файла с забронированными днями
    if (file_exists('booking_day.txt')){
        $data['return_day'] = file('booking_day.txt',FILE_IGNORE_NEW_LINES); 
        $data['status'] = 'Забронированные дни загружены';
    } else { $data['status'] = 'Бронь на этот месяц отсутствует'; }
}

# Сохраняем бронь обратно в файл, дополняя его новыми днями
if($_POST['type'] == 'save'){ 
    $day = array_values($_POST['day']); $day = implode(PHP_EOL,$day); 
    # Добавляем новые дни в файл каждый с новой строки
    $result = file_put_contents('booking_day.txt', $day.PHP_EOL, FILE_APPEND | LOCK_EX);
    
        if ($result === FALSE){ $data['error'] = '1'; $data['status'] = 'Ошибка записи в файл'; }
        else { $data['error'] = '0'; $data['status'] = 'Выбранные дни добавлены в список забронированных'; }
}

echo json_encode($data);
unset($data);
?>