<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
    <script src="JavaScript/tailwind.js"></script>
</head>
<body class="bg-[url('../images/BookBg.webp')] bg-cover bg-center min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="bg-black bg-opacity-10 text-white backdrop-blur-lg shadow-lg rounded-lg p-8 max-w-md w-full">
            <form action="/app/controllers/signup.php" method="POST" class="flex flex-col items-center shadow-lg">
                <h2 class="text-xl font-semibold">Create a new account</h2>
                <p class="text-sm mb-5">Join us for a great experience</p>

                <div class="mb-4 w-full">
                    <input
                        placeholder="Full Name"
                        type="text"
                        id="name"
                        name="name"
                        required
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>

                <div class="mb-4 w-full">
                    <input
                        placeholder="Email Address"
                        type="email"
                        id="email"
                        name="email"
                        required
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>

                <div class="mb-4 w-full">
                    <input
                        placeholder="Password"
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>

                <div class="mb-6 w-full">
                    <input
                        placeholder="Confirm Password"
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        required
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Sign Up
                </button>

                <p class="mt-4 text-sm">
                    Already have an account? 
                    <a href="/index.php" class="text-blue-400 hover:text-blue-300">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
