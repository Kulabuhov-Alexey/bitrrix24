<? session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <title>TestTask</title>
</head>
<?

if (!empty($_POST['PLACEMENT_OPTIONS'])) {
  $re = '/(?!"ID":")[[:digit:]]+/m';
  preg_match($re, $_POST['PLACEMENT_OPTIONS'], $matches);
  $deal_ID = $matches[0];
  $_SESSION['deal_ID'] = $deal_ID;
}
// update hooks
if (!empty($_POST['contactPerson'])) {
  // update comment data
  $queryUrl = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.livefeedmessage.add.json';
  $queryData = http_build_query(array(
    "fields" => array(
      "POST_TITLE" => "Немного о сервисе",
      "MESSAGE" => $_POST['comments'],
      "SPERM" => array(
        "CRMCONTACT" => ["CRMCONTACT3", "CRMCONTACT7"],
        "CRMCOMPANY" => ["CRMCOMPANY1", "CRMCOMPANY3"],
        "CRMDEAL" => ["CRMDEAL3", "CRMDEAL5"],
        "CRMLEAD" => ["CRMLEAD9", "CRMLEAD11"],
        "SG" => ["SG5", "SG9"],
        "U" => ["U1", "U3"],
        "DR" => ["DR1", "DR7"],
      ),
      "ENTITYTYPEID" => 2,
      "ENTITYID" => $_SESSION['deal_ID'],
    )
  ));

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
  ));

  $result = curl_exec($curl);
  curl_close($curl);
  // update deal data
  $queryUrl = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.deal.update.json';
  $queryData = http_build_query(array(
    "ID" => $_SESSION['deal_ID'],
    'FIELDS' => array(
      "TITLE" => $_POST['nameDeal'], //название сделки
      "COMMENTS" => $_POST['comments'] //комментарий
    )
  ));

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
  ));

  $result = curl_exec($curl);
  curl_close($curl);

  // update company data
  $queryUrl = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.company.update.json';
  $queryData = http_build_query(array(
    "ID" => $_SESSION['company_ID'],
    'FIELDS' => array(
      "TITLE" => $_POST['companyName']
      //      "UF_CRM_1581619509707" => $_POST['city'], // город
      //      "UF_CRM_1581620973525" => $_POST['inn'] // ИНН
    )
  ));

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
  ));

  $result = curl_exec($curl);
  curl_close($curl);
  // update city
  $queryUrl_company = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.address.update.json';
  $queryData = http_build_query(array("fields" => array("TYPE_ID" => "1", "ENTITY_TYPE_ID" => "8", "ENTITY_ID" => $_SESSION['inn_id'], "CITY" => $_POST['city'])));

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl_company,
    CURLOPT_POSTFIELDS => $queryData,
  ));
  $result = curl_exec($curl);
  curl_close($curl);

  // update inn
  $queryUrl_requisite = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.requisite.update.json';
  $queryData = http_build_query(array("id" => $_SESSION['inn_id'], "fields" => array("RQ_INN" => $_POST['inn'])));

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl_requisite,
    CURLOPT_POSTFIELDS => $queryData,
  ));

  $result = curl_exec($curl);
  curl_close($curl);

  // update contact data
  $fio = explode(" ", $_POST['contactPerson']);
  $queryUrl = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.contact.update.json';
  $queryData = http_build_query(array(
    "ID" => $_SESSION['contact_ID'],
    'FIELDS' => array(
      "NAME" => $fio[0], //и
      "SECOND_NAME" => $fio[1], //о
      "LAST_NAME" => $fio[2], //ф
      "POST" => $_POST['position'], //Должность
      "PHONE" => array(0 => array("VALUE" => $_POST['phone'], "ID" => $_POST['id_phone'])), //Телефон
      "EMAIL" => array("0" => array("VALUE" => $_POST['email'], "ID" => $_POST['id_email'])) //емэйл
    )
  ));

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
  ));

  $result = curl_exec($curl);
  curl_close($curl);
}

