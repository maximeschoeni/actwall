<?php /*
<figure
  class="desktop-only <?php echo ($edition['width'] > $edition['height']) ? 'panorama' : 'portrait'; ?>"


  >
  <?php if (isset($edition['has_frame'], $edition['border_width'], $edition['border_img'], $edition['border_slice']) && $edition['has_frame']) { ?>
  <img
    src="<?php echo $photo['src']; ?>"
    srcset="<?php echo implode(',', array_map(function($size) {
      return "{$size['src']} {$size['width']}w";
    }, $photo['sizes'])); ?>"
    sizes="(min-width: 1024px) 1024px, 100vw"
    style="
      width:<?php echo $edition['width']; ?>em;
      height:<?php echo $edition['height']; ?>em;
      border-style: solid;
      border-width:<?php echo $edition['border_width']; ?>em;
      border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
      padding:<?php echo $edition['padding_top']; ?>em <?php echo $edition['padding_right']; ?>em <?php echo $edition['padding_bottom']; ?>em <?php echo $edition['padding_right']; ?>em;
      "
  >
  <div
    class="border"
      style="

        border-style: solid;
        border-width:<?php echo $edition['border_width']; ?>em;
        border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
        padding:<?php echo $edition['padding_top']; ?>em <?php echo $edition['padding_right']; ?>em <?php echo $edition['padding_bottom']; ?>em <?php echo $edition['padding_left']; ?>em;
        "
    ></div>
  <?php } else { ?>
    <img
      class="no-frame"
      src="<?php echo $photo['src']; ?>"
      srcset="<?php echo implode(',', array_map(function($size) {
        return "{$size['src']} {$size['width']}w";
      }, $photo['sizes'])); ?>"
      sizes="(min-width: 1024px) 1024px, 100vw"
      style="
        width:<?php echo $edition['width']; ?>em;
        height:<?php echo $edition['height']; ?>em;
        padding:<?php echo $edition['padding_top']; ?>em <?php echo $edition['padding_right']; ?>em <?php echo $edition['padding_bottom']; ?>em <?php echo $edition['padding_left']; ?>em;
        "
    >
    <div class="border hidden"></div>
  <?php } ?>
</figure>
*/ ?>


<?php
  $width = (float) $edition['paper_width'];
  $height = (float) $edition['paper_height'];
  $ratio = $width/$height;
  // var_dump($ratio);
  $ratio_class = '';
  if ($ratio < 0.8) {
    $ratio_class = 'portrait';
  } else if ($ratio > 1.1) {
    $ratio_class = 'panorama';
  } else if ($ratio > 1.8) {
    $ratio_class = 'very-large';
  }

?>


<figure class="<?php echo $ratio_class; ?>">
  <?php if (isset($edition['has_frame'], $edition['border_width'], $edition['border_img'], $edition['border_slice']) && $edition['has_frame']) { ?>
  <img
    src="<?php echo $photo['src']; ?>"
    srcset="<?php echo implode(',', array_map(function($size) {
      return "{$size['src']} {$size['width']}w";
    }, $photo['sizes'])); ?>"
    sizes="(min-width: 1024px) 1024px, 100vw"
    style="
      width:<?php echo $edition['paper_width']/$edition['paper_height']; ?>em;
      height:1em;
      border-style: solid;
      border-width:<?php echo $edition['cadre_width']/$edition['paper_height']; ?>em;
      border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
      padding:<?php echo ($edition['paper_height'] - $edition['image_height'])/$edition['paper_height']/2 ?>em <?php echo ($edition['paper_width'] - $edition['image_width'])/$edition['paper_height']/2 ?>em;
      "
  >
  <div
    class="border"
    style="

      border-style: solid;
      border-width:<?php echo $edition['cadre_width']/$edition['paper_height']; ?>em;
      border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
        "
    ></div>
  <?php } else { ?>
    <img
      class="no-frame"
      src="<?php echo $photo['src']; ?>"
      srcset="<?php echo implode(',', array_map(function($size) {
        return "{$size['src']} {$size['width']}w";
      }, $photo['sizes'])); ?>"
      sizes="(min-width: 1024px) 1024px, 100vw"
      style="
        width:<?php echo $edition['paper_width']/$edition['paper_height']; ?>em;
        height:1em;
        padding:<?php echo ($edition['paper_height'] - $edition['image_height'])/$edition['paper_height']/2 ?>em <?php echo ($edition['paper_width'] - $edition['image_width'])/$edition['paper_height']/2 ?>em;
        "
    >
    <div class="border hidden"></div>
  <?php } ?>
</figure>


<?php /*
<figure class="mobile-only <?php echo ($edition['width'] > $edition['height']) ? 'panorama' : 'portrait'; ?>">
  <?php if (isset($edition['has_frame'], $edition['border_width'], $edition['border_img'], $edition['border_slice']) && $edition['has_frame']) { ?>
  <img
    src="<?php echo $photo['src']; ?>"
    srcset="<?php echo implode(',', array_map(function($size) {
      return "{$size['src']} {$size['width']}w";
    }, $photo['sizes'])); ?>"
    sizes="(min-width: 1024px) 1024px, 100vw"
    style="
      width:1em;
      height:<?php echo $edition['paper_height']/$edition['paper_width']; ?>em;
      border-style: solid;
      border-width:<?php echo $edition['cadre_width']/$edition['paper_width']; ?>em;
      border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
      padding:<?php echo ($edition['paper_height']-$edition['image_height'])/$edition['paper_width']/2; ?>em <?php echo ($edition['paper_width']-$edition['image_width'])/$edition['paper_width']/2; ?>em;
      "
  >
  <div
    class="border"
    style="
      border-style: solid;
      border-width:<?php echo $edition['cadre_width']/$edition['paper_width']; ?>em;
      border-image: url(<?php echo $edition['border_img']; ?>) <?php echo $edition['border_slice']; ?> stretch;
      padding:<?php echo ($edition['paper_height']-$edition['image_height'])/$edition['paper_width']/2; ?>em <?php echo ($edition['paper_width']-$edition['image_width'])/$edition['paper_width']/2; ?>em;
      "
    ></div>
  <?php } else { ?>
    <img
      class="no-frame"
      src="<?php echo $photo['src']; ?>"
      srcset="<?php echo implode(',', array_map(function($size) {
        return "{$size['src']} {$size['width']}w";
      }, $photo['sizes'])); ?>"
      sizes="(min-width: 1024px) 1024px, 100vw"
      style="
        width:1em;
        height:<?php echo $edition['paper_height']/$edition['paper_width']; ?>em;
        padding:<?php echo ($edition['paper_height']-$edition['image_height'])/$edition['paper_width']/2; ?>em <?php echo ($edition['paper_width']-$edition['image_width'])/$edition['paper_width']/2; ?>em;
        "
    >
    <div class="border hidden"></div>
  <?php } ?>
</figure>
*/ ?>
