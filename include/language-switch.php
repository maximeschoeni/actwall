<ul class="language-switch">
  <?php foreach ($languages as $language) { ?>
    <li class="<?php echo $language->post_name; ?>">
      <a href="<?php echo $sublanguage->get_translation_link($language); ?>" class="<?php if ($sublanguage->current_language->ID == $language->ID) echo 'active'; ?>"><?php echo $language->post_name; ?></a>
    </li>
  <?php } ?>
</ul>
