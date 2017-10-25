<?php
// Template Name: Audit Commitee

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <div class="page-bg"></div>

    <div class="audit-commitee-page overflow-hidden">
        
        <div class="audit-commitee-page__section audit-commitee-page__section--1">
            <h2><?php the_title(); ?></h2>
            <hr>
            <?php the_content(); ?>
        </div>
        
        <div class="audit-commitee-page__section audit-commitee-page__section--2">
            
            <p><?php _e("AS Pro Kapital Grupp Council has formed the Audit Committee whose members are:", "prokapital"); ?></p>

            <ul class="people-list audit-commitee-page__people-list">

	            <?php if( have_rows('auditors_repeater') ): ?>
		            <?php while( have_rows('auditors_repeater') ) : the_row(); ?>
                        <li class="people-list__item contact-card audit-commitee-page__contact-card">

                            <div class="contact-card__image-container">
                                <div class="contact-card__image-wrapper">
                                    <img src="<?= get_sub_field("auditor_image"); ?>" />
                                </div>
                            </div>

                            <div class="contact-card__text">
                                <h3 class="contact-card__title"><?= get_sub_field('auditor_name'); ?></h3>
                                <p class="contact-card__subtitle"><?= get_sub_field("auditor_profession"); ?></p>
                                <hr>
                                <div class="contact-card__body">
                                    <?= get_sub_field("auditor_description"); ?>
                                </div>
                            </div>

                        </li>

		            <?php endwhile; ?>
	            <?php endif; ?>
            </ul>
            
        </div>
        
        <div class="audit-commitee-page__section audit-commitee-page__section--3">
            <div class="framed-image" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/dist/img/auditor_page_bg.jpg');"></div>
        </div>

    </div>
	
<?php endwhile; ?>

<?php
// get_sidebar();
get_footer();
