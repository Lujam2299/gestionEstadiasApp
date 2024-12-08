<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de Presentación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
            position: relative;
            min-height: 100%;
        }
        .header {
            text-align: right;
            font-size: 12px;
            color: #333;
        }
        .header span {
            display: block;
        }
        .title {
            text-align: left;
            margin-top: 30px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .content {
            margin-top: 20px;
            text-align: justify;
            font-size: 13px;
        }
        .content strong {
            font-weight: bold;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 13px;
            margin-top: 30px;
        }
        .end{
            font-size:14px;
            margin-top: 0; 
            margin-bottom: 5px; 
            line-height: 1.2;
    
        }
        footer img {
            height: 75px;
            width: 80%;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('images/headerlogos.png') }}" style="width:60%; height: 100px;" alt="">
    <div class="header">
        <span>Instituto Tecnológico de Ciudad Valles</span>
        <span>Departamento de Sistemas y Computación</span>
        <span>Ciudad Valles, S.L.P., <strong>{{ $fecha }}</strong></span>
        <span>Oficio No. 069.06/0140/2022</span>
    </div>

    <div class="title">
        C.{{$titular}}<br>
        {{$cargo}} de {{$empresa}}<br>
        PRESENTE
    </div>

    <div class="content">
        <p>El Tecnológico Nacional de México Campus Ciudad Valles, tiene a bien presentar a sus finas atenciones a
        <strong>{{ $docente }}</strong>, docente adscrito(a) al departamento de <b>{{$departamento}}</b> de este Instituto, 
        quien busca desarrollar una estancia académica y de investigación en el area de <strong>{{$area}}</strong>, con la finalidad 
        de <strong>{{ $objetivo }}</strong>, del 
        <strong>{{ $inicio }}</strong> al <strong>{{ $fin }}</strong></p>

        <p>Hacemos patente nuestro sincero agradecimiento, por su buena disposición y colaboración para que nuestra docente 
        desarrolle la estancia académica y de investigación, y pueda fortalecer y aplicar sus conocimientos y las competencias, 
        así como el trabajo en el campo de acción en el que se desenvuelve como profesionista.</p>

        <p>Al vernos favorecidos con su participación en nuestro objetivo, solo nos resta manifestarle la seguridad de nuestra más 
        atenta y distinguida consideración.</p>
    </div>

    <div class="end">
        <p><strong>A T E N T A M E N T E,</strong></p>
        <p style="font-style:italic; margin-top:0;">Experiencia en educación tecnológica</p><br>
        <p><strong>HECTOR AGUILAR PONCE</strong></p>
        <p><strong>DIRECTOR</strong></p>
    </div>

    <footer class="footer">
        <img src="{{ public_path('images/footer.png') }}" style="width:90%; height: 125px;" alt="">
    </footer>

</body>
</html>