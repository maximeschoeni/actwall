<header class="header-bottom" id="header-bottom">
  <div class="page-title">
    <h1><?php echo get_the_title($post->post_parent); ?></h1>
    <a class="close" href="<?php echo home_url(); ?>">
      <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
        <path d="M20.0839 0.991951L11.6636 9.41221L3.67166 1.42026L1.4203 3.67162L9.41225 11.6636L0.991987 20.0838L3.39286 22.4847L11.8131 14.0644L19.8051 22.0564L22.0564 19.805L14.0645 11.8131L22.4847 3.39283L20.0839 0.991951Z" fill="black"/>
      </svg>
    </a>
  </div>
  <div class="followers"><!--<a>120 Followers</a>--></div>
  <ul>
    <li><a href="<?php echo get_permalink($post->post_parent); ?>"><span><?php echo __('Biography', 'actwall'); ?></span></a></li>
    <li><a href="<?php echo get_permalink($post->post_parent); ?>"><span><?php echo __('Portfolio', 'actwall'); ?></span></a></li>
    <li><a href="<?php echo get_permalink($post->post_parent); ?>"><span><?php echo __('All works', 'actwall'); ?></span></a></li>
    <li class="follow">
      <a>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
          <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
        </svg>
        <span>Follow</span>
      </a>
    </li>
  </ul>

</header>
<script>
  {
    const header = document.getElementById("header");
    const headerBottom = document.getElementById("header-bottom");
    new Sticky(
      headerBottom,
      () => header.clientHeight,
      () => header.clientHeight
    );
  }
</script>
