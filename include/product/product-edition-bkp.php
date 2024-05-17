


<div class="details">
  <div id="editions-switch"></div>
  <script>
    {

      const editions = <?php echo json_encode($photo['editions']); ?>;
      let current = 0;
      abduct(document.getElementById("editions-switch"), [{
        class: "edition-instance",
        update: (element, implant, render) => {
          const currentEdition = editions[current];
          implant.children = [
            {
              class: "price",
              child: {
                tag: "span",
                update: element => element.innerHTML = `${currentEdition.prix || ""}.-`
              }
            },
            {
              class: "edition-size",
              children: [
                {
                  class: "edition",
                  child: {
                    tag: "span",
                    update: element => {
                      if (currentEdition.nb_vendu > 1) {
                        element.innerHTML = `Edition de ${currentEdition.nb_edition} • ${currentEdition.nb_vendu} vendues`;
                      } else if (currentEdition.nb_vendu === 1) {
                        element.innerHTML = `Edition de ${currentEdition.nb_edition} • ${currentEdition.nb_vendu} vendue`;
                      } else {
                        element.innerHTML = `Edition de ${currentEdition.nb_edition}`;
                      }

                    }
                  }
                },
                {
                  class: "size",
                  children: [
                    {
                      class: "picto",
                      init: element => element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                        <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                      </svg>`
                    },
                    {
                      tag: "select",
                      update: element => {
                        element.onchange = () => {
                          current = parseInt(element.value);
                          render();
                        }
                      },
                      children: editions.map((edition, index) => {
                        return {
                          tag: "option",
                          update: element => {
                            element.innerHTML = `Size ${edition.format.image_width} x ${edition.format.image_height}CM (${Math.floor(parseInt(edition.format.image_width)*0.393701)} x ${Math.floor(parseInt(edition.format.image_height)*0.393701)} in)`;
                            element.value = index;
                          }
                        }
                      })
                    }
                  ]
                }
              ]
            }
          ];
        }
      }]);
    }
  </script>
  <?php /*
  <?php foreach ($photo['editions'] as $i => $edition) { ?>
    <div class="edition-instance">
      <?php if ($edition['prix_encadre']) { ?>
        <div class="price"><span><?php echo $edition['prix_encadre']; ?>.-</span></div>
      <?php } ?>
      <div class="edition-size">
        <?php if ($edition['nb_edition']) { ?>
          <div class="edition">
            <?php echo sprintf(__('Edition de %d • %d vendues', 'actwall'), $edition['nb_edition'], $edition['nb_vendu']); ?>
          </div>
        <?php } ?>
        <?php if (isset($edition['format']['image_width'], $edition['format']['image_height'])) { ?>
          <div class="size">

            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
              <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
            </svg>
            <select>
              <option>Size <?php echo $edition['format']['image_width']; ?> x <?php echo $edition['format']['image_height']; ?>CM (<?php echo intval($edition['format']['image_width']*0.393701); ?> x <?php echo intval($edition['format']['image_height']*0.393701); ?> in)</option>
              <?php for ($j = 0; $j < count($photo['editions']); $j++) { ?>
                <?php if ($j !== $i) { ?>
                  <option>Size <?php echo $photo['editions'][$j]['format']['image_width']; ?> x <?php echo $photo['editions'][$j]['format']['image_height']; ?>CM (<?php echo intval($photo['editions'][$j]['format']['image_width']*0.393701); ?> x <?php echo intval($photo['editions'][$j]['format']['image_height']*0.393701); ?> in)</option>
                <?php } ?>
              <?php } ?>
            </select>

          </div>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
  <?php */ ?>
  <?php include get_stylesheet_directory().'/include/attachment/acquire-button.php'; ?>
  <?php
    $specifications = isset($photo['specifications'][0]) ? $photo['specifications'][0] : '';
    $framing = isset($photo['framing'][0]) ? $photo['framing'][0] : '';
    $history = $photo['history']; //get_post_meta($post->ID, 'exhibitions-history', true);
    $shipping = $photo['shipping']; //get_post_meta($post->ID, 'shipping', true);

  ?>
  <ul class="info" id="photo-details-info">
    <?php if ($specifications) { ?>
      <li>
        <a class="info-handle">
          <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
            <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
          </svg>
          <span><?php echo __('Specifications', 'actwall'); ?></span>
        </a>
        <div class="info-body">
          <div class="info-content"><?php echo nl2br($specifications); ?></div>
        </div>
      </li>
    <?php } ?>
    <?php if ($framing) { ?>
      <li>
        <a class="info-handle">
          <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
            <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
          </svg>
          <span><?php echo __('Framing', 'actwall'); ?></span>
        </a>
        <div class="info-body">
          <div class="info-content"><?php echo nl2br($framing); ?></div>
        </div>
      </li>
    <?php } ?>
    <?php if ($history) { ?>
      <li>
        <a class="info-handle">
          <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
            <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
          </svg>
          <span><?php echo __('Exhibitions history', 'actwall'); ?></span>
        </a>
        <div class="info-body">
          <div class="info-content"><?php echo nl2br($history); ?></div>
        </div>
      </li>
    <?php } ?>
    <?php if ($shipping) { ?>
      <li>
        <a class="info-handle">
          <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
            <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
          </svg>
          <span><?php echo __('Shipping', 'actwall'); ?></span>
        </a>
        <div class="info-body">
          <div class="info-content"><?php echo nl2br($shipping); ?></div>
        </div>
      </li>
    <?php } ?>
  </ul>
  <script>
    Accordeon.register(document.getElementById("photo-details-info"), "li", ".info-handle", ".info-body");
  </script>
</div>
