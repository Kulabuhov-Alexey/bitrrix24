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
// update hooks
if (!empty($_POST)) {
  // update company data
  $queryUrl = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.company.update.json';
  $queryData = http_build_query(array(
    "ID" => "2",
    'FIELDS' => array(
      "TITLE" => $_POST['companyName'],
      "UF_CRM_1581619509707" => $_POST['city'], // город
      "UF_CRM_1581620973525" => $_POST['inn'] // ИНН
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
    "ID" => "2",
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

  // update contact data
  $fio = explode(" ",$_POST['contactPerson']);
  $queryUrl = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.contact.update.json';
  $queryData = http_build_query(array(
    "ID" => "2",
    'FIELDS' => array(
      "NAME" => $fio[0], //ф
      "SECOND_NAME" => $fio[1], //и
      "LAST_NAME" => $fio[2], //о
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
?>
<?
//read hooks
$queryUrl_company = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.company.get.json';
$queryUrl_deal = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.deal.get.json';
$queryUrl_contact = 'https://b24-pwelds.bitrix24.ru/rest/1/g89qnk5f5n02kqrf/crm.contact.get.json';
$queryData = http_build_query(array("ID" => "2"));

$curl_company = curl_init();
curl_setopt_array($curl_company, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_company,
  CURLOPT_POSTFIELDS => $queryData,
));
$curl_deal = curl_init();
curl_setopt_array($curl_deal, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_deal,
  CURLOPT_POSTFIELDS => $queryData,
));
$curl_contact = curl_init();
curl_setopt_array($curl_contact, array(
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POST => 1,
  CURLOPT_HEADER => 0,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => $queryUrl_contact,
  CURLOPT_POSTFIELDS => $queryData,
));

$result_company = curl_exec($curl_company);
$result_deal = curl_exec($curl_deal);
$result_contact = curl_exec($curl_contact);
curl_close($curl_company);
curl_close($curl_deal);
curl_close($curl_contact);
$result_company = json_decode($result_company, true);
$result_deal = json_decode($result_deal, true);
$result_contact = json_decode($result_contact, true);
$company_name = $result_company["result"]["TITLE"];
$company_city = $result_company["result"]["UF_CRM_1581619509707"];
$company_inn = $result_company["result"]["UF_CRM_1581620973525"];
$deal_title = $result_deal["result"]["TITLE"];
$contact_person = "{$result_contact['result']['NAME']} {$result_contact['result']['SECOND_NAME']} {$result_contact['result']['LAST_NAME']}";
$contact_position = $result_contact["result"]["POST"];
$id_phone = $result_contact["result"]["PHONE"]["0"]["ID"];
$contact_phone = $result_contact["result"]["PHONE"]["0"]["VALUE"];
$id_email = $result_contact["result"]["EMAIL"]["0"]["ID"];
$contact_email = $result_contact["result"]["EMAIL"]["0"]["VALUE"];
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
            <input type="hidden" class="form-control" id="phone" name="id_phone" value="<?= $id_phone; ?>">
            <input type="text" class="form-control" id="phone" name="phone" value="<?= $contact_phone; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="email" class="col-md-2 col-form-label">E-mail</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" id="phone" name="id_email" value="<?= $id_email; ?>">
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
