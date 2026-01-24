<?php
/** @var \App\Model\Category[] $categories */
/** @var \App\Service\Router $router */

$title = 'PLUSFLIX Admin Panel';

ob_start();
?>
<section class="cards-grid">
    <form id="catForm" class="card" method="post" action="<?= $router->generatePath('category-add') ?>">
        <div class="card-icon card-icon-primary">
            <span class="material-symbols-outlined">category</span>
        </div>
        <div class="card-body">
            <h3 class="card-title">Manage Categories</h3>
            <p class="card-text">Add category</p>
        </div>
        <div class="card-footer card-footer-inline">
            <input
                type="text"
                name="category_name"
                class="input"
                placeholder="Category name..."/>
            <?php if (!empty($add_category_result)): ?>
                <div class="csv-status <?= $add_category_result['status'] === 'success' ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($add_category_result['message']) ?>
                </div>
            <?php endif; ?>
            <button id="catFormBtn" class="btn btn-primary" onclick="document.getElementById('catForm').submit();">ADD</button>
        </div>
    </form>
</section>


<section class="tabs-row">
    <div class="tabs">
        <a href="#" class="tab tab-active">All Categories (<?= count($categories ?? []) ?>)</a>
    </div>
</section>

<section class="table-card">
    <div class="table-wrapper">
        <table class="media-table">
            <thead>
            <tr>
                <th>Name</th>
                <th class="th-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td>
                        No categories found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td>
                            <div class="media-name">
                                <span class="media-title"><?= htmlspecialchars($category->getName(), ENT_QUOTES) ?></span>
                            </div>
                        </td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn-edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="icon-btn icon-btn-delete confirm-delete" data-action="<?=$router->generatePath('category-delete') ?>" data-category-id="<?=$category->getId()?>">
                                <a href="<?= $router->generatePath('category-delete', [''])?>" class="material-symbols-outlined">delete</a>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <form id="postDeleteForm" method="POST" style="display:none;"></form>

    <div class="table-footer">
        <p class="table-footer-info">
            Showing <span>1 - <?= count($categories ?? []) ?></span> of <?= count($categories ?? []) ?>
        </p>
        <div class="pagination">
            <button class="page-btn page-btn-outline">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button class="page-btn page-btn-active">1</button>
            <button class="page-btn page-btn-outline">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
    </div>
</section>
<script>
    document.addEventListener("click", e => {
        const link = e.target.closest(".confirm-delete");
        if (!link) return;

        e.preventDefault();

        if (!confirm("Are you sure you want to delete this item?")) return;

        const form = document.getElementById("postDeleteForm");
        form.action = link.dataset.action;
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "category_id";
        form.appendChild(input);
        input.value = link.dataset.categoryId;
        form.submit();
    });
</script>
<?php
$main = ob_get_clean();

include __DIR__ . '/../base_admin.html.php';
?>