//read hooks
//read deal ----------------
$queryUrl_deal = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.deal.get.json';
$queryData = http_build_query(array("ID" => $_SESSION['deal_ID']));
$curl_deal = curl_init();
curl_setopt_array($curl_deal, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_deal,
  CURLOPT_POSTFIELDS => $queryData,
));
$result_deal = curl_exec($curl_deal);
curl_close($curl_deal);
$result_deal = json_decode($result_deal, true);
$deal_title = $result_deal["result"]["TITLE"];
$_SESSION['company_ID'] = $result_deal["result"]["COMPANY_ID"];
$_SESSION['contact_ID'] = $result_deal["result"]["CONTACT_ID"];
//--------------------------------
//read company ----------------
$queryUrl_company = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.company.get.json';
$queryData = http_build_query(array("ID" => $_SESSION['company_ID']));
$curl_company = curl_init();
curl_setopt_array($curl_company, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_company,
  CURLOPT_POSTFIELDS => $queryData,
));
$result_company = curl_exec($curl_company);
curl_close($curl_company);
$result_company = json_decode($result_company, true);
$company_name = $result_company["result"]["TITLE"];
//$company_city = $result_company["result"]["UF_CRM_1581619509707"];
//$company_inn = $result_company["result"]["UF_CRM_1581620973525"];
//---------------
// read contact------------
$queryUrl_contact = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.contact.get.json';
$queryData = http_build_query(array("ID" => $_SESSION['contact_ID']));
$curl_contact = curl_init();
curl_setopt_array($curl_contact, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_contact,
  CURLOPT_POSTFIELDS => $queryData,
));
$result_contact = curl_exec($curl_contact);
curl_close($curl_contact);
$result_contact = json_decode($result_contact, true);
$contact_person = "{$result_contact['result']['NAME']} {$result_contact['result']['SECOND_NAME']} {$result_contact['result']['LAST_NAME']}";
$contact_position = $result_contact["result"]["POST"];
$deal_id_phone = $result_contact["result"]["PHONE"]["0"]["ID"];
$contact_phone = $result_contact["result"]["PHONE"]["0"]["VALUE"];
$deal_id_email = $result_contact["result"]["EMAIL"]["0"]["ID"];
$contact_email = $result_contact["result"]["EMAIL"]["0"]["VALUE"];
//-------------
// read INN
$queryUrl_requisite = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.requisite.list.json';
$queryData = http_build_query(array("filter" => array('ENTITY_TYPE_ID' => 4, 'ENTITY_ID' => $_SESSION['company_ID'], 'PRESET_ID' => 1)));

$curl_requisite = curl_init();
curl_setopt_array($curl_requisite, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_requisite,
  CURLOPT_POSTFIELDS => $queryData,
));


$result_requisite = curl_exec($curl_requisite);

curl_close($curl_requisite);

$requisite_list = json_decode($result_requisite, true);
for ($i = 0; $i <= count($requisite_list); $i++) {
  if ($requisite_list['result'][$i]['RQ_INN'] == '') {
    continue;
  } else {
    $company_inn = $requisite_list['result'][$i]['RQ_INN'];
    $_SESSION['inn_id'] = $requisite_list['result'][$i]['ID'];
  };
};

//read City----------
$queryUrl_address = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.address.list.json';
$queryData = http_build_query(array("filter" => array("ENTITY_TYPE_ID" => 8,  'ENTITY_ID' => $_SESSION['inn_id'])));

$curl_address = curl_init();
curl_setopt_array($curl_address, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_address,
  CURLOPT_POSTFIELDS => $queryData,
));


$result_address = curl_exec($curl_address);


curl_close($curl_address);

$result = json_decode($result_address, true);
$company_city = $result['result'][0]['CITY'];
?>

<body>
  <div class="d-flex flex-column bg-secondary vh-100 justify-content-center">
    <div class="container jumbotron">
      <form action="" method="POST">
        <div class="form-group row">
          <label for="comment" class="col-md-2 col-form-label">Комментарии</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="comment" rows="3" name="comments"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="companyName" class="col-md-2 col-form-label">Название компании</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="companyName" name="companyName" value="<?= $company_name; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="city" class="col-md-2 col-form-label">Город</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="city" name="city" value="<?= $company_city; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="nameDeal" class="col-md-2 col-form-label">Название сделки</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="nameDeal" name="nameDeal" value="<?= $deal_title; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="inn" class="col-md-2 col-form-label">ИНН</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inn" name="inn" value="<?= $company_inn; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="contactPerson" class="col-md-2 col-form-label">Контактное лицо</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="contactPerson" name="contactPerson" value="<?= $contact_person; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="position" class="col-md-2 col-form-label">Должность</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="position" name="position" value="<?= $contact_position; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="phone" class="col-md-2 col-form-label">Телефон</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" id="phone" name="id_phone" value="<?= $deal_id_phone; ?>">
            <input type="text" class="form-control" id="phone" name="phone" value="<?= $contact_phone; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="email" class="col-md-2 col-form-label">E-mail</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" id="phone" name="id_email" value="<?= $deal_id_email; ?>">
            <input type="text" class="form-control" id="email" name="email" value="<?= $contact_email; ?>">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-primary">Отправить</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>

</html>
