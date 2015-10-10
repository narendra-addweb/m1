<?php
/**
 * Archive page for post type: "appica_news"
 *
 * @since 1.0.0
 *
 * @author 8guild
 * @package Appica
 */

get_header(); ?>

	<section class="space-top padding-bottom">
		<div class="container">

			<?php if ( have_posts() ) : ?>

				<div class="page-heading">
					<h2><?php _e( 'News', 'appica' ); ?></h2>
				</div>

				<div class="row">

					<?php $i = 0; while ( have_posts() ): the_post(); ?>

				<?php if ( 0 === $i % 2 ) : ?>
				</div><div class="row">
				<?php endif; ?>

					<div class="col-sm-6">
						<div class="news-block">
							<span><?php the_time( get_option( 'date_format' ) ); ?></span>
							<a href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a>
							<div class="content">
								<?php the_excerpt(); ?>
							</div>
							<a href="<?php the_permalink(); ?>"><?php __( 'More', 'appica' ); ?></a>
						</div>
					</div>

					<?php $i++; endwhile; ?>

				</div>

				<?php appica_paginate_links(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>


		</div>
	</section>

<?php get_footer(); ?>