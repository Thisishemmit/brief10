<?php require_once 'app/helpers/errors.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reservations</title>
    <script src="/JavaScript/tailwind.js"></script>
</head>

<body>
    <div class="min-h-screen bg-gray-100">
        <main class="container mx-auto px-4 py-8">
            <?php if (has_error('delete_reservation')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-red-700"><?= get_error('delete_reservation') ?></p>
                </div>
            <?php endif; ?>

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Book Reservations</h1>
            </div>

            <?php if (empty($books)): ?>
                <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Reservations</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no books with active reservations.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    <?php foreach ($books as $book): ?>
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="aspect-[2/3] w-full relative group">
                                <img src="/images/<?= htmlspecialchars($book['cover_image']) ?>"
                                    alt="<?= htmlspecialchars($book['title']) ?>"
                                    class="w-full h-full object-cover">
                                <!-- Hover overlay with buttons -->
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center space-y-2">
                                    <button onclick="showDetails(<?= htmlspecialchars(json_encode($book)) ?>)"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-600">
                                        View Details
                                    </button>
                                    <a href="/admin/reservations/delete?book_id=<?= $book['id_book'] ?>&user_id=all"
                                        onclick="return confirm('Are you sure you want to delete all reservations for this book?')"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md text-sm hover:bg-red-600">
                                        Delete All Reservations
                                    </a>
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="font-medium text-sm truncate"><?= htmlspecialchars($book['title']) ?></h3>
                                <p class="text-xs text-gray-600 mt-1">
                                    <?= $book['reservation_count'] ?>
                                    <?= $book['reservation_count'] === 1 ? 'reservation' : 'reservations' ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>

        <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Reservations</h2>
                        <button onclick="hideDetails()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div id="modalContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetails(book) {
            const modal = document.getElementById('detailsModal');
            const content = document.getElementById('modalContent');

            content.innerHTML = `
                <div class="flex items-center mb-6">
                    <img src="/images/${book.cover_image}" alt="${book.title}" 
                         class="h-24 w-16 object-cover rounded">
                    <div class="ml-4">
                        <h3 class="font-medium text-lg">${book.title}</h3>
                        <p class="text-gray-500">${book.author || ''}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    ${book.reservations.map(res => `
                        <div class="flex items-center justify-between py-3 border-t">
                            <div>
                                <p class="font-medium">${res.user_name}</p>
                                <p class="text-sm text-gray-500">
                                    ${new Date(res.reserved_at).toLocaleDateString()}
                                </p>
                            </div>
                            <a href="/admin/reservations/delete?id=${res.id_reservation}"
                               class="text-red-600 hover:text-red-800"
                               onclick="return confirm('Are you sure you want to delete this reservation?')">
                                Delete
                            </a>
                        </div>
                    `).join('')}
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function hideDetails() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) hideDetails();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') hideDetails();
        });
    </script>
</body>

</html>