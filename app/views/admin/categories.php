<?php
require_once 'app/helpers/errors.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <script src="/JavaScript/tailwind.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Categories</h1>
                    <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Add Category
                    </button>
                </div>

                <?php if (has_error('add_category') || has_error('edit_category') || has_error('delete_category')) : ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                        <p class="text-red-700">
                            <?= get_error('add_category') ?? get_error('edit_category') ?? get_error('delete_category') ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($cat->getName()) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button onclick="editCategory(<?= $cat->getId() ?>, '<?= htmlspecialchars($cat->getName()) ?>')"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <button onclick="deleteCategory(<?= $cat->getId() ?>)"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-xl font-bold mb-4">Add Category</h2>
            <form action="/admin/categories/add" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" name="add_category"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-xl font-bold mb-4">Edit Category</h2>
            <form action="/admin/categories/edit" method="POST">
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-4">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" name="edit_category"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div id="deleteCategoryModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-xl font-bold mb-4">Delete Category</h2>
            <p class="mb-4 text-gray-600">Are you sure you want to delete this category? This action cannot be undone.</p>
            <form action="/admin/categories/delete" method="POST">
                <input type="hidden" id="delete_id" name="id">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('deleteCategoryModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" name="delete_category"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editCategory(id, name) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }

        function deleteCategory(id) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteCategoryModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
