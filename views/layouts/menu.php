<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <?php if (!empty($_SESSION['admin']) && $_SESSION['admin']['role_type'] == SUPER_ADMIN) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Admin management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/?controller=admin&action=search">Search</a>
                        <a class="dropdown-item" href="/?controller=admin&action=create">Create</a>
                    </div>
                </li>
            <?php endif; ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    User management
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/?controller=user&action=search">Search</a>
                </div>
            </li>
            <li>
                <a class="nav-link" href="/?controller=authBE&action=logout" aria-haspopup="true" aria-expanded="false">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>