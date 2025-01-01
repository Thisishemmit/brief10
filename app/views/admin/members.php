<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Roles</title>
    <script src="/JavaScript/tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-[url('/images/BookBg.webp')] bg-cover bg-center min-h-screen flex flex-row">
        <?php require 'app/views/parts/sidebar.php'; ?>
        <div class="min-h-screen flex-grow flex flex-col items-center justify-center p-4">
            <div class="bg-black bg-opacity-10 text-white backdrop-blur-lg shadow-lg rounded-lg p-8 max-w-4xl w-full">
                <h2 class="text-xl font-semibold text-center mb-6">Manage Members' Roles</h2>

                <?php if (empty($allUsers)) :  ?>

                    <div>
                        <p>No users</p>
                    </div>

                <?php else : ?>

                    <table class="w-full text-left border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-800 text-white">
                                <th class="p-4 border border-gray-300">Name</th>
                                <th class="p-4 border border-gray-300">Email</th>
                                <th class="p-4 border border-gray-300">Role</th>
                                <th class="p-4 border border-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white text-black">

                            <?php foreach ($allUsers as $user): ?>
                                <tr class="border-b border-gray-300">
                                    <td class="p-4 border border-gray-300"><?= $user->getName() ?></td>
                                    <td class="p-4 border border-gray-300"><?= $user->getEmail() ?></td>
                                    <td class="p-4 border border-gray-300"><?= $user->getRole() ?></td>
                                    <td class="p-4 border border-gray-300">
                                        <button onclick="displayEditForm('<?= $user->getName() ?>', '<?= $user->getEmail() ?>', '<?= $user->getRole() ?>')" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-600 duration-100">Edit</button>
                                        <button onclick="displayDeleteForm('<?= $user->getName() ?>', '<?= $user->getEmail() ?>')" class="bg-red-500 text-white font-bold py-2 px-4 rounded-md hover:bg-red-600 duration-100 ml-2">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Edit Form -->
            <div id="editForm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white text-black rounded-lg p-6 w-96">
                    <h3 class="text-lg font-semibold mb-4">Edit User Role</h3>
                    <form action="/admin/members/edit" method="GET">
                        <div class="mb-4">
                            <input type="hidden" name="name" type="text" id="editName" class="w-full border border-gray-300 rounded-md p-2">
                        </div>
                        <div class="mb-4">
                            <input type="hidden" name="userEmail" type="text" id="editEmail" class="w-full border border-gray-300 rounded-md p-2">
                        </div>
                        <div class="mb-4">
                            <label for="editRole" class="block font-bold">Role:</label>
                            <select name="role" id="editRole" class="w-full border border-gray-300 rounded-md p-2">
                                <option value="admin">Administrator</option>
                                <option value="member">Member</option>
                            </select>
                        </div>
                        <button type="button" onclick="closeEditForm()" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-md mr-2">Cancel</button>
                        <button type="submit" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded-md">Save</button>
                    </form>
                </div>
            </div>

            <!-- Delete Confirmation -->
            <form method="GET" action="/admin/members/delete" id="deleteForm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white text-black rounded-lg p-6 w-96">
                    <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                    <p id="deleteMessage" class="mb-4"></p>
                    <input type="hidden" name="userEmail" id="userEmail">
                    <button type="button" onclick="closeDeleteForm()" class="bg-gray-500 text-white font-bold py-2 px-4 rounded-md mr-2">Cancel</button>
                    <input type="submit" value="Delete" class="bg-red-500 text-white font-bold py-2 px-4 rounded-md">
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
