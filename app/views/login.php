
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="../JavaScript/tailwind.js"></script>
</head>
<body class="bg-[url('../images/BookBg.webp')] bg-cover bg-center min-h-screen">

    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="bg-black bg-opacity-10 backdrop-blur-lg shadow-lg rounded-lg p-8 max-w-md w-full">
            <h1 class="text-white text-center font-bold text-3xl mb-5">Login</h1>
            <form action="index.php" method="POST" class="flex flex-col">
                <div class="mb-4">
                    <input 
                        placeholder="Email address"
                        type="email" 
                        id="email" 
                        name="email" 
                        class="mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>
                <div class="mb-6">
                    <input 
                        placeholder="Password"
                        type="password" 
                        id="password" 
                        name="password" 
                        class="mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>
                <button type="submit" class="w-full bg-indigo-500 text-white font-bold py-2 px-4 rounded-md duration-100 hover:bg-indigo-600">Log in</button>
                <hr class="my-5">
                <a href="signup.php" class="min-w-20 mx-auto bg-[#42b72a] text-white font-bold py-2 px-2 rounded-md duration-100 hover:bg-[#359922]">Create new account</a> 
                <a href="#" class="text-white text-sm text-center mt-3">Login as a guest</a>  
            </form>
        </div>
        
    </div>
</body>
</html>
