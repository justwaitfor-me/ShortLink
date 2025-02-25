<!-- filepath: /assets/php/public.php -->
<?php
include '_cross_validation.php';

$jsonFile = ($remoteAddr === '127.0.0.1' || $remoteAddr === '::1') ? 'shortlink-data.json' : '/home/sites/site100035052/web/justwaitforme.de/content/data/json/shortlink-data.json';
$shortLinks = [];

if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $shortLinks = json_decode($jsonData, true);
}

$publicLinks = array_filter($shortLinks, function ($link) {
    return $link['public'] && $link['active'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Short Links</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Public Short Links</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Short Link</th>
                        <th scope="col">Original URL</th>
                        <th scope="col">Author</th>
                        <th scope="col">Created</th>
                        <th scope="col">Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publicLinks as $key => $link): ?>
                        <tr>
                            <td><a href="https://s.justwaitforme.de/<?php echo htmlspecialchars($key); ?>" target="_blank"><?php echo htmlspecialchars($key); ?></a></td>
                            <td><a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank"><?php echo htmlspecialchars($link['url']); ?></a></td>
                            <td><?php echo htmlspecialchars($link['author']); ?></td>
                            <td><?php echo htmlspecialchars($link['created']); ?></td>
                            <td><?php echo htmlspecialchars($link['clicks']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>