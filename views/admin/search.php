<div class="py-4">
    <div class="border border-primary">
        <form method="GET" class="p-4">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="search">
            <div class="form-group pb-3 col-6">
                <label>Email *</label>
                <input type="text" class="form-control " name="searchEmail" value="<?php echo !empty($_GET['searchEmail']) ? $_GET['searchEmail'] : '' ?>">
            </div>
            <div class="form-group pb-3 col-6">
                <label>Name *</label>
                <input type="text" class="form-control" name="searchName" value="<?php echo !empty($_GET['searchName']) ? $_GET['searchName'] : '' ?>">
                <input type="hidden" value="1" name="page">
                <input type="hidden" name="column" value="id">
                <input type="hidden" name="order" value="asc">
            </div>
            <div class="row">
                <a class="btn btn-secondary" href="<?php echo DOMAIN ?>/?controller=admin&action=search">Reset</a>
                <div class="col-10"></div>
                <button type="search" class="btn btn-primary col-1">Search</button>
            </div>
        </form>
    </div>
    <table class="table mt-5">
        <thead class="thead-dark">
            <tr>
                <th scope="col">
                    <a style="color: white;" 
                    <?php if(isset($_GET['controller']) && isset($_GET['action']) && isset($_GET['searchEmail']) && isset($_GET['searchName'])  && isset($_GET['page'])):?>
                    href="<?php echo DOMAIN ?>/?controller=<?php echo $_GET['controller'] ?>&action=<?php echo $_GET['action'] ?>&searchEmail=<?php echo $_GET['searchEmail'] ?>&searchName=<?php echo $_GET['searchName'] ?>&page=<?php echo $_GET['page'] ?>&column=id&order=<?php echo isset($results['ascOrDesc']) ? $results['ascOrDesc'] : '' ?>"
                    <?php endif?>>
                        ID
                        <?php if (!empty($results['data'])) : ?>
                            <i class="fa fa-sort<?php echo $results['column'] == 'id' ? '-' . $results['sortOrder'] : ''; ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </a>
                </th>

                <th scope="col">Avatar</th>

                <th scope="col">
                    <a style="color: white;" 
                    <?php if(isset($_GET['controller']) && isset($_GET['action']) && isset($_GET['searchEmail']) && isset($_GET['searchName'])  && isset($_GET['page'])):?>
                    href="<?php echo DOMAIN ?>/?controller=<?php echo $_GET['controller'] ?>&action=<?php echo $_GET['action'] ?>&searchEmail=<?php echo $_GET['searchEmail'] ?>&searchName=<?php echo $_GET['searchName'] ?>&page=<?php echo $_GET['page'] ?>&column=name&order=<?php echo isset($results['ascOrDesc']) ? $results['ascOrDesc'] : '' ?>"
                    <?php endif?>>  
                        Name
                        <?php if (!empty($results['data'])) : ?>
                            <i class="fa fa-sort<?php echo $results['column'] == 'name' ? '-' . $results['sortOrder'] : ''; ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </a>
                </th>

                <th scope="col">
                    <a style="color: white;" 
                    <?php if(isset($_GET['controller']) && isset($_GET['action']) && isset($_GET['searchEmail']) && isset($_GET['searchName'])  && isset($_GET['page'])):?>
                    href="<?php echo DOMAIN ?>/?controller=<?php echo $_GET['controller'] ?>&action=<?php echo $_GET['action'] ?>&searchEmail=<?php echo $_GET['searchEmail'] ?>&searchName=<?php echo $_GET['searchName'] ?>&page=<?php echo $_GET['page'] ?>&column=email&order=<?php echo isset($results['ascOrDesc']) ? $results['ascOrDesc'] : '' ?>"
                    <?php endif?>> 
                    Email
                        <?php if (!empty($results['data'])) : ?>
                            <i class="fa fa-sort<?php echo $results['column'] == 'email' ? '-' . $results['sortOrder'] : ''; ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </a>
                </th>

                <th scope="col">
                    <a style="color: white;" 
                    <?php if(isset($_GET['controller']) && isset($_GET['action']) && isset($_GET['searchEmail']) && isset($_GET['searchName'])  && isset($_GET['page'])):?>
                    href="<?php echo DOMAIN ?>/?controller=<?php echo $_GET['controller'] ?>&action=<?php echo $_GET['action'] ?>&searchEmail=<?php echo $_GET['searchEmail'] ?>&searchName=<?php echo $_GET['searchName'] ?>&page=<?php echo $_GET['page'] ?>&column=role_type&order=<?php echo isset($results['ascOrDesc']) ? $results['ascOrDesc'] : '' ?>"
                    <?php endif?>>    
                    Role
                        <?php if (!empty($results['data'])) : ?>
                            <i class="fa fa-sort<?php echo $results['column'] == 'role_type' ? '-' . $results['sortOrder'] : ''; ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </a>
                </th>
                
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($results['data'])) : ?>
                <?php require_once('views/elements/pagination.php'); ?>
                <?php foreach ($results['data'] as $value) : ?>
                    <tr>
                        <th scope="row"><?= $value->id ?></th>
                        <td>
                            <img style="width: 50px;" src="assets/upload/admin/<?php echo $value->id . '/' . $value->avatar; ?>">
                        </td>
                        <td><?php echo $value->name ?></td>
                        <td><?php echo $value->email ?></td>
                        <td><?php echo $value->role_type == SUPER_ADMIN ? 'Super Admin' : 'Admin' ?></td>
                        <td>
                            <a href="<?php echo DOMAIN ?>/?controller=admin&action=edit&id=<?php echo $value->id; ?>">Edit</a><br>
                            <a onclick="return Del('<?php echo $value->name; ?>')" href="<?php echo DOMAIN ?>/?controller=admin&action=delete&id=<?= $value->id; ?>">Delete</a>
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