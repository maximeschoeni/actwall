
<?php /*

<div class="details" id="editions-details">
  <div id="editions-switch"></div>
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
*/ ?>

<div class="details" id="editions-details"></div>
<script>
  {

    const photo = <?php echo json_encode($photo); ?>;
    console.log(photo);
    let current = 0;
    abduct(document.getElementById("editions-details"), [{
      class: "edition-instance",
      update: (element, implant, render) => {
        const currentEdition = photo.editions[current];
        implant.children = [
          {
            class: "editions-switch",
            children: [

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
                        children: photo.editions.map((edition, index) => {
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
            ]
          },
          {
            tag: "a",
            class: "acquire-button",
            init: element => {
              element.href = "mailto:info@actwall.com?subject=subject&body=body";
            },
            child: {
              tag: "span",
              init: element => {
                element.innerHTML = Actwall.__("Acquire this work");
              }
            }
          },
          {
            tag: "ul",
            class: "info",
            init: (element, implant) => {
              implant.complete = element => {
                Accordeon.register(element, "li", ".info-handle", ".info-body");
                this.complete = null;
              }
            },
            // complete: element => {
            //   console.log(element)
            //   Accordeon.register(element, "li", ".info-handle", ".info-body");
            //   this.complete = null;
            // },
            children: [
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.specifications[current]);
                },
                children: [
                  {
                    class: "info-handle",
                    init: element => {
                      element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                        <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                      </svg>
                      <span>${Actwall.__("Specifications")}</span>`;
                    }
                  },
                  {
                    class: "info-body",
                    child: {
                      update: element => {
                        element.innerHTML = photo.specifications[current] || "";
                      }
                    }
                  }
                ]
              },
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.framing[current]);
                },
                children: [
                  {
                    class: "info-handle",
                    init: element => {
                      element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                        <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                      </svg>
                      <span>${Actwall.__("Framing")}</span>`;
                    }
                  },
                  {
                    class: "info-body",
                    child: {
                      update: element => {
                        element.innerHTML = photo.framing[current] || "";
                      }
                    }
                  }
                ]
              },
              // {
              //   tag: "li",
              //   class: "info-handle",
              //   update: element => {
              //     element.classList.toggle("hidden", !photo.signature);
              //   },
              //   children: [
              //     {
              //       class: "info-handle",
              //       init: element => {
              //         element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
              //           <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
              //         </svg>
              //         <span>${Actwall.__("signature")}</span>`;
              //       }
              //     },
              //     {
              //       class: "info-body",
              //       child: {
              //         update: element => {
              //           element.innerHTML = photo.signature || "";
              //         }
              //       }
              //     }
              //   ]
              // },
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.selected_exhibitions);
                },
                children: [
                  {
                    class: "info-handle",
                    init: element => {
                      element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                        <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                      </svg>
                      <span>${Actwall.__("Selected exhibitions")}</span>`;
                    }
                  },
                  {
                    class: "info-body",
                    child: {
                      update: element => {
                        element.innerHTML = photo.selected_exhibitions || "";
                      }
                    }
                  }
                ]
              },
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.acquired);
                },
                children: [
                  {
                    class: "info-handle",
                    init: element => {
                      element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                        <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                      </svg>
                      <span>${Actwall.__("Acquired by institutions")}</span>`;
                    }
                  },
                  {
                    class: "info-body",
                    child: {
                      update: element => {
                        element.innerHTML = photo.acquired || "";
                      }
                    }
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
