<?php
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
    <script src="../JavaScript/tailwind.js"></script>
</head>
<body class="bg-[url('../images/BookBg.webp')] bg-cover bg-center min-h-screen">

    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="bg-black bg-opacity-10 text-white backdrop-blur-lg shadow-lg rounded-lg p-8 max-w-md w-full">

            <form action="signup.php" method="POST" class="flex flex-col items-center shadow-lg">
                <h2 class="text-xl font-semibold">Create a new account</h2>
                <p class="text-sm mb-5">Join us for a great experience</p>

                <div class="mb-4 w-full">
                    <input 
                        placeholder="Full Name" 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?php echo htmlspecialchars($name); ?>"
                        
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                        <span class="text-red-500 text-sm"><?php echo $error_name; ?></span>
                </div>
                
                <div class="mb-4 w-full">
                    <input 
                        placeholder="Email Address" 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($email); ?>"
                        
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                    <span class="text-red-500 text-sm"><?php echo $error_email; ?></span>
                </div>

                <div class="mb-4 w-full">
                    <input 
                        placeholder="Password" 
                        type="password" 
                        id="password" 
                        name="password" 
                        
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                    <span class="text-red-500 text-sm"><?php echo $error_password; ?></span>
                </div>

                <div class="mb-6 w-full">
                    <input 
                        placeholder="Confirm Password" 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        
                        class="text-black mt-1 p-4 h-10 block w-full border-gray-300 rounded-md shadow-sm border">
                    <span class="text-red-500 text-sm"><?php echo $error_confirm_password; ?></span>
                </div>

                <hr class="my-5 border w-full">

                <button type="submit" class="min-w-44 bg-[#42b72a] text-white font-bold  py-2 px-2 rounded-md mx-auto duration-100 hover:bg-[#359922]"> SIgn Up</button>
                <p class="mt-4 text-white duration-100 hover:text-slate-300"><a href="index.php">Already have an account?</a></p>
            </form>

        </div>
    </div>

</body>
</html>

