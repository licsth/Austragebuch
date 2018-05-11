<html>
<head>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Nutzer registrieren</title>
</head>
<body role="document" class="form">
    
    <div class="container theme-showcase" role="main">
        <br>
            <div class="col-lg-4 col-md-6 col-sm-8 center-block">
                <h2 class="hi">Nutzer registrieren</h2>
                <form method="post" action="processing/register.php">
                    <div class="form-group">
                    <!--<input type="file" draggable="false" name="file"><br>-->
                        <label for="file">Hier .csv-Datei ablegen</label>
                        <input type="file" class="fileField" draggable="true" name="file">
                        </div>
                    <button class="btn btn-lg btn-primary btn-block hi" type="submit">Okay</button>
                </form>
            </div>
        </div>
</body>
</html>