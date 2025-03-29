<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-cover bg-no-repeat h-screen flex items-center justify-center" style="background-image: url('cpc.jpg'); background-position: center top;">
    <div class="absolute top-4 right-4">
        <img src="logo.jpg" alt="Logo" class="w-20 h-auto rounded-full">
    </div>
    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-lg p-6 w-full max-w-md mx-auto">
        <h1 class="text-center text-black text-2xl font-semibold mb-8">BSIT-2C ATTENDANCE CHECKER</h1>
        <div>
            <a href="form.php">
                <button class="bg-green-700 text-white py-2 px-4 w-full rounded hover:bg-green-600">ADD NEW RECORD</button>
            </a>
        </div>
        <div class="mt-4">
            <a href="finished_attendance.php">
                <button class="bg-red-900 text-white py-2 px-4 w-full rounded hover:bg-red-600">VIEW FINISHED ATTENDANCE</button>
            </a>
        </div>
    </div>
</body>

</html>
