<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - View List of Books</title>
    <script src="/JavaScript/tailwind.js"></script>
</head>
<body class="bg-[url('/images/BookBg.webp')] bg-cover bg-center min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="bg-black bg-opacity-10 text-white backdrop-blur-lg shadow-lg rounded-lg p-8 max-w-4xl w-full">
            <h2 class="text-xl font-semibold text-center mb-6">Available Books</h2>

            <?php if(empty($allBooks)) : ?>

                <div>
                    <p class="text-center">No Books</p>
                </div>

            <?php else : ?>

            <table class="w-full text-left border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-4 border border-gray-300">Title</th>
                        <th class="p-4 border border-gray-300">Author</th>
                        <th class="p-4 border border-gray-300">Category</th>
                        <th class="p-4 border border-gray-300">Availability</th>
                        <th class="p-4 border border-gray-300">Details</th>
                    </tr>
                </thead>

                <tbody class="bg-white text-black">

                    <?php foreach($allBooks as $book) : ?>
                        <tr class="border-b border-gray-300">
                            <td class="p-4 border border-gray-300"><?= $book->getTitle() ?></td>
                            <td class="p-4 border border-gray-300"><?= $book->getAuthor() ?></td>
                            <td class="p-4 border border-gray-300"><?= $book->getCategory() ?></td>
                            <td class="p-4 border border-gray-300"><?= $book->getStatus() ?></td>
                            <td class="p-4 border border-gray-300"><Button id="detailsBtn">View details</Button></td>

                        </tr>
                    <?php endforeach; ?>
                        
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
