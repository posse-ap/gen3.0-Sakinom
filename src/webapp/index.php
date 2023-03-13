<?php
$dbn = 'mysql:dbname=posse; host=db';
$user = 'root';
$pass = 'root';

try {
  $pdo = new PDO($dbn, $user, $pass);
} catch (PDOException $e) {
  die("接続エラー：{$e->getMessage()}");
}
// sleep(3);
// var_dump($_POST['content']);
// if (isset($_POST['submit'])) {
if ($_POST) {
  $studiedDate = $_POST['date'];
  // var_dump($studiedDate);
  $target = ['年', '月'];
  $replaceDate = str_replace($target, '-', $studiedDate);
  $replacedDate = str_replace('日', '', $replaceDate);
  $newDate = date('Y-m-d', strtotime($replacedDate));
  // $content = $_POST['content'];

  $content = filter_input(INPUT_POST, 'content', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
  // var_dump($content);
  // if(isset($content)) {
  //   $allContent = implode(", ", $content);
  // }else{
  //   $allContent = "";
  // }
  $language = filter_input(INPUT_POST, 'language', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
  // var_dump($language);

  // if(isset($language)) {
  //   $allLang = implode(", ", $language);
  // }else{
  //   $allLang = "";
  // }
  $studyHours = filter_input(INPUT_POST, 'hours', FILTER_DEFAULT, FILTER_VALIDATE_INT);

  $sql = 'INSERT INTO studies(studied_date, studyhours) VALUES (:studied_date, :studyhours)';
  $stmt = $pdo->prepare($sql);
  if ($newDate === "") {
    $stmt->bindValue(":studied_date", null, PDO::PARAM_NULL);
  } else {
    $stmt->bindValue(':studied_date', $newDate);
  }
  if ($studyHours === "") {
    $stmt->bindValue(":studyhours", null, PDO::PARAM_NULL);
  } else {
    $stmt->bindValue(':studyhours', $studyHours);
  }
  // var_dump($newDate);
  $studies = $stmt->execute();

  $studies_id = $pdo->lastInsertId();
  $time_l = $studyHours / count($language);

  var_dump(count($language));

  $sql = 'INSERT INTO languages(studies_id, language, studyhours) VALUES (:studies_id, :language, :studyhours)';
  $stmt = $pdo->prepare($sql);
  foreach ($language as $l) {
    $stmt->bindValue(':studies_id', $studies_id);
    $stmt->bindValue(':language', $l);
    $stmt->bindValue(':studyhours', $time_l);
    $languages = $stmt->execute();
  }


  $time_c = $studyHours / count($content);

  $sql = 'INSERT INTO contents(studies_id, content, studyhours) VALUES (:studies_id, :content, :studyhours)';
  $stmt = $pdo->prepare($sql);
  foreach ($content as $c) {
    $stmt->bindValue(':studies_id', $studies_id);
    $stmt->bindValue(':content', $c);
    $stmt->bindValue(':studyhours', $time_c);
    $contents = $stmt->execute();
  }

  // POST処理の最後にリダイレクト処理
  header("Location:./index.php");
  // exit();   
} else {
  echo "error";
}

$day = $pdo->query("SELECT DATE_FORMAT(studied_date, '%Y-%m-%d') as studied_day, case when sum(studyhours) is not null then sum(studyhours) else 0 end as studyhours from studies group by studied_day having studied_day = DATE_FORMAT(CURDATE(), '%Y-%m-%d')")->fetchAll(PDO::FETCH_ASSOC);
// $stmt = $pdo->prepare("SELECT DATE_FORMAT(calendar.ymd, '%d') as studied_day, case when sum(studyhours) is not null then sum(studyhours) else 0 end as studyhours from studies
// right outer join (
// select
// d.ymd as ymd
// from(
// select
// date_format(date_add(date_add(last_day( now()), interval - day(last_day(now())) DAY ) , interval td.add_day DAY ), '%Y-%m-%d' ) as ymd
// from(
// select
// 0 as add_day
// from
// dual
// where
// ( @num := 1 - 1 ) * 0
// union all
// select
// @num := @num + 1 as add_day
// from
// `information_schema`.columns limit 31
// ) as td
// ) as d
// where month(d.ymd) = month(now())
// order by d.ymd ) as calendar
// on calendar.ymd = studies.studied_date
// group by calendar.ymd
// having studied_day = DATE_FORMAT(CURDATE(), '%e')");
// $stmt = $pdo->prepare("SELECT DATE_FORMAT(`studied_date`, '%Y-%m-%d') as `studied_day`, sum(studyhours) as studyhours from studies group by studied_date having studied_day = DATE_FORMAT(CURDATE(), '%Y-%m-%d')");
// $stmt->bindValue(':date', date('Y-m-d'));
// $stmt->execute();
// $day = $stmt->fetchAll(PDO::FETCH_ASSOC);

// var_dump($day);

$stmt = $pdo->prepare("select DATE_FORMAT(`studied_date`, '%Y-%m') as studied_month, sum(studyhours) as studyhours from studies group by studied_month having studied_month=:month");
$stmt->bindValue(':month', date('Y-m'));
$stmt->execute();
$month = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = $pdo->query("select sum(studyhours) as studyhours from studies")->fetchAll(PDO::FETCH_ASSOC);

$dates = $pdo->query("SELECT DATE_FORMAT(calendar.ymd, '%e') as day, case when sum(studyhours) is not null then sum(studyhours) else 0 end as time from studies
right outer join (
select
d.ymd as ymd
from(
select
date_format(date_add(date_add(last_day( now()), interval - day(last_day(now())) DAY ) , interval td.add_day DAY ), '%Y-%m-%d' ) as ymd
from(
select
0 as add_day
from
dual
where
( @num := 1 - 1 ) * 0
union all
select
@num := @num + 1 as add_day
from
`information_schema`.columns limit 31
) as td
) as d
where month(d.ymd) = month(now())
order by d.ymd ) as calendar
on calendar.ymd = studies.studied_date
group by calendar.ymd having DATE_FORMAT(calendar.ymd, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') order by calendar.ymd")->fetchAll(PDO::FETCH_ASSOC);

// $month_btn = date('Y-m');

// function left() {
//   $month_btn = date('Y-m', strtotime('-1 month'));
//   var_dump($month_btn);
//   return $month_btn;
// }

// function right() {
//   $month_btn = date('Y-m', strtotime('+1 month'));
//   var_dump($month_btn);
//   return $month_btn;
// }

// if(){
//   $time = date('Y-m', strtotime('+1 month'));
// }else if(){
//   $time = date('Y-m', strtotime('-1 month'));
// }else{
//   $time = date('Y-m');
// }

// $stmt->bindValue(':time', $time);
// $stmt->execute();
// $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);


// $time = time();
// $first_day = date('j', strtotime(date('Ymd', $time) . "first day of this month"));
// $last_day = date('j', strtotime(date('Ymd', $time) . "last day of this month"));
// $days = range($first_day, $last_day);

$arr = json_encode($dates);
file_put_contents("studyhours.json", $arr);

// $lang = $pdo->query('select case when language is not null then language else "その他" end as language, sum(studyhours) from studies group by language')->fetchAll(PDO::FETCH_ASSOC);

$studies_l = $pdo->query("select languages.language, sum(languages.studyhours) as studyhours from studies join languages on studies.id = languages.studies_id group by language")->fetchAll(PDO::FETCH_ASSOC);
// $languages = $pdo->query("SELECT * FROM languages")->fetchAll(PDO::FETCH_ASSOC);

// foreach($languages as $key => $l) {
//   $index = array_search($l["studies_id"], array_column($studies, 'id'));
//   $studies[$index]["languages"][] = $l;
// }

// echo '<pre>';
// var_dump($studies);
// echo '</pre>';


$language_arr = [];

foreach ($studies_l as $s) {
  $language_arr = array_merge($language_arr, array($s['language'] => $s['studyhours']));
}

// foreach($studies as $s) {
//   array_push($language_arr, $s['language']  $s['studyhours']);
// }

// var_dump($language_arr);
$language_array = json_encode($language_arr, JSON_UNESCAPED_UNICODE);
file_put_contents("language.json", $language_array);

$studies_c = $pdo->query("select contents.content, sum(contents.studyhours) as studyhours from studies join contents on studies.id = contents.studies_id group by content")->fetchAll(PDO::FETCH_ASSOC);

$content_arr = [];

foreach ($studies_c as $s) {
  $content_arr = array_merge($content_arr, array($s['content'] => $s['studyhours']));
}

$content_array = json_encode($content_arr, JSON_UNESCAPED_UNICODE);
file_put_contents("content.json", $content_array);



// $str = $pdo->query("select content, sum(studyhours) as studyhours from studies group by content")->fetchAll(PDO::FETCH_ASSOC);
// $str = $pdo->query("select content, sum(studyhours) as studyhours from studies CROSS APPLY STRING_SPLIT(content, ', ') group by content")->fetchAll(PDO::FETCH_ASSOC);
// var_dump($str);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/reset.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" defer></script>
  <script src="./assets/js/calendar.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="./assets/js/scripts.js" defer></script>
  <title>POSSE</title>
</head>

<body>
  <header class="l-header">
    <div class="fixed_header">
      <div class="header_title">
        <div class="header_logo">
          <img src="./assets/img/logo.svg" alt="">
        </div>
        <p class="header_subtitle">4th week</p>
      </div>
      <div class="header_btn_container">
        <button class="header_btn">
          <p class="header_btn_content">記録・投稿</p>
        </button>
      </div>
    </div>
  </header>

  <main class="l-main">
    <div class="modal">
      <div class="modal_contents">
        <div class="modal_content modal_main">
          <div class="modal_close"></div>
          <form action="./index.php" method="post" id="form" name="form">
            <div class="inputs">
              <div class="input_choices">
                <div class="input_study_date">
                  <p class="date">学習日</p>
                  <input type="text" name="date" id="date">
                </div>
                <div class="input_study_contents">
                  <p class="input_study_contents_label">学習コンテンツ（複数選択可）</p>
                  <div class="checkbox_container">
                    <div class="checkbox_box">
                      <input type="checkbox" name="content[]" id="Nyobi" class="checkbox_input" value="N予備校">
                      <label for="Nyobi" class="checkbox_label">
                        <i class="default_checkbox"></i>N予備校
                      </label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="content[]" id="dotinstall" value="ドットインストール">
                      <label for="dotinstall" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        ドットインストール</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="content[]" id="posseHW" value="POSSE課題">
                      <label for="posseHW" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        POSSE課題</label>
                    </div>
                  </div>
                </div>
                <div class="input_study_languages">
                  <p class="input_study_languages_label">学習言語（複数選択可）</p>
                  <div class="checkbox_container">
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="html" value="HTML">
                      <label for="html" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        HTML</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="CSS" value="CSS">
                      <label for="CSS" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        CSS</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="JavaScript" value="JavaScript">
                      <label for="JavaScript" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        JavaScript</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="PHP" value="PHP">
                      <label for="PHP" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        PHP</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="Laravel" value="Laravel">
                      <label for="Laravel" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        Laravel</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="SQL" value="SQL">
                      <label for="SQL" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        SQL</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="SHELL" value="SHELL">
                      <label for="SHELL" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        SHELL</label>
                    </div>
                    <div class="checkbox_box">
                      <input type="checkbox" name="language[]" id="others" value="情報システム基礎知識（その他）">
                      <label for="others" class="checkbox_label">
                        <i class="default_checkbox"></i>
                        情報システム基礎知識（その他）</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="input_writings">
                <div class="input_study_hours">
                  <p class="hours">学習時間</p>
                  <input type="text" name="hours" id="hours">
                </div>
                <div class="twitter">
                  <div class="input_twitter">
                    <p class="twitter">Twitter用コメント</p>
                    <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
                  </div>
                  <div class="twitter_checkbox">
                    <input type="checkbox" name="share" id="share">
                    <label for="share" class="checkbox_label">
                      <i class="default_checkbox"></i>
                      Twitterにシェアする</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="header_btn_container">
              <div class="header_btn_modal modal_btn">
                <!-- <button type="submit" class="header_btn_content" name="submit">記録・投稿</button> -->
                <input type="submit" class="header_btn_content" name="submit" id="submit" value="記録・投稿">
              </div>
            </div>
          </form>
        </div>
        <div class="modal_content modal_loading">
          <div class="modal_close"></div>
          <div class="loader">Loading...</div>
        </div>
        <div class="modal_content modal_complete">
          <div class="modal_close"></div>
          <div class="complete_container">
            <div class="complete_visual">
              <p class="complete">AWESOME!</p>
              <div class="complete_icon_container">
                <div class="complete_icon"><i class="default_checkbox"></i>
                </div>
              </div>
            </div>
            <div class="error_content">
              <p class="error_text">記録・投稿<br>完了しました</p>
            </div>
          </div>
        </div>
        <div class="modal_content modal_calendar">
          <div class="modal_return"></div>
          <div class="modal_close"></div>
          <div class="modal_calendar_container">
            <table id="tbl">
              <thead>
                <tr class="calendar_top">
                  <th id="prev">
                    <i class="left_icon"></i>
                  </th>
                  <th id="title" colspan="5">2020/05</th>
                  <th id="next">
                    <i class="right_icon"></i>
                  </th>
                </tr>
                <tr class="days">
                  <th>Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th>Sat</th>
                </tr>
                </tr>
              </thead>
              <tbody></tbody>
              <!-- <tfoot>
              <tr>
                <td id="today" colspan="7">Today</td>
              </tr>
            </tfoot> -->
            </table>
            <div class="header_btn_container">
              <button class="calendar_btn">
                <p class="header_btn_content">決定</p>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="statistics">
      <div class="datasets">
        <div class="datasets_value">
          <div class="datasets_value_container">
            <div class="datasets_value_list">
              <p class="datasets_value_title">Today</p>
              <p class="datasets_value_num">
                <?php if (isset($day[0]['studyhours'])) {
                  echo $day[0]['studyhours'];
                } else {
                  echo '0';
                } ?></p>
              <p class="datasets_value_unit">hour</p>
            </div>
          </div>
          <div class="datasets_value_container">
            <div class="datasets_value_list">
              <p class="datasets_value_title">Month</p>
              <p class="datasets_value_num"><?php if (isset($month[0]['studyhours'])) {
                                              echo $month[0]['studyhours'];
                                            } else {
                                              echo '0';
                                            } ?></p>
              <p class="datasets_value_unit">hour</p>
            </div>
          </div>
          <div class="datasets_value_container">
            <div class="datasets_value_list">
              <p class="datasets_value_title">Total</p>
              <p class="datasets_value_num">
                <?php if (isset($total[0]['studyhours'])) {
                  echo $total[0]['studyhours'];
                } else {
                  echo '0';
                } ?></p>
              <p class="datasets_value_unit">hour</p>
            </div>
          </div>
        </div>
        <div class="datasets_bar_container">
          <canvas id="datasets_bar">
            Canvas not supported...
          </canvas>
        </div>
      </div>
      <div class="pie-charts">
        <div class="pie-languages">
          <h3 class="pie-charts_title">学習言語</h3>
          <div class="pie-charts_img">
            <canvas id="pie-charts_lang">
              Canvas not supported...
            </canvas>
          </div>
        </div>
        <div class="pie-contents">
          <h3 class="pie-charts_title">学習コンテンツ</h3>
          <div class="pie-charts_img">
            <canvas id="pie-charts_content">
              Canvas not supported...
            </canvas>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer class="l-footer">
    <div class="period">
      <!-- <button onclick="clickLeft()"><i class="left_icon"></i></button> -->
      <!-- <i class="left_icon" onclick="clickLeft()"></i> -->
      <i class="left_icon"></i>
      <p class="period_content"><?= date("Y年m月") ?></p>
      <i class="right_icon"></i>
    </div>
    <div class="header_btn_container">
      <button class="header_btn">
        <p class="header_btn_content">記録・投稿</p>
      </button>
    </div>
  </footer>
  <!-- <script>
  $("#submit").click(() => {
    $("#form").submit(() => {
      return false;
    })
  })
</script> -->
</body>

</html>