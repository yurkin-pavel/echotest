<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>КАЛЬКУЛЯТОР</title>
    <meta name="Description" content="тестовое задание">

    <meta name="Keywords" content="тест">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/datepicker-ru.js"></script>

    <script>
        $(document).ready(function() {
            $('#datepicker').datepicker();
            //деактивируем поля вкладов и кнопку 
            $("#input_add,rangeinputadd").prop("disabled", true);
            $("#rangeinputadd").prop("disabled", true);
            $("#subforezult").prop("disabled", true);

            var summapopolneniya = 0;


            /*СВЯЗЬ РАДИОКНОПКИ И ПОЛЕ ПОПОЛНЕНИЯ ВКЛАДА*/

            $("#yes").click(function() {
                $("#input_add").prop("disabled", false);
                $("#rangeinputadd").prop("disabled", false);
            })

            $("#no").click(function() {
                $("#input_add").prop("disabled", true);
                $("#rangeinputadd").prop("disabled", true);
                summapopolneniya = 0;
            })


            //КНОПКА РАССЧИТАТЬ СТАНОВИТСЯ АКТИВНОЙ ТОЛЬКО ПОСЛЕ ВЫБОРА ДАТЫ

            $('#datepicker').datepicker("option", "onSelect", function() {
                $("#subforezult").prop("disabled", false);
            });

            //КЛИК ПО КНОПКЕ "РАССЧИТАТЬ"	
            $("#subforezult").click(function() {

                var flagforsend = 1; // флаг разрешающий отсылку

                var znacheniedaty = $('input[name*="dmy"]').val();

                if (!znacheniedaty) // нет даты вклада. хоть кнопка отправки  и неактивна без ввода даты
                {
                    flagforsend = 0;
                }

                var summavklada = $('input[name*="input_summ"]').val();
				summavklada =parseInt(summavklada, 10);
                if (isNaN(summavklada) || !summavklada || summavklada < 1000 || summavklada > 3000000) //проверка суммы вклада
                {
                    flagforsend = 0;
                    $('#result').html('Сумма вклада должна быть от 1000 руб. до 3 000 000 руб.');
                }

                var srokvklada = $("#srokvklada option:selected").val();
                //тут проверять смысла нет - пользователь ограничен списком

                if ($("#yes").prop("checked") == true) {
                    summapopolneniya = $('input[name*="input_add"]').val();
					summapopolneniya =parseInt(summapopolneniya, 10);
                    if (isNaN(summapopolneniya) ||!summavklada || summapopolneniya < 1000 || summapopolneniya > 3000000) {
                        flagforsend = 0;
                        $('#result').html('Сумма пополнения должна быть от 1000 руб. до 3 000 000 руб.');
                    }
                } else {
                    summapopolneniya = 0;
                }



                //var yesno=$('input[name="yesno"]:checked').val();


                if (flagforsend == 1) //если все данные проходят отправляем
                {
                    $.post("/calc.php", {
                        znacheniedaty: znacheniedaty,
                        summavklada: summavklada,
                        srokvklada: srokvklada,
                        summapopolneniya: summapopolneniya
                    })

                    .done(function(data) {
                        $('#labelsubforezult').css("display", "inline");
						
						var formatter = new Intl.NumberFormat("ru", {
                            style: "currency",
                            currency: "RUB"
                               });
							var reslt=formatter.format(data);
			
                        $('#result').html(reslt);

                    });
                }


            })
        })
    </script>



</head>

