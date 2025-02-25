<?php

$ipAddress = $_SERVER['REMOTE_ADDR'];
// handleRateLimiting($ipAddress, 60, 5);

if (isset($_SESSION['key'])) {
    $key64 = $_SESSION['key'];
    $key = base64_decode($key64);
    $url = "s.justwaitforme.de/$key";
    $perm_url = "s.justwaitforme.de/?uid=$key64";

    if (
        $remoteAddr === '127.0.0.1' || $remoteAddr === '::1'
    ) {
        $jsonFile = 'shortlink-data.json';
    } else {
        $jsonFile = '/home/sites/site100035052/web/justwaitforme.de/content/data/json/shortlink-data.json';
    }

    $shortLinks = loadJsonData($jsonFile);
    if ($shortLinks) {

        // Check if the JSON data is valid
        if (json_last_error() === JSON_ERROR_NONE) {
            $shortLink = $shortLinks[$key];
        } else {
            $errorCode = 501;
            $errorMessage = "Internal Server Error";
            $errorDescription = "An error occurred while processing the request.";
            header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
            exit;
        }
    } else {
        $errorCode = 501;
        $errorMessage = "Internal Server Error";
        $errorDescription = "An error occurred while processing the request.";
        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
        exit;
    }
} else {
    $errorCode = 500;
    $errorMessage = "Internal Server Error";
    $errorDescription = "An error occurred while saving the data.";
    header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
    exit;
}

include 'assets/php/handleDashboardPost.php';
?>

<!-- filepath: /assets/php/dashboard.php -->
<main class="container mt-5">
    <?php
    $jsonFile = ($remoteAddr === '127.0.0.1' || $remoteAddr === '::1') ? 'shortlink-data.json' : '/home/sites/site100035052/web/justwaitforme.de/content/data/json/shortlink-data.json';
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $shortLinks = json_decode($jsonData, true);

        if (isset($shortLinks[$key])) {
            $shortLink = $shortLinks[$key];
        } else {
            $errorCode = 404;
            $errorMessage = "Not Found";
            $errorDescription = "The short link does not exist.";
            header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
            exit;
        }
    } else {
        $errorCode = 501;
        $errorMessage = "Internal Server Error";
        $errorDescription = "An error occurred while processing the request.";
        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
        exit;
    }
    ?>

    <h1 class="mb-4 position-relative">
        Dashboard
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?php echo $shortLink['clicks']; ?>
            <span class="visually-hidden">Clicks</span>
        </span>
    </h1>
    <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
        <div>
            <strong>Success!</strong> Short link created successfully!
        </div>
        <span>
            <span class="badge text-bg-dark" style="margin-right: 24px"> <a class="link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="https://<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></span>
            <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('<?php echo $url; ?>')">
                <i class="bi bi-copy"></i>
            </button>
        </span>
    </div>
    <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
        <div>
            <strong>Info:</strong> Permanent Dashboard Link:
        </div>
        <span>
            <span class="badge text-bg-light" style="margin-right: 24px"><a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="https://<?php echo $perm_url; ?>" target="_blank"><?php echo $perm_url; ?></a></span>
            <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('<?php echo $perm_url; ?>')">
                <i class="bi bi-copy"></i>
            </button>
        </span>
    </div>

    <div class="card">
        <div class="card-header">
            Manage Short Link
        </div>
        <div class="card-body">
            <form id="manage-shortlink-form" method="POST">

                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($shortLink['url']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($shortLink['expires']); ?>">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="never_expire" name="never_expire" <?php echo ($shortLink['expires'] === '9999-12-31') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="never_expire">Never Expire</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="active" name="active" <?php echo ($shortLink['active']) ? 'checked' : ''; ?> data-toggle="modal">
                    <label class="form-check-label" for="active">Active</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="protected" name="protected" <?php echo ($shortLink['protected']['active']) ? 'checked' : ''; ?> data-toggle="modal" data-target="#passwordModal">
                    <label class="form-check-label" for="protected" id="protected-label">Password Protected</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="public" name="public" <?php echo ($shortLink['public']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="public">Public</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="monitoring" name="monitoring" <?php echo ($shortLink['monitoring']['active']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="monitoring" id="monitoring-label">Enable Monitoring <span class="badge rounded-pill text-bg-info" style="margin-left:7px;">New</span></label>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
    <br>
    <?php if ($shortLink['monitoring']['active']): ?>
        <div class="card">
            <div class="card-header">
                <form id="clear-log-form" method="POST">
                    Monitoring Data
                    <input type="hidden" name="clearlog" value="ok">
                    <button type="submit" class="btn btn-primary"
                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem; margin-left: 10px;">
                        Clear Log
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Ip</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Date</th>
                                <th scope="col">Unique Id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shortLink['monitoring']['data'] as $event): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($event['ip']); ?></td>
                                    <td><?php echo htmlspecialchars($event['user_agent']); ?></td>
                                    <td><?php echo htmlspecialchars($event['time']); ?></td>
                                    <td><?php echo htmlspecialchars($event['unique_id']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>
<script src="assets/js/dashboard.js"></script>