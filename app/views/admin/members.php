<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Roles</title>
    <script src="/JavaScript/tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-row bg-gray-100">
        <?php require 'app/views/parts/sidebar.php'; ?>
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Manage Members' Roles</h1>
                </div>

                <?php if (empty($allUsers)) : ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                        <p class="text-red-700">No users</p>
                    </div>
                <?php else : ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($allUsers as $user): ?>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= $user->getName() ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= $user->getEmail() ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= $user->getRole() ?></td>
                                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                            <button onclick="displayEditForm('<?= $user->getName() ?>', '<?= $user->getEmail() ?>', '<?= $user->getRole() ?>')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <button onclick="displayDeleteForm('<?= $user->getName() ?>', '<?= $user->getEmail() ?>')" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div id="editForm" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-xl font-bold mb-4">Edit User Role</h2>
            <form action="/admin/members/edit" method="GET">
                <input type="hidden" name="name" id="editName" class="w-full px-4 py-2 rounded-md border-gray-300 shadow-sm">
                <input type="hidden" name="userEmail" id="editEmail" class="w-full px-4 py-2 rounded-md border-gray-300 shadow-sm">
                <div class="mb-4">
                    <label for="editRole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="editRole" class="w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="admin">Administrator</option>
                        <option value="member">Member</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditForm()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteForm" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
            <p id="deleteMessage" class="mb-4 text-gray-600"></p>
            <form action="/admin/members/delete" method="GET">
                <input type="hidden" name="userEmail" id="userEmail">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteForm()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function displayEditForm(name, email, role) {
            document.getElementById('editForm').classList.remove('hidden');
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
        }

        function closeEditForm() {
            document.getElementById('editForm').classList.add('hidden');
        }

        function displayDeleteForm(name, email) {
            document.getElementById('deleteForm').classList.remove('hidden');
            document.getElementById('deleteMessage').innerText = `Are you sure you want to delete ${name} (${email})?`;
            document.getElementById('userEmail').value = email;
        }

        function closeDeleteForm() {
            document.getElementById('deleteForm').classList.add('hidden');
        }
    </script>
</body>

</html>
