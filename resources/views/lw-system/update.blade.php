<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= config('lwSystem.app_name') ?> - Installation Updater</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= config('lwSystem.app_update_url') ?>/static-assets/client/styles.min.css?ver-1.0.0" >
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
</head>
<body>
<div class="lw-container">
<h2 class="lw-header"><?= config('lwSystem.name') ?> - Installation Updater</h2>
<div class="lw-container-box"></div>
</div>
 <script>
             // Get third party Url from config and customer uid from store setting table
    var appUrl = "<?= config('lwSystem.app_update_url') ?>/api/app-update",
        registrationId = "<?= config('lwSystem.registration_id') ?>",
        version = "<?= config('lwSystem.version') ?>",
        productUid = "<?= config('lwSystem.product_uid') ?>",
        csrfToken = "<?= csrf_token() ?>",
        localRegistrationRoute = "<?= route('installation.version.create.registration') ?>",
        localDownloadRoute = "<?= route('installation.version.update.download') ?>",
        localPerformUpdateRoute = "<?= route('installation.version.update.perform') ?>";
        console.log(appUrl);
 </script>
 <script src="<?= config('lwSystem.app_update_url') ?>/static-assets/client/update-client.min.js?ver-1.0.0" ></script>
</body>
</html>