<?php
/** @var \App\Model\Title[] $titles */
/** @var \App\Service\Router $router */
/** @var ?array $csv_result */

$title = 'PLUSFLIX Admin Panel';

ob_start();
?>
<section class="cards-grid">
    <div class="card">
        <div class="card-icon card-icon-primary">
            <span class="material-symbols-outlined">upload_file</span>
        </div>
        <div class="card-body">
            <h3 class="card-title">Import CSV</h3>
            <p class="card-text">
                Upload CSV file.
            </p>
            <?php if (!empty($csv_result)): ?>
                <div class="csv-status <?= $csv_result['status'] === 'success' ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($csv_result['message']) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <form id="csvForm" enctype="multipart/form-data" method="post" action="<?= $router->generatePath('admin-import-csv') ?>" style="display: none;">
                <input type="file"
                       id="csvInput"
                       name="csv_file"
                       accept=".csv"
                       onchange="document.getElementById('csvForm').submit();">
            </form>
            <button class="btn btn-primary btn-block" onclick="document.getElementById('csvInput').click();">
                <span class="material-symbols-outlined">publish</span>
                <span>IMPORT</span>
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-icon card-icon-primary">
            <span class="material-symbols-outlined">settings_input_component</span>
        </div>
        <div class="card-body">
            <h3 class="card-title">Manage Streaming</h3>
<!--            TODO podpiac dodawanie streamingu
-->            <p class="card-text">Add streaming platform</p>
        </div>
        <div class="card-footer card-footer-inline">
            <input type="text" class="input" placeholder="Streaming name...">
            <button class="btn btn-primary">ADD</button>
        </div>
    </div>

    <div class="card">
        <div class="card-icon card-icon-primary">
            <span class="material-symbols-outlined">category</span>
        </div>
        <div class="card-body">
            <h3 class="card-title">Manage Categories</h3>
<!--            TODO podpiac dodawanie kategorii
-->            <p class="card-text">Add category</p>
        </div>
        <div class="card-footer card-footer-inline">
            <input type="text" class="input" placeholder="Category name...">
            <button class="btn btn-primary">ADD</button>
        </div>
    </div>
</section>

<section class="content-header">
    <div class="content-header-left">
        <h2>Content Management</h2>
        <p>Add, edit and monitor your database.</p>
    </div>
    <div class="content-header-right">
        <button onclick="location.href='<?=$router->generatePath('admin-add')?>'" class="btn btn-primary btn-shadow">
            <span class="material-symbols-outlined">add</span>
            <span>Add New Item</span>
        </button>
    </div>
</section>

<section class="tabs-row">
    <div class="tabs">
        <a href="#" class="tab tab-active">All Titles (<?= count($titles ?? []) ?>)</a>
    </div>
    <form method="GET" class="table-search-form">
        <input name="action" value="admin-index" style="display:none";>
        <div class="table-search">
            <span class="material-symbols-outlined table-search-icon">search</span>
            <input type="text"
                   name="q"
                   class="input input-search"
                   placeholder="Search for title..."
                   value="<?= htmlspecialchars($queryParams['q'] ?? '') ?>">
        </div>

        <button type="submit" style="display: none;"></button>
    </form>
</section>

<section class="table-card">
    <div class="table-wrapper">
        <table class="media-table">
            <thead>
            <tr>
                <th>Media</th>
                <th>Name</th>
                <th>Type</th>
                <th>Categories</th>
                <th>Streaming</th>
                <th>Release Date</th>
                <th>Ratings</th>
                <th class="th-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($titles)): ?>
                <tr>
                    <td>
                        No titles found. Import CSV or add items manually.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($titles as $movie): ?>
                    <tr>
                        <td>
                            <div class="media-thumb">
<!--                                TODO pobrac i wyswietlac miniaturki filmow-->
                            </div>
                        </td>
                        <td>
                            <div class="media-name">
                                <span class="media-title"><?= htmlspecialchars($movie->getTitle(), ENT_QUOTES) ?></span>
                            </div>
                        </td>
                        <td>
                                <span class="badge <?= $movie->getKind() === 'series' ? 'badge-type-primary' : 'badge-type-secondary' ?>">
                                    <?= ucfirst(htmlspecialchars($movie->getKind(), ENT_QUOTES)) ?>
                                </span>
                        </td>
                        <td>
                            <div class="badge-row">
                                <?php
                                $cats = $movie->getCategories();
                                if (!empty($cats)) {
                                    $catNames = array_map(fn($c) => $c->getName(), $cats);
                                    foreach (array_slice($catNames, 0, 2) as $catName): ?>
                                        <span class="badge badge-tag"><?= htmlspecialchars($catName, ENT_QUOTES) ?></span>
                                    <?php endforeach;
                                    if (count($catNames) > 2) {
                                        echo '<span class="badge badge-tag">+' . (count($catNames) - 2) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="streaming-cell">
                                <?php
                                $plats = $movie->getPlatforms();
                                if (!empty($plats)) {
                                    $platNames = array_map(fn($p) => $p->getName(), $plats);
                                    foreach (array_slice($platNames, 0, 2) as $platName): ?>
                                        <span class="streaming-label"><?= htmlspecialchars($platName, ENT_QUOTES) ?></span>
                                    <?php endforeach;
                                    if (count($platNames) > 2) {
                                        echo '<span class="streaming-label">+' . (count($platNames) - 2) . '</span>';
                                    }
                                } else {
                                    echo '<span class="streaming-label" style="opacity:0.5">-</span>';
                                }
                                ?>
                            </div>
                        </td>
                        <td class="cell-date">
                            <?= htmlspecialchars($movie->getYear(), ENT_QUOTES) ?>
                        </td>
                        <td>
                            <div class="rating-bar">
                                <div class="rating-top">
<!--                                    TODO pobierac rating z bazy danych-->                                    <span class="rating-good"><?= rand(70, 98) ?>%</span>
                                    <span class="rating-bad"><?= rand(2, 30) ?>%</span>
                                </div>
                                <div class="rating-track">
                                    <div class="rating-fill" style="width: 88%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="cell-actions">
                            <button class="icon-btn icon-btn-edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="icon-btn icon-btn-delete">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <p class="table-footer-info">
            Showing <span>1 - <?= count($titles ?? []) ?></span> of <?= count($titles ?? []) ?>
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
<?php
$main = ob_get_clean();

include __DIR__ . '/base_admin.html.php';
?>
