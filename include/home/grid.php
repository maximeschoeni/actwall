<!-- <div class="inline-grid-container" id="inline-grid-container"> -->

<?php
  $schema = [];
  $sequences = [[0,0,1,0,0],[0,1,0,0,0],[1,0,0,0,0],[1,1,0,0,0,0]];
  shuffle($sequences);
  foreach ($sequences as $sequence) {
    $schema = array_merge($schema, $sequence);
  }

?>
  <ul class="grid vertical-grid" id="grid">
    <?php foreach ($photos as $photo_index => $photo) { ?>

      <?php

        if (empty($photo['editions'])) {

          // var_dump($photo);

          continue;
        }

        $edition_index = array_rand($photo['editions']);
        $edition = $photo['editions'][$edition_index];

        // if (rand(0, 100) < 85) {
        //
        //   $size = 'small';
        //
        // } else {
        //
        //   $size = 'large';
        //
        // }

        // var_dump($photo_index, $photo_index%21, $schema[$photo_index%21]);

        if ($schema[$photo_index%21]) {

          $size = 'large';

        } else {

          $size = 'small';

        }


      ?>

      <li class="<?php echo $size; ?>">

        <a href="<?php echo $photo['permalink']; ?>" onclick="return Actwall.openProduct(<?php echo $photo['product_id']; ?>, '<?php echo $photo['permalink']; ?>', <?php echo $edition_index; ?>);">
          <figure>

            <?php


              $paper_height_cm = (float) $edition['paper_height'];
              $paper_width_cm = (float) $edition['paper_width'];
              $image_height_cm = (float) $edition['image_height'];
              $image_width_cm = (float) $edition['image_width'];

              if ($edition['has_frame']) {

                $border_width_cm = (float) $edition['cadre_width'];

              } else {

                $border_width_cm = 0;

              }

              $padding_v_cm = ($paper_height_cm - $image_height_cm)/2;
              $padding_h_cm = ($paper_width_cm - $image_width_cm)/2;


              if ($paper_width_cm > $paper_height_cm) {

                $base_cm = $paper_width_cm + $border_width_cm;
                $height = 'auto';
                $width = '1em';

              } else {

                $base_cm = $paper_height_cm + $border_width_cm;
                $height = '1em';
                $width = 'auto';

              }

              $border_width = $border_width_cm/$base_cm;
              $padding_v = $padding_v_cm/$base_cm;
              $padding_h = $padding_h_cm/$base_cm;


              // $min_height = 80;
              // $max_height = 120;
              //
              // $height = max($min_height, $paper_height);
              // $height = min($max_height, $height);

              $padding_v_string = number_format($padding_v, 4, '.', '');
              $padding_h_string = number_format($padding_h, 4, '.', '');
              $border_width_string = number_format($border_width, 4, '.', '');

              // $element_height = number_format(($height/$paper_height)*$border_width_cm*2 + $height, 4, '.', '');

            ?>
            <img
              class="<?php if ($edition['has_frame']) echo 'has-frame'; else echo 'no-frame' ?>"
              src="<?php echo $photo['src']; ?>"
              srcset="<?php echo implode(',', array_map(function($size) {
                return "{$size['src']} {$size['width']}w";
              }, $photo['sizes'])); ?>"
              sizes="(min-width: 1024px) 1024px, 100vw"
              style="
                width: <?php echo $width ?>;
                height: <?php echo $height ?>;
                <?php if ($edition['has_frame']) { ?>
                  border-style: solid;
                  border-width:<?php echo $border_width_string; ?>em;
                  border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
                <?php } ?>
                padding: <?php echo $padding_v_string; ?>em <?php echo $padding_h_string; ?>em;
                "
            >
            <div
              class="border <?php if (!$edition['has_frame']) echo 'hidden'; ?>"
              style="
                <?php if ($edition['has_frame']) { ?>
                  border-style: solid;
                  border-width:<?php echo $border_width_string; ?>em;
                  border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
                  <?php } ?>
                "
              >
            </div>
            <?php /* } else { ?>
              <img
                class="no-frame"
                src="<?php echo $photo['src']; ?>"
                srcset="<?php echo implode(',', array_map(function($size) {
                  return "{$size['src']} {$size['width']}w";
                }, $photo['sizes'])); ?>"
                sizes="(min-width: 1024px) 1024px, 100vw"
                style="
                  width:<?php echo $edition['width']; ?>em;
                  height:auto;
                  padding:<?php echo $edition['padding_top']; ?>em <?php echo $edition['padding_right']; ?>em <?php echo $edition['padding_bottom']; ?>em <?php echo $edition['padding_left']; ?>em;
                  "
              >
              <div class="border hidden"></div>
            <?php } */ ?>
          </figure>
          <div class="caption">
            <h3><?php echo $photo['member_name']; ?></h3>
            <h4><?php echo $photo['title']; ?></h4>
            <div class="price">
              <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18">
                <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
              </svg>
              <?php
                $min_price = (float) $photo['min_price'];
                if (!$min_price) {
                  $min_price = $edition['prix'];
                }

              ?>
            <?php echo __('From', 'actwall'); ?> <?php echo number_format($min_price, 0, ".", "'"); ?>.-
            </div>
          </div>
        </a>

      </li>
    <?php } ?>
  </ul>
<!-- </div> -->
<div class="grid-placeholder" id="grid-placeholder">
  <script>
    // addEventListener("DOMContentLoaded", event => {
    //   const grid = document.getElementById("grid");
    //   const placeholder = document.getElementById("grid-placeholder");
    //   const header = document.getElementById("header");
    //
    //   placeholder.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
    //
    //   addEventListener("resize", event => {
    //
    //     placeholder.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
    //   });
    //
    //   addEventListener("scroll", event => {
    //
    //     // console.log(scrollY, document.body.scrollHeight, grid.scrollWidth, placeholder.clientHeight);
    //
    //     grid.scrollLeft = scrollY;
    //   });
    //
    //
    //
    // });
  </script>
</div>
