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

/*ФИЛЬТРАЦИЯ*/


if(!is_numeric($summavklada) || !is_numeric($summapopolneniya) || !is_numeric($srokvklada))
{
   echo ('ПРОВЕРЬТЕ ВВЕДЕННЫЕ ДАННЫЕ :(');
   exit();
}

$summavklada = (int)$summavklada;
$srokvklada= (int)$srokvklada;
$summapopolneniya = (int)$summapopolneniya;


$summavklada = round($summavklada);
$srokvklada = round($srokvklada);
$summapopolneniya = round($summapopolneniya);


if ($summavklada < 1000 || $summavklada > 3000000  || $summapopolneniya > 3000000 || $srokvklada > 5 || $srokvklada < 1)
{
   echo ('ПРОВЕРЬТЕ ВВЕДЕННЫЕ ДАННЫЕ :-(');
   exit();
}

if ($summapopolneniya > 0 && $summapopolneniya < 1000)
{
   echo ('ПРОВЕРЬТЕ ВВЕДЕННЫЕ ДАННЫЕ :-((');
   exit();
}

$znacheniedaty = substr($znacheniedaty, 0, 10);//dd.mm.yyyy - 10 знаков

/*выделяем месяц и год*/
$startmonth = substr($znacheniedaty, 3, 2);
$startyear = substr($znacheniedaty, 6);
$startday = substr($znacheniedaty, 0, 2);

$startday = (int) $startday;
$startmonth = (int) $startmonth;
$startyear = (int) $startyear;



$srokvkladamonth = $srokvklada * 12; //количество месцев в сроке вклада 

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


