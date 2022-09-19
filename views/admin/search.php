<div class="py-4">
    <div class="border border-primary">
        <form method="GET" class="p-4">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="search">
            <div class="form-group pb-3 col-6">
                <label>Email *</label>
                <input type="email" class="form-control " name="searchEmail">
            </div>
            <div class="form-group pb-3 col-6">
                <label>Name *</label>
                <input type="text" class="form-control" name="searchName">
                <input type="hidden" value="1" name="page">
            </div>
            <div class="row">
                <button type="reset" class="btn btn-secondary col-1">Reset</button>
                <div class="col-10"></div>
                <button type="search" class="btn btn-primary col-1">Search</button>
            </div>
        </form>
    </div>
    <table class="table mt-5">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Avatar</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($results)) : ?>
            <?php require_once ('views/elements/pagination.php');?>
            <?php foreach ($results['data'] as $value) : ?>
                <tr>
                    <th scope="row"><?= $value->id ?></th>
                    <td>
                        <img style="width: 50px;"
                             src="assets/upload/admin/<?php echo $value->id . '/' . $value->avatar; ?>">
                    </td>
                    <td><?php echo $value->name ?></td>
                    <td><?php echo $value->email ?></td>
                    <td><?php echo $value->role_type == SUPER_ADMIN ? 'Super Admin' : 'Admin' ?></td>
                    <td>
                        <a href="/?controller=admin&action=edit&id=<?php echo $value->id; ?>">Edit</a><br>
                        <a onclick="return Del('<?php echo $value->name; ?>')"
                           href="/?controller=admin&action=delete&id=<?= $value->id; ?>">Delete</a>
                    </td>
                </tr>

            <?php endforeach ?>
        <?php else : ?>
            <tr>
                <td><?php echo NO_RESULTS ?></td>
            </tr>
        <?php endif ?>

        </tbody>
    </table>
</div>