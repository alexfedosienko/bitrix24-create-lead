<?php

/*
Возможные значения для $postData

LOGIN*   String   Логин
PASSWORD*   String   Пароль
TITLE*   String   Название
COMPANY_TITLE   String   Название компании
NAME   String   Имя
LAST_NAME   String   Фамилия
SECOND_NAME   String   Отчество
POST   String   Должность
ADDRESS   String   Адрес
COMMENTS   String   Комментарий
SOURCE_DESCRIPTION   String   Дополнительно об источнике
STATUS_DESCRIPTION   String   Дополнительно о статусе
OPPORTUNITY   Double   Возможная сумма сделки
CURRENCY_ID   String   Валюта
PRODUCT_ID   String   Продукт
SOURCE_ID   String   Источник
STATUS_ID   String   Статус
ASSIGNED_BY_ID   Int   Ответственный
PHONE_WORK   String   Рабочий телефон
PHONE_MOBILE   String   Мобильный телефон
PHONE_FAX   String   Номер факса
PHONE_HOME   String   Домашний телефон
PHONE_PAGER   String   Номер пейджера
PHONE_OTHER   String   Другой телефон
WEB_WORK   String   Корпоративный сайт
WEB_HOME   String   Личная страница
WEB_FACEBOOK   String   Страница Facebook
WEB_LIVEJOURNAL   String   Страница LiveJournal
WEB_TWITTER   String   Микроблог Twitter
WEB_OTHER   String   Другой сайт
EMAIL_WORK   String   Рабочий e-mail
EMAIL_HOME   String   Частный e-mail
EMAIL_OTHER   String   Другой e-mail
IM_SKYPE   String   Контакт Skype
IM_ICQ   String   Контакт ICQ
IM_MSN   String   Контакт MSN/Live!
IM_JABBER   String   Контакт Jabber
IM_OTHER   String   Другой контакт

STATUS_ID – Статусы:
NEW Не обработан
ASSIGNED Назначен ответственный
DETAILS Уточнение информации
CANNOT_CONTACT Не удалось связаться
IN_PROCESS В обработке
ON_HOLD Обработка приостановлена
RESTORED Сконвертирован
CONVERTED Восстановлен
JUNK Некачественный лид

SOURCE_ID – Источники:
SELF Свой контакт
PARTNER Существующий клиент
CALL Звонок
WEB Веб-сайт
EMAIL Электронная почта
CONFERENCE Конференция
TRADE_SHOW Выставка
EMPLOYEE Сотрудник
COMPANY Кампания
HR HR - департамент
MAIL Письмо
OTHER Другое

CURRENCY_ID – Валюты:
RUB Рубль
USD Доллар США
EUR Евро

PRODUCT_ID – Продукты:
PRODUCT_1 1С-Битрикс: Управление сайтом
PRODUCT_2 1С-Битрикс: Корпоративный портал
OTHER Другое

*/

define('CRM_HOST', ''); // Ваш домен CRM системы
define('CRM_PORT', '443'); // Порт сервера CRM. Установлен по умолчанию
define('CRM_PATH', '/crm/configs/import/lead.php'); // Путь к компоненту lead.rest
define('CRM_LOGIN', ''); // Логин пользователя Вашей CRM по управлению лидами
define('CRM_PASSWORD', ''); // Пароль пользователя Вашей CRM по управлению лидами

function createLead($postData) {
	if (defined('CRM_AUTH')) {
		$postData['AUTH'] = CRM_AUTH;
	} else {
		$postData['LOGIN'] = CRM_LOGIN;
		$postData['PASSWORD'] = CRM_PASSWORD;
	}
	$fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
	if ($fp) {
		$strPostData = '';
		foreach ($postData as $key => $value)
			$strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
		$str = "POST ".CRM_PATH." HTTP/1.0\r\n";
		$str .= "Host: ".CRM_HOST."\r\n";
		$str .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$str .= "Content-Length: ".strlen($strPostData)."\r\n";
		$str .= "Connection: close\r\n\r\n";
		$str .= $strPostData;
		fwrite($fp, $str);
		$result = '';
		while (!feof($fp)) {
			$result .= fgets($fp, 128);
		}
		fclose($fp);
		$response = explode("\r\n\r\n", $result);
		$output = '<pre>'.print_r($response[1], 1).'</pre>';
	} else {
		echo 'Connection Failed! '.$errstr.' ('.$errno.')';
	}

}

$postData = array();
createLead($postData);

?>
