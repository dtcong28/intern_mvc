<button type="button" class="btn btn-dark"><a href="<?php echo DOMAIN?>/?controller=authFE&action=logout" style="text-decoration: none; color:white">Logout</a></button>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2>My profile</h2>
        <form method="POST" enctype="multipart/form-data" class="col-6">

            <div class="form-group">
                <label>ID</label>: <?php echo isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '' ?>
            </div>

            <div class="form-group">
                <label>Avatar *</label><br>
                <?php if (isset($_SESSION['user']['avatar'])): ?>
                    <?php if (str_contains($_SESSION['user']['avatar'], 'https://platform-lookaside.fbsbx.com/platform/profilepic')): ?>
                        <img style="width: 100px;"
                             src="<?php echo $_SESSION['user']['avatar'] ?>"><br>
                    <?php else: ?>
                        <img style="width: 100px;"
                             src="assets/upload/user/<?php echo $_SESSION['user']['id'] . '/' . $_SESSION['user']['avatar']; ?>"><br>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Name *</label>
                <input type="text" class="form-control" name="name"
                       value="<?php echo isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '' ?>" readonly>

            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" class="form-control" name="email"
                       value="<?php echo isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '' ?>"
                       readonly>
            </div>
        </form>
    </div>
</div>