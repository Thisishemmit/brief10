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

            <h1 class="text-2xl font-bold mb-6">Return Requests</h1>

            <?php if (empty($returnings)): ?>
                <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Return Requests</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no pending return requests at this time.</p>
                </div>
            <?php else: ?>
                <div class="grid gap-4">
                    <?php foreach ($returnings as $return): 
                        $book = $return['book'];
                        $user = $return['user'];
                        $requests = $return['returnRequests'];
                        // Get the pending request
                        $pendingRequest = current(array_filter($requests, function($req) {
                            return $req['request']['status'] === 'pending';
                        }));
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
                                    <p class="text-gray-600">Request Status:</p>
                                    <?php foreach ($requests as $req): ?>
                                        <p class="mt-1">
                                            <span class="px-2 py-1 text-xs rounded <?= $req['request']['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($req['request']['status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst($req['request']['status']) ?>
                                            </span>
                                            <span class="text-gray-500 text-xs ml-2">
                                                <?= date('M d, Y', strtotime($req['request']['requested_at'])) ?>
                                            </span>
                                        </p>
                                    <?php endforeach; ?>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <?php if ($pendingRequest): ?>
                                        <a href="/admin/returnings/approve?id=<?= $pendingRequest['request']['id_return_request'] ?>" 
                                           class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                                           onclick="return confirm('Are you sure you want to approve this return request?')">
                                            Approve
                                        </a>
                                        <a href="/admin/returnings/reject?id=<?= $pendingRequest['request']['id_return_request'] ?>" 
                                           class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                           onclick="return confirm('Are you sure you want to reject this return request?')">
                                            Reject
                                        </a>
                                    <?php endif; ?>
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
