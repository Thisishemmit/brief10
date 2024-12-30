<?php
require_once 'app/helpers/errors.php';
require_once 'app/models/User.php';
require_once 'app/models/Book.php';

$user = new User($db);
$book = new Book($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowings Management</title>
    <script src="/JavaScript/tailwind.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col">
        <main class="flex-grow container mx-auto px-4 py-8">
            <?php if (has_error('approve_request')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-red-700"><?= get_error('approve_request') ?></p>
                </div>
            <?php endif; ?>

            <?php if (has_error('reject_request')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-red-700"><?= get_error('reject_request') ?></p>
                </div>
            <?php endif; ?>

            <?php if (has_error('request_not_found')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-red-700"><?= get_error('request_not_found') ?></p>
                </div>
            <?php endif; ?>

            <!-- Borrow Requests Section -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Borrow Requests</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <?php if (empty($borrowRequests)): ?>
                        <div class="col-span-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg p-8">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">No borrow requests</h3>
                                <p class="mt-1 text-sm text-gray-500">There are no pending borrow requests at this time.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($borrowRequests as $request):
                            $user->findById($request['id_user']);
                            $book->findById($request['id_book']);
                        ?>
                            <div class="bg-white p-4 rounded-lg shadow flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <span class="font-semibold"><?= $user->getName() ?></span>
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                            <span class="font-semibold"><?= $book->getTitle() ?></span>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Due: <?= date('Y-m-d', strtotime($request['due_at'])) ?>
                                    </div>
                                </div>
                                <div class="flex space-x-3 mt-4 pt-3 border-t border-gray-100">
                                    <a href="/admin/borrowings/approve?id=<?= $request['id_borrow_request'] ?>"
                                        class="flex-1 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-center">
                                        Approve
                                    </a>
                                    <a href="/admin/borrowings/reject?id=<?= $request['id_borrow_request'] ?>"
                                        class="flex-1 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-center">
                                        Reject
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Current Borrowings Section -->
            <section>
                <h2 class="text-2xl font-bold mb-4">Current Borrowings</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <?php if (empty($borrowings)): ?>
                        <div class="col-span-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg p-8">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">No active borrowings</h3>
                                <p class="mt-1 text-sm text-gray-500">There are no books currently borrowed.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($borrowings as $borrow):
                            $user->findById($borrow['id_user']);
                            $book->findById($borrow['id_book']);
                        ?>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="font-semibold"><?= $user->getName() ?></span>
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                        <span class="font-semibold"><?= $book->getTitle() ?></span>
                                    </div>
                                    <span class="text-sm text-gray-500">Due: <?= date('Y-m-d', strtotime($borrow['due_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>