<body id="top" data-spy="scroll" data-target=".navbar">
    <div class="container-fluid ">
        <div class="row ">
            <div class="col-12 ">

                <div class="container-fluid  d-none d-md-block mb-4">
                    <div class="row ">
                        <div class="col-md-3 d-flex  justify-content-center   justify-content-md-start   align-items-center   pl-0">
                            <img src="/img/logo.png" alt="логотип" class="w-75">
                        </div>
                        <div class="col-md-9 d-flex  justify-content-center   justify-content-md-end   align-items-center  pr-2">


                            <div class="number d-flex align-items-center justify-content-center">
                                <h3><a href="tel:+88001005005">8-800-100-5005</a><br><a href="tel:+73452522000">+7 (3452) 522-000</a></h3>
                            </div>
                        </div>
                    </div>
                </div>



                <nav class="navbar sticky-top navbar-expand-md navbar-dark   fixed-top font-weight-bold ">
                    <div class="container-fluid p-0">

                        <div class="row w-100 m-0 p-0 d-flex align-items-stretch flex-fill">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
                            <div class="navbar-nav d-flex w-100 justify-content-around align-items-stretch">
                                <ul class="collapse navbar-collapse w-100 p-0  nav-tabs list-unstyled" id="collapsibleNavbar">

                                    <li class="nav-item   flex-fill text-center" data-toggle="collapse" data-target=".navbar-collapse.show">
                                        <a class="nav-link   " href="#">Кредитная карта</a>
                                    </li>

                                    <li class="nav-item  flex-fill text-center ">
                                        <a class="nav-link " href="#">Вклады</a>
                                    </li>

                                    <li class="nav-item  flex-fill text-center" data-toggle="collapse" data-target=".navbar-collapse.show">
                                        <a class="nav-link   " href="#">Дебетовая карта</a>
                                    </li>
                                    <li class="nav-item clpsnav flex-fill text-center" data-toggle="collapse" data-target=".navbar-collapse.show">
                                        <a class="nav-link" href="#">Страхование</a>
                                    </li>
                                    <li class="nav-item flex-fill   text-center" data-toggle="collapse" data-target=".navbar-collapse.show">
                                        <a class="nav-link " href="#" onclick="location.href='#';">Друзья</a>
                                    </li>
                                    <li class="nav-item flex-fill last  text-center " data-toggle="collapse" data-target=".navbar-collapse.show">
                                        <a class="nav-link" href="#" onclick="location.href='#';">Интернет-банк</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>


                <div class="row d-block d-md-none mt-1">
                    <div class="col-12 d-flex  justify-content-center   justify-content-md-start   align-items-center">
                        <img src="/img/logo.png" alt="логотип">
                    </div>
                    <div class="col-12 d-flex  justify-content-center   justify-content-md-end   align-items-center">

                        <div class="number" style="display:flex; align-items: center;">
                            <h4><a href="tel:+88001005005">8-800-100-5005</a><br><a href="tel:+73452522000">+7 (3452) 522-000</a></h4>
                        </div>
                    </div>
                </div>

                <!--ХЛЕБНЫЕ КРОШКИ-->

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Главная</a></li>
                        <li class="breadcrumb-item"><a href="#">Вклады</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Калькулятор</li>
                    </ol>
                </nav>

                <!--КАЛЬКУЛЯТОР-->

                <div class="row">

                    <div class="col-12 col-md-8  mb-5 mt-2 mt-md-5 ml-0 ml-md-4 calk">
                        <div class="container-fluid  ">
                            <div class="row">
                                <div class="col-12 col-md-1"></div>
                                <div class="col-12 col-md-3">
                                    <h3 class="mb-5 mt-2">Калькулятор</h3>
                                </div>
                                <div class="col-12 col-md-8"></div>
                            </div>
                        </div>


                        <form method="post">
                            <div class="container-fluid  ">
                                <div class="form-group row">
                                    <div class="col-12 col-md-3">
                                        <p class="text-left text-md-right">Дата оформления вклада </p>
                                    </div>
                                    <div class="col-12 col-md-2"><input type="text" class="form-control" id="datepicker" name="dmy" readonly placeholder="ДД.ММ.ГГГГ"></div>
                                    <div class="col-12 col-md-4"></div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-3">
                                        <p class="text-left text-md-right">Сумма вклада</p>
                                    </div>
                                    <!-- ИНПУТ И БЕГУНОК СУММА ВКЛАДА -->
                                    <div class="col-12 col-md-2 mb-2">
                                        <input type="number" min="1000" max="3000000" step="1" class="form-control delarrow" name="input_summ" id="input_summ" onchange="rangeinputsumm.value = input_summ.value" value="1000" placeholder="1000">
                                    </div>
                                    <div class="col-12 col-md-5 mt-1">
                                        <input type="range" oninput="input_summ.value = rangeinputsumm.value" min="1000" max="3000000" value="10000" id="rangeinputsumm" step="1000" onchange="input_summ.value = rangeinputsumm.value">
                                        <label for="rangeinputsumm" class="rangetext font-weight-light font-italic">1 тыс. руб.</label> <label for="rangeinputsumm" class="rangetext font-weight-light float-right font-italic">3 000 000</label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-3">
                                        <p class="text-left text-md-right">Срок вклада</p>
                                    </div>
                                    <div class="col-12 col-md-2"> <select class="form-control" size="1" id="srokvklada" name="srokvklada">
    <option selected value="1">1 год</option>
    <option value="2">2 года</option>
    <option value="3">3 года</option>
    <option value="4">4 года</option>
    </select></div>
                                    <div class="col-12 col-md-5"></div>
                                </div>

                                <!-- Переключатели ДА-НЕТ -->
                                <div class="form-group  row">
                                    <div class="col-12 col-md-3">
                                        <p class="text-left text-md-right">Пополнение вклада</p>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <input class="form-check-input-inline mr-1 " type="radio" checked name="yesno" id="no" value="0">
                                        <span>Нет</span>
                                        <input class="form-check-input-inline mr-1  " type="radio" name="yesno" id="yes" value="1">
                                        <span>Да</span>
                                    </div>
                                    <div class="col-12 col-md-5"></div>
                                </div>

								
                                <div class="form-group row">
                                    <div class="col-12 col-md-3 ">
                                        <p class="text-left text-md-right">Сумма пополнения вклада</p>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2">
                                        <!-- ИНПУТ И БЕГУНОК СУММА ПОПОЛНЕНИЯ ВКЛАДА -->
                                        <input type="number" min="1000" max="3000000" step="1" class="form-control delarrow" name="input_add" id="input_add" onchange="rangeinputadd.value = input_add.value" value="1000" placeholder="1000"></div>
                                    <div class="col-12 col-md-5 mt-1">
                                        <input type="range" oninput="input_add.value = rangeinputadd.value" min="1000" max="3000000" value="10000" id="rangeinputadd" step="1000" onchange="input_add.value = rangeinputadd.value">
                                        <label for="rangeinputadd" class="rangetext font-weight-light font-italic">1 тыс. руб.</label> <label for="rangeinputadd" class="rangetext font-weight-light float-right font-italic">3 000 000</label>
                                    </div>
                                </div>
								 <div class="container-fluid ">
                                     <div class="row ">
                                       <div class="d-flex col-12 justify-content-center col-md-2 justify-content-md-end">
                                <input type="button" value="Рассчитать" class="btn  btn-start mb-2" id="subforezult"> 
								 </div>
								  <div class="col-12 col-md-10">
								  <label class="form-check-label mx-11" id="labelsubforezult">Результат:</label> 
								<span id="result"></span>
								 </div> </div> </div>
								
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="container-fluid fixed-bottom">

        <div class="row">
            <div class="col-12 d-none d-md-block">
                <nav class="navbar navbar-expand-md navbar-dark ">
                    <div class="container-fluid p-0 ">
                        <div class="row w-100 m-0 p-0 d-flex ">
                            <div class=" col-12 navbar-nav d-flex w-100 .align-content-center py-3">
                                <span>
          <a class="nav-link-bottom" href="#">Кредитная карта</a>
        </span>
                                <span>
            <a class="nav-link-bottom" href="#">Вклады</a>
		 </span>
                                <span>
          <a class="nav-link-bottom" href="#">Дебетовая карта</a>
        </span>
                                <span>
          <a class="nav-link-bottom" href="#">Страхование</a>
        </span>
                                <span>
          <a class="nav-link-bottom" href="#"  >Друзья</a>
        </span>
                                <span>
          <a class="nav-link-bottom" href="#" >Интернет-банк</a>
        </span>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

</body>
</html>
