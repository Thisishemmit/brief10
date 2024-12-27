<?php
require_once 'app/helpers/errors.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="../JavaScript/tailwind.js"></script>
</head>
<body class="bg-[url('../images/BookBg.webp')] bg-cover bg-center min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="bg-black bg-opacity-10 text-white backdrop-blur-lg shadow-lg rounded-lg p-8 max-w-md w-full">
            <form action="login" method="POST" class="flex flex-col items-center shadow-lg">
                <h2 class="text-xl font-semibold">Welcome back!</h2>
                <p class="text-sm mb-5">Login to your account</p>

                <?php if (has_error('login')) : ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 w-full">
                        <p class="text-red-700"><?= get_error('login') ?></p>
                    </div>
                <?php endif; ?>

                <div class="mb-4 w-full">
                    <input 
                        placeholder="Email address"
                        type="email" 
                        id="email" 
                        name="email" 
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>
                <div class="mb-6 w-full">
                    <input 
                        placeholder="Password"
                        type="password" 
                        id="password" 
                        name="password" 
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>
                <input type="submit" name="login" value="Log in" class="w-full bg-indigo-500 text-white font-bold py-2 px-4 rounded-md duration-100 hover:bg-indigo-600">
                <hr class="my-5 w-full border-gray-300">
                <a href="/signup" class="w-full text-center bg-[#42b72a] text-white font-bold py-2 px-4 rounded-md duration-100 hover:bg-[#359922]">Create new account</a>
            </form>
        </div>
    </div>
</body>
</html>
