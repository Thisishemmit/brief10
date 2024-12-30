<?php
require_once 'app/helpers/errors.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Management</title>
    <script src="/JavaScript/tailwind.js"></script>
    <style>
        .book-cover {
            aspect-ratio: 1.5/1;
            object-fit: cover;
            width: 100%;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal.show {
            display: flex;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <main class="container mx-auto px-4 py-8 max-w-7xl">
            <div class="flex justify-between mb-6 items-center">
                <h1 class="text-2xl font-bold text-gray-800">Books Management</h1>
                <a href="/admin/books/add"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                    Add New Book
                </a>
            </div>

            <?php if (has_error('delete_book')) : ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <p class="text-red-700"><?= get_error('delete_book') ?></p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <?php foreach ($books as $book) : ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200 cursor-pointer max-w-[180px] w-full mx-auto"
                         onclick="showBookDetails(<?= htmlspecialchars(json_encode([
                             'id' => $book->getId(),
                             'title' => $book->getTitle(),
                             'author' => $book->getAuthor(),
                             'category' => $book->getCategory(),
                             'summary' => $book->getSummary(),
                             'cover' => $book->getCoverImage()
                         ])) ?>)">
                        <div class="relative pb-[150%]">
                            <img src="/images/<?= $book->getCoverImage() ?>"
                                 alt="<?= htmlspecialchars($book->getTitle()) ?>"
                                 class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="p-2">
                            <h3 class="font-semibold text-sm mb-0.5 text-gray-800 truncate"><?= htmlspecialchars($book->getTitle()) ?></h3>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-600 text-xs truncate flex-1"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                <span class="text-xs px-1.5 py-0.5 rounded-full <?= $book->getStatus() === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($book->getStatus()) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>

        <div id="bookModal" class="modal" onclick="if(event.target === this) hideBookDetails()">
            <div class="bg-white m-auto rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                        <button onclick="hideBookDetails()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <img id="modalCover" class="w-full rounded-lg" alt="Book cover">
                        <div>
                            <p class="text-gray-600 mb-2">Author: <span id="modalAuthor" class="text-gray-800"></span></p>
                            <p class="text-gray-600 mb-2">Category: <span id="modalCategory" class="text-gray-800"></span></p>
                            <p class="text-gray-600 mb-4">Summary:</p>
                            <p id="modalSummary" class="text-gray-800 mb-6"></p>
                            <div class="flex space-x-4">
                                <a id="editButton" href="" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                                    Edit Book
                                </a>
                                <form id="deleteForm" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200">
                                        Delete Book
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBookDetails(book) {
            document.getElementById('modalTitle').textContent = book.title;
            document.getElementById('modalAuthor').textContent = book.author;
            document.getElementById('modalCategory').textContent = book.category;
            document.getElementById('modalSummary').textContent = book.summary;
            document.getElementById('modalCover').src = `/images/${book.cover}`;
            document.getElementById('editButton').href = `/admin/books/edit?id=${book.id}`;
            document.getElementById('deleteForm').action = `/admin/books/delete?id=${book.id}`;
            document.getElementById('bookModal').classList.add('show');
        }

        function hideBookDetails() {
            document.getElementById('bookModal').classList.remove('show');
        }
    </script>
</body>

</html>
