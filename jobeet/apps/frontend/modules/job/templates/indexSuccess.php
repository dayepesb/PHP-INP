<?php use_stylesheet('jobs.css') ?>
<?php use_javascript('../bower_components/jquery/dist/jquery.js') ?>
<?php use_javascript('../bower_components/angular/angular.js') ?>
<?php use_javascript('../bower_components/angular-resource/angular-resource.js') ?>
<?php use_javascript('../homepage/app/list/list.module.js') ?>
<?php use_javascript('../homepage/app/list/list.component.js') ?>
<?php use_javascript('../homepage/app/app.js') ?>
<div id="jobs">
    <?php foreach ($categories as $category): ?>
        <div class="category_<?php echo Jobeet::slugify($category->getname()) ?>">
            <div class="category">
                <div class="feed">
                    <a href="<?php echo url_for('category', array('sf_subject' => $category, 'sf_format' => 'atom')) ?>">feed</a>
                </div>
                <h1><?php echo link_to($category, 'category', $category) ?></h1>
            </div>

            <?php include_partial('job/list', array('jobs' => $category->getactivejobs(sfconfig::get('app_max_jobs_on_homepage')))) ?>


            <?php if (($count = $category->countactivejobs() - sfconfig::get('app_max_jobs_on_homepage')) > 0): ?>
                <div class="more_jobs">
                    and <?php echo link_to($count, 'category', $category) ?>
                    more...
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <list-jobs></list-jobs>
</div>
