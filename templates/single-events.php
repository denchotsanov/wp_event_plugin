<?php
get_header();
?>

    <section id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php
            the_post();
            ?>
            <header class="page-header">
                <?php the_title('<h1 class="page-title">', '</h1>'); ?>
            </header><!-- .page-header -->
            <article id="event-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php
                    $post_id = get_the_id();
                    $startdate = strtotime(get_post_meta($post_id, 'event_date', true));
                    $dateformat = get_option('date_format');
                    $current_year = date_i18n('Y', $startdate);
                    echo date_i18n($dateformat, $startdate);

                    $location = get_post_meta($post_id, 'event_location', true);
                    echo '<br>';
                    echo $location;


                    $url = get_post_meta($post_id, 'event_url', true);
                    echo '<br>';
                    echo '<a href="'.$url.'">'.$url.'</a>';

                    ?>
                </div>
            </article>

        </main><!-- #main -->
    </section><!-- #primary -->

<?php
get_footer();
