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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
    <div class="min-h-screen flex <?= is_logged_in() ? 'flex-row' : 'flex-col' ?> bg-gray-100">
        <?php if (is_logged_in()): ?>
            <?php require 'app/views/parts/sidebar.php'; ?>
        <?php endif; ?>
        
        <main class="container mx-auto px-4 py-8 max-w-7xl">
            <div class="flex justify-between mb-6 items-center">
                <h1 class="text-2xl font-bold text-gray-800">Books Management</h1>
                <div class="flex items-center space-x-4">
                    <input type="text" id="searchInput" placeholder="Search books..." class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button id="clearButton" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200">
                        Clear
                    </button>
                </div>



                <div>
                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="text-gray-700">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                    <?php else:  ?>
                        <a href="/login"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                            Login
                        </a>
                        <a href="/signup"
                            class="bg-green-600 text-white px-4 py-2 rounded-md ml-5 hover:bg-green-700 transition duration-200">
                            Signup
                        </a>
                    <?php endif; ?>
                </div>
            </div>


            <?php if (has_error('delete_book')) : ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <p class="text-red-700"><?= get_error('member_req_bor') ?></p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-3" id="books">
                <?php foreach ($books as $book) : ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200 cursor-pointer max-w-[180px] w-full mx-auto"
                        onclick="showBookDetails(<?= htmlspecialchars(json_encode([
                                                        'id' => $book->getId(),
                                                        'title' => $book->getTitle(),
                                                        'author' => $book->getAuthor(),
                                                        'category' => $book->getCategory(),
                                                        'summary' => $book->getSummary(),
                                                        'cover' => $book->getCoverImage()
                                                    ])) ?>)" id="book">
                        <div class="relative pb-[150%]">
                            <img src="/images/<?= $book->getCoverImage() ?>"
                                alt="<?= htmlspecialchars($book->getTitle()) ?>"
                                class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="p-2">
                            <h3 class="font-semibold text-sm mb-0.5 text-gray-800 truncate"><?= htmlspecialchars($book->getTitle()) ?></h3>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-600 text-xs truncate flex-1"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                <?php if (is_logged_in()): ?>
                                    <?php if ($book->getStatus() === 'available'): ?>
                                        <?php if (!isBookRequested($book->getId(), $allPendingReqs)): ?>
                                            <button onclick="event.stopPropagation(); openBorrowModal(<?= $book->getId() ?>)"
                                                class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition duration-200">
                                                Borrow
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-600">Requested</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (!isBookReserved($book->getId(), $allReservations)): ?>
                                            <button onclick="event.stopPropagation(); openReserveModal(<?= $book->getId() ?>)"
                                                class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition duration-200">
                                                Reserve
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-600">Reserved</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                                <?php if (is_logged_in()): ?>
                                    <?php if ($book->getStatus() === 'available'): ?>
                                        <?php if (!isBookRequested($book->getId(), $allPendingReqs)): ?>
                                            <button onclick="event.stopPropagation(); openBorrowModal(<?= $book->getId() ?>)"
                                                class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition duration-200">
                                                Borrow
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-600">Requested</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (!isBookReserved($book->getId(), $allReservations)): ?>
                                            <button onclick="event.stopPropagation(); openReserveModal(<?= $book->getId() ?>)"
                                                class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition duration-200">
                                                Reserve
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-600">Reserved</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="borrowModal" class="modal" onclick="if(event.target === this) hideBorrowModal()">
        <div class="bg-white m-auto rounded-lg max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Select Borrow Period</h2>
                    <button onclick="hideBorrowModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 mb-4">I want to borrow this book for :</p>
                <div class="flex space-x-4">
                    <button onclick="confirmBorrow(5)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        5 Days
                    </button>
                    <button onclick="confirmBorrow(10)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        10 Days
                    </button>
                    <button onclick="confirmBorrow(15)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        15 Days
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="reserveModal" class="modal" onclick="if(event.target === this) hideReserveModal()">
        <div class="bg-white m-auto rounded-lg max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Select Reservation Period</h2>
                    <button onclick="hideReserveModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 mb-4">I want to reserve this book for :</p>
                <div class="flex space-x-4">
                    <button onclick="confirmReserve(5)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        5 Days
                    </button>
                    <button onclick="confirmReserve(10)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        10 Days
                    </button>
                    <button onclick="confirmReserve(15)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        15 Days
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBorrowModal(bookId) {
            window.currentBookId = bookId;
            document.getElementById('borrowModal').classList.add('show');
        }

        function hideBorrowModal() {
            document.getElementById('borrowModal').classList.remove('show');
        }

        function confirmBorrow(days) {
            const bookId = window.currentBookId;
            console.log(`Borrowing book ID ${bookId} for ${days} days`);
            hideBorrowModal();

            window.location = `/books/borrow?id=${bookId}&days=${days}`;


        }

        function showBookDetails(book) {
            document.getElementById('modalTitle').textContent = book.title;
            document.getElementById('modalAuthor').textContent = book.author;
            document.getElementById('modalCategory').textContent = book.category;
            document.getElementById('modalSummary').textContent = book.summary;
            document.getElementById('modalCover').src = `/images/${book.cover}`;
            document.getElementById('bookModal').classList.add('show');
        }

        function hideBookDetails() {
            document.getElementById('bookModal').classList.remove('show');
        }

        function openReserveModal(bookId) {
            window.currentBookId = bookId;
            document.getElementById('reserveModal').classList.add('show');
        }

        function hideReserveModal() {
            document.getElementById('reserveModal').classList.remove('show');
        }

        function confirmReserve(days) {
            const bookId = window.currentBookId;
            hideReserveModal();
            window.location = `/books/reserve?id=${bookId}&days=${days}`;
        }

        document.getElementById('clearButton').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';

            const oldbooks = document.querySelectorAll('#books > div');
            const newbooks = document.querySelectorAll('.search_book');
            oldbooks.forEach(book => {
                book.style.display = 'block';
            });
            newbooks.forEach(book => {
                book.style.display = 'none';
            });
        });

        document.getElementById('searchInput').addEventListener('input', function() {

            const query = this.value;
            if (query.length > 2) {
                fetch(`/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        const booksContainer = document.getElementById('books');
                        const oldbooks = document.querySelectorAll('#books > div');

                        oldbooks.forEach(book => {
                            book.style.display = 'none';
                        });
                        if (data.length > 0) {
                            data.forEach(book => {
                                const bookDiv = document.createElement('div');
                                bookDiv.className =
                                    'search_book bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200 cursor-pointer max-w-[180px] w-full mx-auto';

                                bookDiv.onclick = () => showBookDetails({
                                    id: book.id,
                                    title: book.title,
                                    author: book.author,
                                    category: book.category,
                                    summary: book.summary,
                                    cover: book.cover_image
                                });

                                bookDiv.innerHTML = `
                            <div class="relative pb-[150%]">
                                <img src="/images/${book.cover_image}"
                                     alt="${book.title}"
                                     class="absolute inset-0 w-full h-full object-cover">
                            </div>
                            <div class="p-2">
                                <h3 class="font-semibold text-sm mb-0.5 text-gray-800 truncate">${book.title}</h3>
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-600 text-xs truncate flex-1">${book.author}</p>
                                    ${book.status === 'available' ? `
                                        <button onclick="event.stopPropagation(); openBorrowModal(${book.id})"
                                                class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition duration-200">
                                            Borrow
                                        </button>` : ''}
                                </div>
                            </div>`;
                                booksContainer.appendChild(bookDiv);
                            });
                        }
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            }
        });
    </script>
</body>

</html>