<aside class="fixed inset-y-0 left-0 bg-gray-800 w-64 space-y-6 px-2 py-4 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
    <nav>
        <?php
        $current_path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $routes = require 'app/config/sidebar.php';
        $sidebar_items = $routes[$_SESSION['user']['role']] ?? [];
        ?>

        <div class="px-4 py-2">
            <h2 class="text-xl font-bold text-white mb-4">Library System</h2>
        </div>

        <div class="space-y-1">
            <?php foreach ($sidebar_items as $path => $item): ?>
                <a href="<?= $path ?>"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg
                          <?= $current_path === $path
                              ? 'bg-gray-900 text-white'
                              : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?>">
                    <i class="<?= $item['icon'] ?> w-6"></i>
                    <span class="ml-3"><?= $item['title'] ?></span>
                </a>
            <?php endforeach; ?>

            <div class="border-t border-gray-700 my-4"></div>

            <a href="/logout"
               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white">
                <i class="fa fa-sign-out w-6"></i>
                <span class="ml-3">Logout</span>
            </a>
        </div>
    </nav>
</aside>
