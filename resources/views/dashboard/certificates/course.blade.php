<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificado do Curso</title>
    <style>
        body{
            font-family: Roboto, Arial, Helvetica, sans-serif;
            margin: 0;
            font-size: 11pt;
        }
        @page{
            margin: 0 0 0 0;
            /* body{
                display: flex;
            }
            #body{
                flex: 1 0 auto;
            } */
        }
        .title-box {
            background-color: #96a6e0;
            text-align: center;
            padding: 0.75rem 0;
            font-weight: 700;
            text-transform: 'uppercase'
        }
        .box{
            border: 1px solid black;
            padding: 15px;
            margin: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="title-box">
        <h1 style="text-transform: uppercase;">Certificado de Conclusão do Curso de {{$cerficate->course}}</h1>
        <img src="{{public_path($cerficate->schoolPhoto)}}" alt="Escola" style="width: 50px;">
    </div>
    <div class="box">
        <p style="font-size: 1.25rem;">Programa de estudos online de {{$cerficate->schoolName}} no curso de {{$cerficate->course}} concluido por</p> 
        <h1 style="margin-bottom: 0;font-weight: 700;text-transform: uppercase;">{{$cerficate->student}}</h1>
        <br>
        <p style="text-align: center;"><span>Data - {{$cerficate->date}}</span><br><img src="{{public_path('/assets/img/procul.jpg')}}" alt="Procul Discipulus" style="width: 100px;"></p>
    </div>
</body>
</html>
    