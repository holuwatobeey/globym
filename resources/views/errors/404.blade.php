<html>
	<head>
		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
			}

			.container {
				text-align: center;
				vertical-align: middle;
			}

            .lw-error-template {padding: 40px 15px;text-align: center;}
            .lw-error-actions {margin-top:15px;margin-bottom:15px;}
            .lw-error-actions .btn { margin-right:10px; }

		</style>
	</head>
	<body>
		<div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="lw-error-template">
                        <h1><?= __tr('Ooops...! Not Found') ?></h1>
                        <h2><?= __tr(' 404 Not Found') ?></h2>
                        <div class="lw-error-details">
                            <?= __tr('Sorry, an error has occured, Requested page not found!') ?>
                        </div>
                        <div class="lw-error-actions">
                            <a href="{{ URL::previous() }}" class="btn btn-primary btn-lg"></span><?= __tr('Back') ?> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>



