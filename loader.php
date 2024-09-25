<html>
<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
<style>
        div.imagecontainer {
            display: flex; 
            justify-content: center; /*Centers Horizontally*/
            align-items: center; /*Centers vertically*/
            height: 100dvh; /*Full height of viewport*/
        }

        div.loader{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        div.imageholder{
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6rem;
        }

        div.progress {
            width: 15rem;
        }

    </style>
</head>
<body>
    <div class="loader">
        <div class="imagecontainer">
        <div class="imageholder">
            <img class="imageload" style="width: 8rem" src="img/logoGreen_noBackground.png">
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        </div>
        </div>
    </div>
</body>
<script src="js/theme.js"></script>
</html>