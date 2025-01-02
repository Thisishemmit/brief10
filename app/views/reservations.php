<?php require_once 'app/helpers/errors.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <script src="/JavaScript/tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="min-h-screen flex flex-row bg-gray-100">
        <?php require 'app/views/parts/sidebar.php'; ?>
        <main class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">My Reserved Books</h1>

            <?php if (empty($reserved_books)): ?>
                <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Reserved Books</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't reserved any books yet.</p>
                </div>
            <?php else: ?>
                <div class="grid gap-4">
                    <?php foreach ($reserved_books as $reservation): ?>
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                <div class="flex items-center space-x-4">
                                    <img src="/images/<?= htmlspecialchars($reservation['book']->getCoverImage()) ?>" 
                                         alt="Book cover"
                                         class="w-16 h-24 object-cover rounded">
                                    <div>
                                        <h3 class="font-medium"><?= htmlspecialchars($reservation['book']->getTitle()) ?></h3>
                                        <p class="text-sm text-gray-600"><?= htmlspecialchars($reservation['book']->getAuthor()) ?></p>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-600">
                                    <p>Reserved on: <?= date('M d, Y', strtotime($reservation['reserved_at'])) ?></p>
                                    <p>Due Date: <?= date('M d, Y', strtotime($reservation['due_at'])) ?></p>
                                </div>

                                <div class="flex justify-end">
                                    <a href="/reservations/delete?id=<?= $reservation['id_reservation'] ?>" 
                                       class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                       onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                        Cancel Reservation
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>