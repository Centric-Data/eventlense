<?php
/**
 *
 * @package events
 */
?>

<?php

  $args = array (
    'post_type'     =>  'centric_events',
    'post_status'   =>  'publish',
    'posts_per_page'  =>  5,
    'order'           =>  'ASC',
  );

  $query = new WP_Query( $args );

?>

<?php
    if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
     $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'medium');
 ?>
<div class="events__card flex justify-between border-b border-gray-300 pb-4 mb-4">
  <div class="events__card--left w-4/5 pl-2">
    <h3><?php the_title(); ?></h3>
    <div class="event__details flex flex-col justify-start align-center gap-1">
      <time class="flex align-center"><span class="material-icons">date_range</span><?php echo esc_attr( get_post_meta( get_the_ID(), 'event_day', true ) ); ?> @ <span><?php echo esc_attr( get_post_meta( get_the_ID(), 'event_time', true ) ); ?> </span> </time>
      <h6 class="flex align-center"><span class="material-icons">location_on</span> <?php echo esc_attr( get_post_meta( get_the_ID(), 'event_venue', true ) ); ?></h6>
    </div>
    <p class="">
      <?php the_excerpt(); ?>
    </p>
    <a class="w-5/6 no-underline mt-8 pt-2 pb-2 pl-4 pr-4 rounded bg-green-500 text-white" href="<?php echo esc_attr( get_post_meta( get_the_ID(), 'event_link', true ) ); ?>">More Details</a>
  </div>
  <div class="events__card--right flex justify-end">
    <img class="w-4/5" src="<?php echo $featured_img_url ?>" alt="Karri Saarinen presenting at Nordic Design">
  </div>
</div>
<?php endwhile; else:
      echo esc_html__( 'Sorry, no events to show', 'eventlense' );
    endif;
   ?>
