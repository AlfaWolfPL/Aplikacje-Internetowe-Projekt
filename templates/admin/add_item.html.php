<?php
/** @var \App\Service\Router $router */

$title = 'Add New Item';
$bodyClass = 'admin-add';

ob_start();

?>
<form method="post" action="<?= $router->generatePath('admin-add-item') ?>" class="admin-form">
    <section class="page-header">
        <div class="page-header-left">
<!--            TODO mozna uzyc tej strony takze do edycji danego filmu tylko trzeba wypelniac od razu formularz danymi filmu po id
            i wrzucic teksty typu "Create New Entry" w zmienna, zeby mozna bylo wypisywac Create albo Edit w zaleznosci skad sie trafi na formularz-->
            <h1>Create New Entry</h1>
            <p>Add new movie/series to database</p>
        </div>
        <div class="page-header-right">
            <a href="<?= $router->generatePath('admin-index') ?>" class="btn btn-outline-primary">Discard</a>
<!--            TODO podpiac przycisk
-->            <button type="submit" class="btn btn-primary btn-shadow">Save</button>
        </div>
    </section>

    <section class="form-section">
        <div class="form-section-header">
            <h3 class="section-title">
                <span class="material-symbols-outlined section-icon">info</span>
                General Information
            </h3>
        </div>

        <div class="form-group">
            <label class="form-label">Title Name</label>
            <input type="text"
                   name="title"
                   class="form-input"
                   placeholder="Title..."
                   required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Year of Production</label>
                <input type="number"
                       name="year"
                       class="form-input"
                       placeholder="2026"
                       min="1900"
                       max="<?= date('Y') + 1 ?>"
                       required>
            </div>
            <div class="form-group">
                <label class="form-label">Content Type</label>
                <div class="toggle-group">
                    <label class="toggle-btn">
                        <input type="radio" name="type" value="movie" checked>
                        Movie
                    </label>
                    <label class="toggle-btn">
                        <input type="radio" name="type" value="series">
                        TV Series
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description"
                      class="form-textarea"
                      rows="6"
                      placeholder="Short description..."
                      required></textarea>
        </div>
    </section>

    <section class="form-section">
        <div class="form-group">
            <h3 class="section-title">
                <span class="material-symbols-outlined section-icon">category</span>
                Classification
            </h3>
        </div>
<!--        TODO pobierac kategorie dynamicznie z bazy danych
-->        <label class="form-label">Categories</label>
        <label class="tag-item">
            <input type="checkbox" name="genres[]" value="action">
            <span>Action</span>
        </label>
        <label class="tag-item">
            <input type="checkbox" name="genres[]" value="scifi" checked>
            <span>Sci-Fi</span>
        </label>
        <label class="tag-item">
            <input type="checkbox" name="genres[]" value="comedy">
            <span>Comedy</span>
        </label>


        <div class="form-divider"></div>
<!--        TODO pobierac dynamicznie streamingi z bazy danych
-->        <div class="form-group">
            <label class="form-label">Streaming Platforms</label>
            <div class="platform-grid">
                <label class="platform-item">
                    <input type="checkbox" name="platforms[]" value="netflix" checked>
                    <span>Netflix</span>
                </label>
                <label class="platform-item">
                    <input type="checkbox" name="platforms[]" value="disney">
                    <span>Disney+</span>
                </label>
                <label class="platform-item">
                    <input type="checkbox" name="platforms[]" value="prime">
                    <span>Prime Video</span>
                </label>
                <label class="platform-item">
                    <input type="checkbox" name="platforms[]" value="apple">
                    <span>Apple TV</span>
                </label>
            </div>
        </div>
    </section>
</form>
<?php
$main = ob_get_clean();

include __DIR__ . '/base_admin.html.php';
?>
