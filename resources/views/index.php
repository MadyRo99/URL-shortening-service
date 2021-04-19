<!doctype html>
<html lang="en">

<?php require_once './resources/views/partials/head.php'; ?>

<body>

    <main class="container mt-5">
        <h2>URL shortening service</h2>
        <a href="/URL-shortening-service/activeUrls" class="mt-4">Check available active URLs</a>

        <div class="form-group mt-2">
            <div id="long_url_container">
                <label for="long_url"> Enter a long URL to shorten it:</label>
                <div class="d-flex">
                    <input type="text"  class="form-control mr-1" id="long_url" required="required">
                    <button class="btn btn-primary" onclick="shortenUrl()">Go</button>
                </div>
            </div>
            <div id="short_url_container">
                <label for="short_url" class="pt-2"> Your shortened URL:</label>
                <input type="text" class="form-control" id="short_url" readonly>
            </div>
        </div>

        <?php require_once './resources/views/partials/toast.php'; ?>
    </main>

    <?php require_once './resources/views/partials/footer.php'; ?>
    <script src="/URL-shortening-service/public/js/shortenUrl.js"></script>
</body>
</html>