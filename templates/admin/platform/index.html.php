<?php
/** @var \App\Model\Platform[] $platforms */
/** @var \App\Service\Router $router */

$title = 'PLUSFLIX Admin Panel';

ob_start();
?>
<section class="cards-grid">
    <form id="platformForm" class="card" method="post" action="<?= $router->generatePath('platform-add') ?>">
        <div class="card-icon card-icon-primary">
            <span class="material-symbols-outlined">settings_input_component</span>
        </div>
        <div class="card-body">
            <h3 class="card-title">Manage platforms</h3>
            <p class="card-text">Add platform</p>
        </div>
        <div class="card-footer card-footer-inline">
            <input
                    type="text"
                    name="platform_name"
                    class="input"
                    placeholder="platform name..."/>
            <?php if (!empty($add_platform_result)): ?>
                <div class="csv-status <?= $add_platform_result['status'] === 'success' ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($add_platform_result['message']) ?>
                </div>
            <?php endif; ?>
            <button id="platformFormBtn" class="btn btn-primary" onclick="document.getElementById('platformForm').submit();">ADD</button>
        </div>
    </form>
</section>


<section class="tabs-row">
    <div class="tabs">
        <a href="#" class="tab tab-active">All platforms (<?= count($platforms ?? []) ?>)</a>
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
            <?php if (empty($platforms)): ?>
                <tr>
                    <td>
                        No platforms found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($platforms as $platform): ?>
                    <tr>
                        <td>
                            <div class="media-name">
                                <span class="media-title"><?= htmlspecialchars($platform->getName(), ENT_QUOTES) ?></span>
                            </div>
                        </td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn-edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="icon-btn icon-btn-delete confirm-delete" data-action="<?=$router->generatePath('platform-delete') ?>" data-platform-id="<?=$platform->getId()?>">
                                <a href="<?= $router->generatePath('platform-delete', [''])?>" class="material-symbols-outlined">delete</a>
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
            Showing <span>1 - <?= count($platforms ?? []) ?></span> of <?= count($platforms ?? []) ?>
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
        input.name = "platform_id";
        form.appendChild(input);
        input.value = link.dataset.platformId;
        form.submit();
    });
</script>
<?php
$main = ob_get_clean();

include __DIR__ . '/../base_admin.html.php';
?>
