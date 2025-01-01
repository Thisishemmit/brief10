<?php require_once 'app/helpers/errors.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Statistics</title>
    <script src="/JavaScript/tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="min-h-screen flex flex-row bg-gray-100">
        <?php require 'app/views/parts/sidebar.php'; ?>
        <main class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-8">Library Statistics</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Most Borrowed Books -->
                <section class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4">Most Borrowed Books</h2>
                    <?php if (empty($mostBorrowedBooks)): ?>
                        <p class="text-gray-500">No borrowing data available</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($mostBorrowedBooks as $item):
                                $book = $item['book'];
                            ?>
                                <div class="flex items-center space-x-4">
                                    <img src="/images/<?= htmlspecialchars($book->getCoverImage()) ?>"
                                         alt="Book cover"
                                         class="w-12 h-16 object-cover rounded">
                                    <div class="flex-1">
                                        <h3 class="font-medium"><?= htmlspecialchars($book->getTitle()) ?></h3>
                                        <p class="text-sm text-gray-600"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= $item['borrow_count'] ?> times
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Most Active Users -->
                <section class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4">Most Active Users</h2>
                    <?php if (empty($mostActiveUsers)): ?>
                        <p class="text-gray-500">No user activity data available</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($mostActiveUsers as $item):
                                $user = $item['user'];
                            ?>
                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <h3 class="font-medium"><?= htmlspecialchars($user->getName()) ?></h3>
                                        <p class="text-sm text-gray-600"><?= htmlspecialchars($user->getEmail()) ?></p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <?= $item['borrow_count'] ?> books
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
