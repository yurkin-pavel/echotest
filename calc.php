<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

/*

Формула с капитализаций процентов по вкладу:

4.5.1 summn = summn-1 + (summn-1 + summadd)daysn(percent / daysy)

4.5.2 где summn – сумма на счете на месяц n (руб),

4.5.3 summn-1 – сумма на счете на конец прошлого месяца

4.5.4 summadd – сумма ежемесячного пополнения

4.5.5 daysn – количество дней в данном месяце, на которые приходился вклад

4.5.6 percent – процентная ставка банка - 10%

4.5.7 daysy – количество дней в году.


*/

if (!($_POST['znacheniedaty']) || !($_POST['summavklada']) || !($_POST['srokvklada'])) {
   echo ('ПРОВЕРЬТЕ ВВЕДЕННЫЕ ДАННЫЕ :(');
   exit();
}

$znacheniedaty = $_POST['znacheniedaty'];
$summavklada = $_POST['summavklada'];
$srokvklada = $_POST['srokvklada'];
$summapopolneniya = $_POST['summapopolneniya'];

if ($summavklada < 1000 || $summavklada > 3000000  || $summapopolneniya > 3000000) {
   echo ('ПРОВЕРЬТЕ ВВЕДЕННЫЕ ДАННЫЕ :(');
   exit();
}

/*ФИЛЬТРАЦИЯ*/
$summapopolneniya = (int) $summapopolneniya;
$summavklada = (int) $summavklada;

$summavklada = chisla($summavklada, 7);
$summapopolneniya = chisla($summapopolneniya, 7);
//echo($summapopolneniya);

$srokvklada = chisla($srokvklada, 1);

/*выделяем месяц и год*/
$startmonth = substr($znacheniedaty, 3, 2);
$startyear = substr($znacheniedaty, 6);
$startday = substr($znacheniedaty, 0, 2);

$startday = (int) $startday;
$startmonth = (int) $startmonth;
$startyear = (int) $startyear;



$srokvkladamonth = $srokvklada * 12; //количество месцев в сроке вклада 



//echo('DATA VKLADA - '.$startday.'/'.$startmonth.'/'.$startyear.'<br>');

/*ТУТ БЫЛ КУСОК КОТОРЫЙ РАСЧИТЫВАЛ ПРОЦЕНТ ПО ВКЛАДУ ОТДЕЛЬНО ДЛЯ МЕСЯЦЕВ ОТКРЫТИЯ И ЗАКРЫТИЯ ВКЛАДА
НА СЛУЧАЙ ЕСЛИ ЭТО ФЕВРАЛИ И В НИХ РАЗНОЕ КОЛИЧЕСТВО ДНЕЙ. НО В ЗАДАНИИ ЭТОГО НЕ БЫЛО ПОЭТОМУ УБРАЛ
*/


$month = $startmonth + 1;
/*проценты начинаем считать со следующего месяца от месяца вклада. 
если вклад сделан 7.01.2020 на год то считаем с февраля 2020 по февраль 2012. 
это как раз год 24 дня января в месяце вклада + 11 полных месяцев + 7 дней в  следующем январе
 */
$year = $startyear; //сохранил дату открытия вклада для другоговарианта подсчета

$daysy = visokos($startyear); //количество дней в этом году
$summn = $summavklada;

$n = 1;

while ($n <= $srokvkladamonth) {

   if ($month > 12) //расчет для следующего по порядку года
   {
      //echo('<br>hpn! btch!<br> ');
      $year++;
      $month = 1;
      $daysy = visokos($year); //количество дней в этом году
   }

   $daysn = monthdays($month, $year); //количество дней в этом месяце

   /*ПОСКОЛЬКУ ОГОВОРОК В ЗАДАНИИ НЕ БЫЛО, ТО СЧИТАЕМ, ЧТО ПОПОЛНЕНИЕ ВКЛАДА БЫЛО КАЖДЫЙ МЕСЯЦ - ВКЛЮЧАЯ И МЕСЯЦ ВКЛАДА.
Т,Е ЗА ГОД - 12 ПОПОЛНЕНИЙ
*/

   $addmonth = ($summn + $summapopolneniya) * $daysn * (0.1 / $daysy); //прирост вклада по процентам в этом месяце
   $addmonth = round($addmonth, 2); // округляем до двух знаков
   $summn = $summn + $addmonth + $summapopolneniya;

   //echo(' DAY - '.$daysn.'<br>'.$daysy.'<br>'.$addmonth.'<br>'.$summn.'<br>==============END MONTH ==================<br>');

   $month++;
   $n++;
}

$summn = (int) $summn;
echo ($summn); //вывод результата
exit();



/*количество дней в месяце*/

function monthdays($mn, $ye)
{
   $numdays = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

   //date('L', mktime(0, 0, 0, 1, 1, $year));

   if ($ye % 4 == 0 && $ye % 100 != 0 || $ye % 400 == 0) {
      $numdays[2]++;
   }

   return $numdays[$mn];
}


/*количество дней в годут*/

function visokos($ye)
{

   if ($ye % 4 == 0 && $ye % 100 != 0 || $ye % 400 == 0) {
      return 366; //високсный
   } else {
      return 365; //невисоксный
   }
}


function chisla($filtruem, $lengs)
{
   $filtruem = trim($filtruem);
   $filtruem = strip_tags($filtruem);
   if ($lengs) {
      $filtruem = substr($filtruem, 0, $lengs);
   }
   $filtruem = htmlspecialchars($filtruem);
   $filtruem = filter_var($filtruem, FILTER_SANITIZE_NUMBER_INT);
   $filtruem = str_replace('+', '', $filtruem);
   $filtruem = str_replace('-', '', $filtruem);
   $filtruem = intval($filtruem);
   return  $filtruem;
}


function stroki($filtruem, $lengs)
{
   $filtruem = trim($filtruem);
   $filtruem = strip_tags($filtruem);
   $filtruem = str_replace('+', '', $filtruem);
   //$filtruem=str_replace('-','',$filtruem);
   if ($lengs) {
      $filtruem = substr($filtruem, 0, $lengs);
   }
   //$filtruem=htmlspecialchars($filtruem);
 // деприкейт  $filtruem = filter_var($filtruem, FILTER_SANITIZE_MAGIC_QUOTES);
   $filtruem = str_ireplace('select', '*', $filtruem);
   $filtruem = str_ireplace('from', '*', $filtruem);
   $filtruem = str_ireplace('update', '*', $filtruem); //SHOW TABLES DROP WHERE ORDER CHANGE TABLE INTO CREATE USE
   $filtruem = str_ireplace('show', '*', $filtruem);
   $filtruem = str_ireplace('table', '*', $filtruem);
   $filtruem = str_ireplace('drop', '*', $filtruem);
   $filtruem = str_ireplace('where', '*', $filtruem);
   $filtruem = str_ireplace('order', '*', $filtruem);
   $filtruem = str_ireplace('change', '*', $filtruem);
   $filtruem = str_ireplace('into', '*', $filtruem);
   $filtruem = str_ireplace('create', '*', $filtruem);
   $filtruem = str_ireplace('use', '*', $filtruem);
   return  $filtruem;
}
