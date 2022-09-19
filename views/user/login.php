<div class="container-lg">
    <h3>Login User</h3>
    <?php if (isset($_SESSION['errLogin']['err'])) : ?>
        <p style="color: #ff0000;">
            <?php echo $_SESSION['errLogin']['err']; ?>
        </p>
    <?php endif ?>
    <?php unset($_SESSION['errLogin']) ?>
    <form method="post" class="mx-5">
        <div class="form-group ">
            <label>Email </label>
            <input type="email" name="email" class="form-control col-4" placeholder="Enter email">

        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control col-4" placeholder="Password">
        </div>
        <a href="<?php echo isset($loginUrl) ? $loginUrl : '#' ?>">login with facebook</a><br>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>
</div>

