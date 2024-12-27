<?php
require_once 'app/helpers/errors.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <script src="/JavaScript/tailwind.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold mb-6">Edit Book</h1>

                <?php if (has_error('edit_book')) : ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                        <p class="text-red-700"><?= get_error('edit_book') ?></p>
                    </div>
                <?php endif; ?>

                <form action="/admin/books/edit?id=<?= $bookData['id'] ?>" method="POST" class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="title" name="title" required value="<?= htmlspecialchars($bookData['title']) ?>"
                            class="mt-1 block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                        <input type="text" id="author" name="author" required value="<?= htmlspecialchars($bookData['author']) ?>"
                            class="mt-1 block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category_id" name="category_id" required
                            class="mt-1 block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category['id_category'] ?>" <?= $category['id_category'] == $bookData['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Cover Image Name</label>
                        <input type="text" id="cover_image" name="cover_image" required value="<?= htmlspecialchars($bookData['cover_image']) ?>"
                            placeholder="e.g., book-cover.jpg"
                            class="mt-1 block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Enter the image filename (must exist in images folder)</p>
                    </div>

                    <div>
                        <label for="summary" class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                        <textarea id="summary" name="summary" rows="4" required
                            class="mt-1 block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?= htmlspecialchars($bookData['summary']) ?></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-2">
                        <a href="/admin/books" 
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" name="edit_book"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Update Book
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
