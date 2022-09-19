<?php if (isset($err)) : ?>
    <?php foreach ($err as $key => $value) : ?>
        <p style="color: #ff0000;">
            <?php echo $value; ?>
        </p>
    <?php endforeach ?>
<?php endif ?>