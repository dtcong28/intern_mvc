<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><?php echo (isset($_GET['id']) ? 'Admin Edit' : 'Admin Create') ?></h2>
        <form method="POST" enctype="multipart/form-data" class="col-6">
            <?php if (isset($oldData->id)) : ?>
                <div class="form-group">
                    <label>ID</label>: <?php echo $oldData->id ?>
                </div>
            <?php endif ?>
            <div class="form-group">
                <label>Avatar *</label><br>
                <input type="file" class="form-control-file" name="avatar" id="upload" onchange="loadFile(event)">

                <img style="width: 50px;" id="output" 
                    <?php if (isset($_SESSION['dataInput']['tmp_avatar'])) : ?> 
                        src="assets/upload/tmp/<?php echo $_SESSION['dataInput']['tmp_avatar']; ?>" 
                    <?php elseif (isset($oldData)) : ?> 
                        src="assets/upload/admin/<?php echo $oldData->id . '/' . $oldData->avatar; ?>" 
                    <?php endif ?>
                />
                <?php if (isset($_SESSION['dataInput']['tmp_avatar'])) : ?>
                    <input type="hidden" name="tmp_avatar" value="<?php echo $_SESSION['dataInput']['tmp_avatar']; ?>">
                <?php endif ?>

                <?php if (isset($_SESSION['errCreate']['image'])) : ?>
                    <?php includeVariables(PATH_TO_BLADE . "error.php", ['err' => $_SESSION['errCreate']['image']], true) ?>
                <?php endif ?>
            </div>
            <div class="form-group">
                <label>Name *</label>
                <input type="text" class="form-control" name="name" value="<?php echo isset($oldData->name) && !isset($_SESSION['dataInput']) ? $oldData->name : (!isset($_SESSION['errCreate']['name']) && isset($_SESSION['dataInput']) ? $_SESSION['dataInput']['name'] : '') ?>">
                <?php if (isset($_SESSION['errCreate']['name'])) : ?>
                    <?php includeVariables(PATH_TO_BLADE . "error.php", ['err' => $_SESSION['errCreate']['name']], true) ?>
                <?php endif ?>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="text" class="form-control" name="email" value="<?php echo isset($oldData->email) ? $oldData->email : (!isset($_SESSION['errCreate']['email']) && isset($_SESSION['dataInput']) ? $_SESSION['dataInput']['email'] : '') ?>">
                <?php if (isset($_SESSION['errCreate']['email'])) : ?>
                    <?php includeVariables(PATH_TO_BLADE . "error.php", ['err' => $_SESSION['errCreate']['email']], true) ?>
                <?php endif ?>
            </div>
            <div class="form-group ">
                <label>Password *</label>
                <input type="password" class="form-control" name="password" value="<?php echo (!isset($_SESSION['errCreate']['password']) && isset($_SESSION['dataInput'])) ? $_SESSION['dataInput']['password'] : '' ?>">
                <?php if (isset($_SESSION['errCreate']['password'])) : ?>
                    <?php includeVariables(PATH_TO_BLADE . "error.php", ['err' => $_SESSION['errCreate']['password']], true) ?>
                <?php endif ?>
            </div>
            <div class=" form-group">
                <label>Password Verify *</label>
                <input type="password" class="form-control" name="password_confirm" value="<?php echo (!isset($_SESSION['errCreate']['confirmation_pwd']) && isset($_SESSION['dataInput'])) ? $_SESSION['dataInput']['password_confirm'] : '' ?>">
                <?php if (isset($_SESSION['errCreate']['confirmation_pwd'])) : ?>
                    <?php includeVariables(PATH_TO_BLADE . "error.php", ['err' => $_SESSION['errCreate']['confirmation_pwd']], true) ?>
                <?php endif ?>

            </div>
            <div class=" form-group">
                <label>Role *</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role_type" value=<?php echo SUPER_ADMIN ?> <?php echo isset($oldData->role_type) && $oldData->role_type == SUPER_ADMIN ? 'checked' : (!isset($_SESSION['errCreate']['role_type']) && isset($_SESSION['dataInput']) && $_SESSION['dataInput']['role_type'] == SUPER_ADMIN ? 'checked' : '') ?>>
                    <label class="form-check-label">Super Admin</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role_type" value=<?php echo ADMIN ?> <?php echo isset($oldData->role_type) && $oldData->role_type == ADMIN ? 'checked' : (!isset($_SESSION['errCreate']['role_type']) && isset($_SESSION['dataInput']) && $_SESSION['dataInput']['role_type'] == ADMIN ? 'checked' : '') ?>>
                    <label class="form-check-label">Admin</label>
                </div>
                <?php if (isset($_SESSION['errCreate']['role_type'])) : ?>
                    <?php includeVariables(PATH_TO_BLADE . "error.php", ['err' => $_SESSION['errCreate']['role_type']], true) ?>
                <?php endif ?>
                <?php
                unset($_SESSION['errCreate']);
                unset($_SESSION['dataInput']);
                ?>

            </div>
            <div class="row">
                <button type="reset" name="reset" class="btn btn-secondary col-2">Reset</button>
                <div class="col-8"></div>
                <button type="submit" name="submit" class="btn btn-primary col-2">Save</button>
            </div>
        </form>
    </div>
</div>