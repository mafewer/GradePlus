<html>
<head>
    <style>
        @keyframes rotating {
            0%   {transform: rotate(0deg)}
            100% {transform: rotate(360deg)}  
        }
        .imagecontainer {
            display: flex; 
            justify-content: center; /*Centers Horizontally*/
            align-items: center; /*Centers vertically*/
            height: 100vh; /*Full height of viewport*/
            animation-name: rotating;
            animation-duration: 2s;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        .loader{
            background-color: white !important;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <title>Loader</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body>
    <div class="loader">
        <div class="imagecontainer">
            <img style="width: 15rem" src="img/logoGreen.png">
        </div>
    </div>
</body>

</html>