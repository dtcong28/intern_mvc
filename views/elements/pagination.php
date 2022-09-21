<?php
$page = isset($_GET['page']) ? $_GET['page'] : '';
$uri = explode('&page', $_SERVER['REQUEST_URI']);
$pagLink = '';
?>
<?php if (isset($_GET['page']) && isset($results)): ?>
    <nav aria-label="..." class="pt-3">
        <ul class="pagination">
            <?php if ($page >= 2): ?>
                <li class="page-item">
                    <a class="page-link"
                       href='<?php echo $uri[0] ?>&page=<?php echo $page - 1 ?>&column=<?php echo isset($_GET["column"]) ? $_GET["column"] : '' ?>&order=<?php echo isset($_GET["order"]) ? $_GET["order"] : '' ?>'>
                        Prev </a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $results['totalPages']; $i++): ?>
                <?php if ($i == $page) {
                    $pagLink .= "<li class='page-item active'><a class = 'page-link' href='$uri[0]&page=" . $i . "&column=" . $_GET["column"] . "&order=" . $_GET["order"] . "'>" . $i . "</a></li>";
                } else {
                    $pagLink .= "<li class='page-item '><a class = 'page-link' href='$uri[0]&page=" . $i . "&column=" . $_GET["column"] . "&order=" . $_GET["order"] . "'>" . $i . "</a></li>";
                }
                ?>
            <?php endfor; ?>
            <?php echo $pagLink; ?>
            <?php if ($page < $results['totalPages']): ?>
                <li class="page-item">
                    <a class="page-link"
                       href='<?php echo $uri[0] ?>&page=<?php echo $page + 1 ?>&column=<?php echo $_GET["column"] ?>&order=<?php echo $_GET["order"] ?>'>
                        Next </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>