<?php
///** @var \App\Model\Title $title */
/** @var \App\Service\Router $router */
/** @var string $title */
/** @var \App\Model\Title $movie */


use App\Model\Platform;

//$title = 'Add New Item';
$bodyClass = 'admin-add';
//$movie = 'movie';

ob_start();

?>
<form method="post" action="<?= $router->generatePath('admin-add-item', $movie? ['id'=>$movie->getId()]:['id'=>null]) ?>" class="admin-form">
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
                   value="<?=$movie? $movie->getTitle():''?>"
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
                       value="<?=$movie? $movie->getYear():''?>"
                       required>
            </div>
            <div class="form-group">
                <label class="form-label">Content Type</label>
                <div class="toggle-group">
                    <label class="toggle-btn">
                        <input type="radio" name="kind" value="movie" <?=($movie? $movie->getKind()==='movie':"")?"checked":""?>>

                        Movie
                    </label>
                    <label class="toggle-btn">
                        <input type="radio" name="kind" value="series" <?=($movie? $movie->getKind()==='series':"")?"checked":""?>>
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
                      required><?= $movie?  $movie->getDescription() : '' ?></textarea>
        </div>
    </section>

    <section class="form-section">
        <div class="form-group">
            <h3 class="section-title">
                <span class="material-symbols-outlined section-icon">category</span>
                Classification
            </h3>
        </div>
        <?php $Categories=\App\Model\Category::findAll();
        $movieCategoriesID=[];
            if($movie){
                $movieCategories=$movie->getCategories();
                foreach ($movieCategories as $movieCategory){
                    $movieCategoriesID[]=$movieCategory->getId();
                }
            }
            ?>
        <label class="form-label">Categories</label>
        <?php foreach ($Categories as $category): ?>
            <?php $isCheckedCat = in_array($category->getId(), $movieCategoriesID)?"checked":""?>
        <label class="tag-item">
            <input type="checkbox" name="genres[]" value="<?= intval($category->getId()) ?>" <?=$isCheckedCat?>>
            <span><?= htmlspecialchars($category->getName()) ?></span>
        </label>
        <?php endforeach; ?>



        <div class="form-divider"></div>
            <?php $platforms=Platform::findAll();
            $moviePlatformsID=[];
            if($movie){
                $moviePlatforms=$movie->getPlatforms();
                foreach ($moviePlatforms as $moviePlatform){
                    $moviePlatformsID[]=$moviePlatform->getId();
                }
            }
            ?>
        <div class="form-group">
            <label class="form-label">Streaming Platforms</label>
            <div class="platform-grid">
                <?php foreach ($platforms as $platform): ?>
                        <?php $isChecked = in_array($platform->getId(), $moviePlatformsID)?"checked":""?>
                    <label class="platform-item">
                        <input type="checkbox"
                               name="platforms[]"
                               value="<?= intval($platform->getId()) ?>"
                               <?=$isChecked?>>
                        <span><?= htmlspecialchars($platform->getName()) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</form>
<?php
$main = ob_get_clean();

include __DIR__ . '/base_admin.html.php';
?>
