<html>
    <head>
        <title>Upload Avatar Result</title>
    </head>
    <body>
        <?php if ($uploaded_file): ?>
        <h1>Upload success</h1>
        <p>
            Here is your uploaded image:
            <img src="<?php echo URL::site("/public/images/upload/$uploaded_file") ?>" alt="Uploaded image" />
        </p>
        <p>
            Here is your uploaded avatar:
            <img src="<?php echo URL::site("/public/images/thumbnails/$uploaded_file") ?>" alt="Uploaded avatar" />
        </p>
        <?php else: ?>
        <h1>Something went wrong with the upload</h1>
        <p><?php echo $error_message ?></p>
        <?php endif ?>
        <?= HTML::anchor('edit', 'назад'); ?>
    </body>
</html>