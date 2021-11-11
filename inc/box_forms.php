<div class="events__box">
  <style scoped>
    .events__box{
      display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
    }
    p{
      display: contents;
    }
  </style>
  <p>
    <label for="event_time">Event Time</label>
    <input type="time" name="event_time" id="event_time" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'event_time', true ) ); ?>">
  </p>
  <p>
    <label for="event_day">Event Day</label>
    <input type="date" name="event_day" id="event_day" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'event_day', true ) ); ?>">
  </p>
  <p>
    <label for="event_venue">Event Venue</label>
    <input type="text" name="event_venue" id="event_venue" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'event_venue', true ) ); ?>">
  </p>
  <p>
    <label for="event_link">Event Link</label>
    <input type="text" name="event_link" id="event_link" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'event_link', true ) ); ?>">
  </p>

</div>
