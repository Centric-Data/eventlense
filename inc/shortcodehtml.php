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
<div class="events flex flex-wrap justify-between gap-1">
  <?php
      if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
      $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'medium');
  ?>
  <div class="events__card flex flex-col justify-between bg-white text-black rounded p-2 shadow-lg">
    <div class="top__details flex gap-2">
      <div class="bg-yellow-300 p-8 border-4 border-green-500 rounded-full h-24 w-24 flex flex-col items-center justify-center w-2/8">
        <div class="font-semibold text-2xl">
          <?php
            $timestamp =  esc_attr( get_post_meta( get_the_ID(), 'event_day', true ) );
            $totime = strtotime( $timestamp );
            $day = date( 'd', $totime );
            echo $day;
          ?>
        </div>
        <div class="uppercase">
          <?php
            $month = date( 'M', $totime );
            echo $month;
          ?>
        </div>
      </div>
      <div>
        <h4 class="text-base font-normal"><?php the_title(); ?></h4>
        <div class="flex align-center gap-2 text-gray-600">
          <h6 class="font-light flex align-center text-sm">@<?php echo esc_attr( get_post_meta( get_the_ID(), 'event_time', true ) ); ?></h6>
          <h6 class="font-light flex align-center text-sm"><?php echo esc_attr( get_post_meta( get_the_ID(), 'event_venue', true ) ); ?></h6>
        </div>
      </div>
    </div>
    <div class="middle__details mt-2 p-2 border-t border-yellow-500 text-sm text-gray-500 leading-tight">
      <p class="leading-tight"><?php the_excerpt(); ?></p>
    </div>
    <div class="bottom__details flex justify-end text-sm text-gray-100">
      <a class="uppercase no-underline text-black hover:text-yellow-500" href="
        <?php
          $exlink =  esc_attr( get_post_meta( get_the_ID(), 'event_link', true ) );
          $postlink = get_permalink();
          if( ! $exlink ){
            echo $postlink;
          } else {
            echo $exlink;
          }
        ?>
      ">Read More</a>
    </div>
  </div>
  <?php endwhile; else:
        echo esc_html__( 'Sorry, no events to show', 'eventlense' );
      endif;
    ?>
</div>
