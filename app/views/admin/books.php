<?php
require_once 'app/helpers/errors.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <script src="/JavaScript/tailwind.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col">
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="flex justify-between mb-6 items-center">
                <h1 class="text-2xl font-bold">Books</h1>
                <a href="/admin/books/add"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"> Add Book</a>
            </div>

            <?php if (has_error('delete_book')) : ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <p class="text-red-700">
                        <?= get_error('delete_book') ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($books as $book) : ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">

                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

</body>

</html>