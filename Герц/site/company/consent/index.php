<?php
// 23.11.2026: политика переехала на /privacy-policy/, согласие — /soglasie-na-obrabotku-personalnyh-dannyh/
require $_SERVER["DOCUMENT_ROOT"] .
    "/bitrix/modules/main/include/prolog_before.php";
LocalRedirect("/privacy-policy/", true, "301 Moved permanently");
