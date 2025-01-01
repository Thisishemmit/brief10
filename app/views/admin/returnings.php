<?php require_once 'app/helpers/errors.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Requests</title>
    <script src="/JavaScript/tailwind.js"></script>
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        <main class="container mx-auto px-4 py-8">
            <?php if (has_error('return_request')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-red-700"><?= get_error('return_request') ?></p>
                </div>
            <?php endif; ?>

            <section class="mb-8">
                <h2 class="text-2xl font-bold mb-6">Pending Return Requests</h2>
                <?php if (empty($returnings)): ?>
                    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending Requests</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no pending return requests at this time.</p>
                    </div>
                <?php else: ?>
                    <div class="grid gap-4">
                        <?php foreach ($returnings as $return):
                            $book = $return['book'];
                            $user = $return['user'];
                            $requests = $return['returnRequests'];
                        ?>
                            <div class="bg-white rounded-lg shadow-sm p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                    <div class="flex items-center space-x-4">
                                        <img src="/images/<?= htmlspecialchars($book->getCoverImage()) ?>"
                                             alt="Book cover"
                                             class="w-16 h-24 object-cover rounded">
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($book->getTitle()) ?></h3>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                        </div>
                                    </div>

                                    <div class="text-sm">
                                        <p class="text-gray-600">Borrower:</p>
                                        <p class="font-medium"><?= htmlspecialchars($user->getName()) ?></p>
                                        <p class="text-gray-500"><?= htmlspecialchars($user->getEmail()) ?></p>
                                    </div>

                                    <div class="text-sm">
                                        <p class="text-gray-600">Request Date:</p>
                                        <?php foreach ($requests as $req): ?>
                                            <p class="text-gray-500">
                                                <?= date('M d, Y', strtotime($req['request']['requested_at'])) ?>
                                            </p>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="flex justify-end space-x-3">
                                        <?php foreach ($requests as $req): ?>
                                            <a href="/admin/returnings/approve?id=<?= $req['request']['id_return_request'] ?>"
                                               class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                                               onclick="return confirm('Are you sure you want to approve this return request?')">
                                                Approve
                                            </a>
                                            <a href="/admin/returnings/reject?id=<?= $req['request']['id_return_request'] ?>"
                                               class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                               onclick="return confirm('Are you sure you want to reject this return request?')">
                                                Reject
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <section>
                <h2 class="text-2xl font-bold mb-6">Processed Return Requests</h2>
                <?php
                $processedRequests = [];
                foreach ($allProccessedReturnings as $return) {
                    foreach ($return['returnRequests'] as $request) {
                        $processedRequests[] = [
                            'book' => $return['book'],
                            'user' => $return['user'],
                            'request' => $request
                        ];
                    }
                }
                ?>

                <?php if (empty($processedRequests)): ?>
                    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Processed Requests</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no processed return requests to show.</p>
                    </div>
                <?php else: ?>
                    <div class="grid gap-4">
                        <?php foreach ($processedRequests as $processed):
                            $book = $processed['book'];
                            $user = $processed['user'];
                            $request = $processed['request'];
                        ?>
                            <div class="bg-white rounded-lg shadow-sm p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                    <div class="flex items-center space-x-4">
                                        <img src="/images/<?= htmlspecialchars($book->getCoverImage()) ?>"
                                             alt="Book cover"
                                             class="w-16 h-24 object-cover rounded">
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($book->getTitle()) ?></h3>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($book->getAuthor()) ?></p>
                                        </div>
                                    </div>

                                    <div class="text-sm">
                                        <p class="text-gray-600">Borrower:</p>
                                        <p class="font-medium"><?= htmlspecialchars($user->getName()) ?></p>
                                        <p class="text-gray-500"><?= htmlspecialchars($user->getEmail()) ?></p>
                                    </div>

                                    <div class="text-sm">
                                        <p class="text-gray-600">Request Date:</p>
                                        <p class="text-gray-500">
                                            <?= date('M d, Y', strtotime($request['request']['requested_at'])) ?>
                                        </p>
                                    </div>

                                    <div class="flex justify-end">
                                        <span class="px-4 py-2 text-sm rounded <?= $request['request']['status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst($request['request']['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
