<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: *');
require_once __DIR__ . '/../../library/vendor/autoload.php';
require_once __DIR__ . '/../../config/config_counter.php';
require_once 'db.php';

Flight::route('/', function() {
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
		
        // Validate page domain
        $allowed = COUNTER_ALLOWED_DOMAINS;

		$valid = false;

		foreach ($allowed as $domain) {
			if (str_starts_with($page, $domain)) {
				$valid = true;
				break;
			}
		}

		if (!$valid) {
			echo 'This is a private server. If you come here by mistake, go away.';
			return;
		}

        // Generate visitor hash
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $visitor_hash = hash('sha256', $ip . $ua, true);

        global $pdo;

        // Check if visitor already logged for this page recently (within 1 day)
        $stmt = $pdo->prepare("SELECT id FROM visitor_logs WHERE page = ? AND visitor_hash = ? AND visited_at > datetime('now', '-1 day')");
        $stmt->execute([$page, $visitor_hash]);
        $exists = $stmt->fetch();

        if ($exists) {
            // Not unique, return current count
            $stmt = $pdo->prepare("SELECT unique_count FROM page_views WHERE page = ?");
            $stmt->execute([$page]);
            $row = $stmt->fetch();
            echo json_encode(['value' => $row['unique_count'] ?? 0]);
        } else {
            // Unique, insert/update log
            $pdo->prepare("INSERT INTO visitor_logs (page, visitor_hash, visited_at) VALUES (?, ?, datetime('now')) ON CONFLICT(page, visitor_hash) DO UPDATE SET visited_at = datetime('now')")->execute([$page, $visitor_hash]);

            // Check if page exists in page_views
            $stmt = $pdo->prepare("SELECT unique_count FROM page_views WHERE page = ?");
            $stmt->execute([$page]);
            $row = $stmt->fetch();

            if ($row) {
                // Existing page, increment count
                $new_count = $row['unique_count'] + 1;
                $pdo->prepare("UPDATE page_views SET unique_count = ? WHERE page = ?")->execute([$new_count, $page]);
                echo json_encode(['value' => $new_count]);
            } else {
                // New page, create with count 1
				$number = 1;
                // $number = random_int(1, 100);
                $pdo->prepare("INSERT INTO page_views (page, unique_count, created_at) VALUES (?, ?, time('now'))")->execute([$page, $number]);
                echo json_encode(['value' => $number]);
            }
        }
    } else {
        // echo 'Page View Counter API is running. Use /counter?page=page-url to get view count.';
		echo 'This is a private server. If you come here by mistake, go away.';
    }
});

Flight::start();
?>
