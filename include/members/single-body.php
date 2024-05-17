<div class="member-portrait" id="member-portrait">
  <div class="single-columns">
    <div class="single-column">
      <div class="portrait">
        <figure>
          <?php if (isset($portrait)) { ?>
            <img
              src="<?php echo $portrait['src']; ?>"
              srcset="<?php echo implode(',', array_map(function($size) {
                return "{$size['src']} {$size['width']}w";
              }, $portrait['sizes'])); ?>"
              sizes="(min-width: 900px) 50vw, 100vw"
            >
          <?php } ?>
          <small><span><?php echo $portrait_caption; ?></span></small>
        </figure>
      </div>
    </div>
    <div class="single-column text-column">
      <section class="bio-section">
        <div class="section-body">
          <div class="section-content"><?php the_content(); ?></div>
        </div>
        <?php if ($representation) { ?>
          <div class="section-footer">
            <span><?php echo __('Represented by', 'actwall') ?> <?php echo $representation; ?></span>
          </div>
        <?php } ?>
      </section>
      <div class="details accordeons">
        <section class="contact-section">
          <div class="section-header">
            <div class="picto">
              <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                <path d="M16.3858 7.58205L11.6764 12.2914L7.09438 7.70933L5.43975 9.36396L10.0218 13.946L11.8037 15.7279L13.4583 14.0733L18.1677 9.36396L16.3858 7.58205Z" fill="black"/>
              </svg>
            </div>
            <h3><?php echo __('Contact', 'actwall') ?></h3>
          </div>
          <div class="section-body">
            <div class="section-content">
              <ul>
                <?php if ($website) { ?>
                  <li>
                    <a target="_blank" href="<?php echo $website; ?>"><?php echo $website_name ? $website_name : $website; ?></a>
                  </li>
                <?php } ?>
                <?php if ($email) { ?>
                  <li>
                    <a href="mailto:<?php echo $email; ?>"><?php echo __('Email', 'actwall'); ?></a>
                  </li>
                <?php } ?>
                <?php if ($instagram) { ?>
                  <li>
                    <a href="<?php echo $instagram; ?>"><?php echo __('Instagram', 'actwall'); ?></a>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </section>
        <section class="awards-section <?php if (empty($awards)) echo 'hidden'; ?>">
          <?php if(!empty($awards)) { ?>
            <div class="section-header">
              <div class="picto">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                  <path d="M16.3858 7.58205L11.6764 12.2914L7.09438 7.70933L5.43975 9.36396L10.0218 13.946L11.8037 15.7279L13.4583 14.0733L18.1677 9.36396L16.3858 7.58205Z" fill="black"/>
                </svg>
              </div>
              <div class="section-title">
                <h3><?php echo __('Awards', 'actwall') ?></h3>
              </div>
            </div>
            <div class="section-body">
              <div class="section-content">
                <table>
                  <?php foreach ((array) $awards as $award) { ?>
                    <?php $award = (array) $award; ?>
                    <tr>
                      <td><span><?php echo $award['name']; ?></span></td>
                      <td><span><?php echo $award['description']; ?></span></td>
                    </tr>
                  <?php } ?>
                </table>
              </div>
            </div>
          <?php } ?>
        </section>
        <section class="publications-section <?php if (empty($publications)) echo 'hidden'; ?>">
          <?php if(!empty($publications)) { ?>
            <div class="section-header">
              <div class="picto">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                  <path d="M16.3858 7.58205L11.6764 12.2914L7.09438 7.70933L5.43975 9.36396L10.0218 13.946L11.8037 15.7279L13.4583 14.0733L18.1677 9.36396L16.3858 7.58205Z" fill="black"/>
                </svg>
              </div>
              <div class="section-title">
                <h3><?php echo __('Publications', 'actwall') ?></h3>
              </div>
            </div>
            <div class="section-body">
              <div class="section-content">
                <table>
                  <?php foreach ((array) $publications as $publication) { ?>
                    <?php $award = (array) $publication; ?>
                    <tr>
                      <td><span><?php echo $publication['name']; ?></span></td>
                      <td><span><?php echo $publication['description']; ?></span></td>
                    </tr>
                  <?php } ?>
                </table>
              </div>
            </div>
          <?php } ?>
        </section>
        <section class="exhibitions-section <?php if (empty($exhibitions)) echo 'hidden'; ?>">
          <?php if(!empty($exhibitions)) { ?>
            <div class="section-header">
              <div class="picto">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                  <path d="M16.3858 7.58205L11.6764 12.2914L7.09438 7.70933L5.43975 9.36396L10.0218 13.946L11.8037 15.7279L13.4583 14.0733L18.1677 9.36396L16.3858 7.58205Z" fill="black"/>
                </svg>
              </div>
              <div class="section-title">
                <h3><?php echo __('Exhibitions', 'actwall') ?></h3>
              </div>
            </div>
            <div class="section-body">
              <div class="section-content">
                <table>
                  <?php foreach ((array) $exhibitions as $exhibition) { ?>
                    <?php $award = (array) $exhibition; ?>
                    <tr>
                      <td><span><?php echo $exhibition['name']; ?></span></td>
                      <td><span><?php echo $exhibition['description']; ?></span></td>
                    </tr>
                  <?php } ?>
                </table>
              </div>
            </div>
          <?php } ?>
        </section>
<?php /*
        <!-- <section class="publications-section <?php if (empty($publications)) echo 'hidden'; ?>">
          <div class="section-header">
            <div class="picto">
              <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                <path d="M16.3858 7.58205L11.6764 12.2914L7.09438 7.70933L5.43975 9.36396L10.0218 13.946L11.8037 15.7279L13.4583 14.0733L18.1677 9.36396L16.3858 7.58205Z" fill="black"/>
              </svg>
            </div>
            <div class="section-title">
              <h3><?php echo __('Publications', 'actwall') ?></h3>
            </div>
          </div>
          <div class="section-body">
            <div class="section-content">
              <?php echo nl2br($publications); ?>
            </div>
          </div>
        </section>
        <section class="exhibitions-section <?php if (empty($exhibitions)) echo 'hidden'; ?>">
          <div class="section-header">
            <div class="picto">
              <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                <path d="M16.3858 7.58205L11.6764 12.2914L7.09438 7.70933L5.43975 9.36396L10.0218 13.946L11.8037 15.7279L13.4583 14.0733L18.1677 9.36396L16.3858 7.58205Z" fill="black"/>
              </svg>
            </div>
            <div class="section-title">
              <h3><?php echo __('Publications', 'actwall') ?></h3>
            </div>
          </div>
          <div class="section-body">
            <div class="section-content">
              <?php echo nl2br($exhibitions); ?>
            </div>
          </div>
        </section> -->

        */ ?>
      </div>
    </div>
  </div>
</div>
<script>
  addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("member-portrait");
    let currentTab;
    abduct(container, [{
      class: "single-columns",
      children: [
        {},
        {
          class: "single-column",
          children: [
            {
              tag: "section"
            },
            {
              class: "details accordeons",
              update: (element, implant, render) => {
                implant.children = [0, 1, 2, 3].map(tab => {
                  return {
                    tag: "section",
                    update: element => {
                      element.classList.toggle("open", currentTab === tab);
                    },
                    children: [
                      {
                        class: "section-header",
                        update: element => {
                          element.onclick = event => {
                            if (currentTab === tab) {
                              currentTab = null;
                            } else {
                              currentTab = tab;
                            }
                            render();
                          };
                        }
                      },
                      {
                        class: "section-body",
                        init: element => {

                        },
                        update: element => {
                          element.ontransitionend = event => {
                            if (currentTab === tab) {
                              element.style.height = "auto";
                            }
                          }
                          if (element.style.height === "auto") {
                            element.style.height = `${element.firstElementChild.clientHeight}px`;
                          }
                          element.clientHeight;
                          if (currentTab === tab) {
                            element.style.height = `${element.firstElementChild.clientHeight}px`;
                          } else {
                            element.style.height = '0';
                          }
                        }
                      }
                    ]
                  };
                });

                // [
                //   ,
                //   {
                //     class: "awards-section"
                //     tag: "section"
                //   },
                //   {
                //     class: "publications-section"
                //     tag: "section"
                //   },
                //   {
                //     class: "exhibitions-section"
                //     tag: "section"
                //   }
                // ];
              }
            }
          ]
        }
      ]
    }]);

  })
</script>


<?php // do_action('actwall_member_publications', $post->ID); ?>
<?php // do_action('actwall_member_shows', $post->ID); ?>
