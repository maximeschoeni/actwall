<div class="header-newsletter" id="header-newsletter">
  <div class="page-title">
    <h1><?php the_title(); ?></h1>
    <a class="close" href="<?php echo home_url(); ?>">
      <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
        <path d="M20.0839 0.991951L11.6636 9.41221L3.67166 1.42026L1.4203 3.67162L9.41225 11.6636L0.991987 20.0838L3.39286 22.4847L11.8131 14.0644L19.8051 22.0564L22.0564 19.805L14.0645 11.8131L22.4847 3.39283L20.0839 0.991951Z" fill="black"/>
      </svg>
    </a>
  </div>
  <div class="newsletter-link"><a>subscribe to our newsletter</a></div>
</div>
<header class="header-filter" id="header-filter">
  <div class="filters-button" id="button-filters-container">
    <button id="button-filters">
      <div class="picto plus">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
          <g>
            <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
          </g>
        </svg>
      </div>
      <span>Filters</span>
    </button>
  </div>
  <div id="header-categories-container" class="header-categories-container">
    <?php if ($categories && !is_wp_error($categories)) { ?>
      <ul class="categories" id="event-category">
        <?php foreach ($categories as $category) { ?>
          <li><a href="#"><span><?php echo $category->name; ?></span></a></li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>
</header>
<div id="evenements-container">
  <div class="filters" id="filters-container"></div>
    <ul class="evenements" id="evenements">
      <?php foreach ($evenements as $evenement) { ?>
        <li class="evenement">
          <div class="evenement-handle">
            <div class="evenement-cell title">
              <h2><?php echo $evenement['title']; ?></h2>
            </div>
            <div class="evenement-cell daterange">
              <span><?php echo $evenement['date_range']; ?></span>
            </div>
            <div class="evenement-cell location">
              <span><?php echo $evenement['location']; ?></span>
            </div>
          </div>
          <div class="evenement-body">
            <div class="evenement-slider">
              <div class="evenement-columns">
                <div class="evenement-column left">
                  <div class="evenement-gallery">
                    <figure>
                      <?php if (isset($evenement['image'])) { ?>
                        <img
                          src="<?php echo $evenement['image']['src']; ?>"
                          srcset="<?php echo implode(',', array_map(function($size) {
                            return "{$size['src']} {$size['width']}w";
                          }, $evenement['image']['sizes'])); ?>"
                          sizes="(min-width: 1024px) 1024px, 100vw"
                        >
                      <?php } ?>
                    </figure>
                  </div>
                  <div class="more">
                    <a>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
                      </svg>
                      <span><?php echo __('See more', 'actwall'); ?></span>
                    </a>
                  </div>
                </div>
                <div class="evenement-column right">
                  <div class="evenement-content">
                    <div class="text"><?php echo $evenement['content']; ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
      <?php } ?>
    </ul>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/template-filters.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/template-evenements.js"></script>
<script>
  {
    const header = document.getElementById("header");
    const evenementsContainer = document.getElementById("evenements-container");
    const containerFilters = document.getElementById("filters-container");
    const buttonFilterContainer = document.getElementById("button-filters-container");
    const headerFilterContainer = document.getElementById("header-categories-container");
    const headerFilter = document.getElementById("header-filter");
    const headerNewsletter = document.getElementById("header-newsletter");

    new Sticky(
      containerFilters,
      () => {
        // console.log(header.clientHeight + Math.max(0, containerFilters.clientHeight - window.innerHeight));
        return header.clientHeight + Math.max(0, headerFilter.clientHeight + containerFilters.clientHeight - window.innerHeight)
      },
      () => header.clientHeight + headerFilter.clientHeight + headerNewsletter.clientHeight
    );

    new Sticky(
      headerNewsletter,
      () => header.clientHeight,
      () => header.clientHeight
    );

    new Sticky(
      headerFilter,
      () => header.clientHeight,
      () => header.clientHeight + headerNewsletter.clientHeight
    ).update();

    const evenementFilters = new Actwall.Evenements();

    evenementFilters.results = <?php echo json_encode($evenements); ?>;

    abduct(buttonFilterContainer, evenementFilters.buildFiltersButton());
    abduct(headerFilterContainer, evenementFilters.buildHeaderTaxonomy("event-category"));
    abduct(evenementsContainer, evenementFilters.build());

  }
</script>
