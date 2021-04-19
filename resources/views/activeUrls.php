<!doctype html>
<html lang="en">

<?php require_once './resources/views/partials/head.php'; ?>

<body>

    <main class="container mt-5">
        <a href="/URL-shortening-service/">< Home</a>
        <h2 class="my-3">Available active URLs</h2>
        <table class="table table-striped table-dark d-none" id="links_table">
            <thead>
                <tr>
                    <th scope="col">Short URL</th>
                    <th scope="col">Long URL</th>
                    <th scope="col">Expiration</th>
                </tr>
            </thead>
            <tbody id="table_body"></tbody>
        </table>

        <div class="alert alert-info d-none" id="info" role="alert"></div>

        <?php require_once './resources/views/partials/toast.php'; ?>
    </main>

    <?php require_once './resources/views/partials/footer.php'; ?>
    <script src="/URL-shortening-service/public/js/activeUrls.js"></script>
</body>
</html>