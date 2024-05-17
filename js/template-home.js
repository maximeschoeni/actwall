Actwall.Home = class extends Actwall.Filters {

  constructor() {

    super();


    this.taxonomies = ["photo-type", "print", "member-category", "gender"];

    this.filters = {
      orderby: [
        {id: "", name: Actwall.__("All work")},
        {id: "newest", name: Actwall.__("New Work")},
        {id: "low-price", name: Actwall.__("Price (low to high)")},
        {id: "high-price", name: Actwall.__("Price (high to low)")}
      ],
      availability: [
        {id: "1", name: Actwall.__("Already produced")},
        {id: "2", name: Actwall.__("Has to be produced (1-2 weeks)")},
      ],
      size: [
        {id: "1", name: Actwall.__("Small (under 40 cm)")},
        {id: "2", name: Actwall.__("Medium (under 40 - 100 cm)")},
        {id: "3", name: Actwall.__("Large (over 100 cm)")},
      ],
      color: [
        {id: "red", name: Actwall.__("Red")},
        {id: "blue", name: Actwall.__("Blue")},
        {id: "green", name: Actwall.__("Green")},
      ]
    };

  }

  getSchema() {
    let schema = [];
    const sequences = [[0,0,1,0,0],[0,1,0,0,0],[1,0,0,0,0],[1,1,0,0,0,0]];
    while (sequences.length) {
      const index = Math.floor(Math.random()*sequences.length);
      const items = sequences.splice(index, 1);
      schema = [...schema, ...items[0]];
    }
    return schema;
  }

  getResults(paramString) {

    return this.fetch(`photographies${paramString}`);

  }

  async getActiveFilters() {

    const filters = [];

    const search = this.getParam("search");

    if (search) {

      filters.push({
        key: "search",
        value: search
      });

    }

    const location = this.getParam("location");

    if (location) {

      filters.push({
        key: "location",
        value: location
      });

    }

    const priceMin = this.getParam("min-price");

    if (priceMin) {

      filters.push({
        key: "min-price",
        value: `Min: ${priceMin}.-`
      });

    }

    const priceMax = this.getParam("max-price");

    if (priceMax) {

      filters.push({
        key: "max-price",
        value: `Max: ${priceMax}.-`
      });

    }

    for (let taxonomy of this.taxonomies) {

      const current = this.getParam(taxonomy);

      if (current) {

        const terms = await this.fetch(`taxonomy/${taxonomy}`);

        const term = terms.find(term => term.id === current);

        if (term) {

          filters.push({
            key: taxonomy,
            value: term.name
          });

        }

      }

    }

    for (let key in this.filters) {

      const current = this.getParam(key);

      if (current) {

        const option = this.filters[key].find(option => option.id === current);

        if (option) {

          filters.push({
            key: key,
            value: option.name
          });

        }

      }

    }

    return filters;
  }

  buildHeader() {
    return {
      tag: "header",
      class: "header-filter",
      init: element => {
        element.id = "header-filter"
      },
      update: (element, implant, render) => {
        this.renderHeader = render;
      },
      children: [
        {
          class: "filters-button",
          init: element => {
            element.id = "button-filters-container";
          },
          children: this.buildFiltersButton()
        },
        ...this.buildHeaderTaxonomy("image-type"),
        {
          class: "artworks-count",
          child: {
            tag: "span",
            update: element => {
              if (this.response) {
                element.innerHTML = `${this.response.length} ${Actwall.__('Artworks')}`;
              }
            }
          }
        }
      ]
    };
  }

  buildFilters() {
    return {
      class: "filters",
      child: {
        class: "filters-content",
        init: element => {
          this.filtersAccordeon.addBody(element.parentNode, element);
        },
        update: (root, implant, render) => {
          this.renderFilters = render;
          if (this.renderHeaderTaxonomy) {
            this.renderHeaderTaxonomy();
          }
        },
        children: [
          this.buildActiveFilters(),
          this.buildSearchInput("search", Actwall.__("Search by keyword, theme, artists")),
          this.buildFilterTab({
            id: "orderby",
            name: Actwall.__("Sort By")
          }),
          // this.buildTaxonomyTab({
          //   id: "photo-type",
          //   name: Actwall.__("Types of Photography")
          // }),
          // this.buildTaxonomyTab({
          //   id: "print",
          //   name: Actwall.__("Types of Print")
          // }),
          // this.buildTaxonomyTab({
          //   id: "member-category",
          //   name: Actwall.__("Member of Category")
          // }),
          this.buildTaxonomyTab({
            id: "gender",
            name: Actwall.__("Gender")
          }),
          // this.buildSearchTab({
          //   id: "location",
          //   name: Actwall.__("Location in Switzerland"),
          //   placeholder: Actwall.__("Location")
          // }),
          // this.buildFilterTab({
          //   id: "availability",
          //   name: Actwall.__("Availability")
          // }),
          this.buildPriceTab(),
          this.buildFilterTab({
            id: "size",
            name: Actwall.__("Size")
          })
          // this.buildFilterTab({
          //   id: "color",
          //   name: Actwall.__("Colors")
          // })
        ]
      }
    };
  }


  buildContent() {

    return [
      {
        tag: "ul",
        class: "grid vertical-grid",
        update: (element, implant, render) => {
          this.renderBody = render;

          const  schema = this.getSchema();

          if (this.results) {
            implant.children = this.results.filter(item => item.editions && item.editions.length).map((item, itemIndex) => {

              return {
                tag: "li",
                // update: element => {
                //   element.style.margin = `margin:${item.margin.top}em ${item.margin.right}em ${item.margin.bottom}em ${item.margin.left}em`;
                // },
                init: element => {
                  // const size = Math.random() > 0.85 ? "large" : "small";
                  // element.classList.add(size);
                },
                update: (element, implant) => {
                  const editionIndex = Math.floor(Math.random()*item.editions.length);
                  const edition = item.editions[editionIndex];

                  // element.classList.toggle("large", Math.random() > 0.85);
                  element.classList.toggle("large", Boolean(schema[itemIndex%21]));

                  // const size = Math.random() > 0.85 ? "large" : "small";
                  //
                  // element.classList.toggle("large", size === "large");

                  implant.child = {
                    tag: "a",
                    update: element => {

                      // console.log(item);

                      element.onclick = event => {
                        event.preventDefault();
                        Actwall.openProduct(item.id, item.permalink, editionIndex);
                      }


                      element.href = item.permalink || "";
                    },
                    children: [{
                      tag: "figure",
                      update: (element, implant) => {
                        const border_width_cm = edition.has_frame && edition.cadre_width || 0;
                        const padding_v_cm = (edition.paper_height - edition.image_height)/2;
                        const padding_h_cm = (edition.paper_width - edition.image_width)/2;

                        let base_cm;
                        let height_em;
                        let width_em;

                        if (edition.paper_width > edition.paper_height) {

                          base_cm = edition.paper_width + border_width_cm;
                          height_em = "auto";
                          width_em = "1em";

                        } else {

                          base_cm = edition.paper_height + border_width_cm;
                          height_em = "1em";
                          width_em = "auto";

                        }
                        const border_width_em = (border_width_cm/base_cm).toFixed(4);
                        const padding_v_em = (padding_v_cm/base_cm).toFixed(4);
                        const padding_h_em = (padding_h_cm/base_cm).toFixed(4);



                        // const min_height = 80;
                        // const max_height = 120;
                        //
                        // const height = Math.min(max_height, Math.max(min_height, edition.paper_height));
                        // const padding_v = ((height/edition.paper_height)*padding_v_cm).toFixed(4);
                        // const padding_h = ((height/edition.paper_height)*padding_h_cm).toFixed(4);
                        // const border_width = ((height/edition.paper_height)*border_width_cm).toFixed(4);
                        // const element_height = ((height/edition.paper_height)*border_width_cm*2 + height).toFixed(4);



                        implant.children = [
                          {
                            tag: "img",
                            update: element => {
                              element.src = item.src;
                              element.srcset = item.sizes.map(size => `${size.src} ${size.width}w`).join(",");
                              element.sizes = "(min-width: 1024px) 1024px, 100vw";
                              if (edition.has_frame) {
                                element.classList.remove("no-frame");
                                element.classList.add("has-frame");
                                element.style = `
                                  width: ${width_em};
                                  height: ${height_em};
                                  border-width: 0;
                                  border-style: solid;
                                  border-width:${border_width_em}em;
                                  border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
                                  padding:${padding_v_em}em ${padding_h_em}em;`;
                              } else {
                                element.classList.add("no-frame");
                                element.classList.remove("has-frame");
                                element.style = `
                                  width: ${width_em};
                                  height: ${height_em};
                                  padding:${padding_v_em}em ${padding_h_em}em;`;
                              }
                            }
                          },
                          {
                            class: "border",
                            update: element => {
                              if (edition.has_frame) {
                                element.classList.remove("hidden");
                                element.style = `
                                  border-style: solid;
                                  border-width:${border_width_em}em;
                                  border-image:url(${edition.border_img}) ${edition.border_slice} stretch;`;
                              } else {
                                element.classList.add("hidden");
                              }
                            }
                          }

                        ];
                      }
                    },
                      {
                        class: "caption",
                        children: [
                          {
                            tag: "h3",
                            update: element => {
                              element.innerHTML = `${item.member_name}, ${item.title}`;
                            }
                          },
                          {
                            class: "price",
                            update: element => {
                              // element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18">
                              //   <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"></path>
                              // </svg>
                              // ${Actwall.__("From")} ${parseInt(edition.prix_encadre).toLocaleString("de-CH").replace("’", "'")}.-`;
                              element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18">
                                <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"></path>
                              </svg>
                              ${Actwall.__("From")} ${parseInt(item.min_price || edition.prix).toLocaleString("de-CH").replace("’", "'")}.-`;
                            }
                          }
                        ]
                      }
                    ]
                  };
                }
              }
            });
          }
        },
        complete: element => {
          // element.clientWidth;
          // console.log("grid complete", element, element.scrollWidth);
          // setTimeout(() => {
          //
          //   console.log("grid timeout", element, element.scrollWidth);
          // }, 100);
          // requestAnimationFrame(() => {
          //   console.log("requestAnimationFrame", element, element.scrollWidth);
          // });

          // element.onwheel = event => {
          //
          //   // console.log(event.deltaY);
          //
          //   element.scrollBy({
          //     left: event.deltaY,
          //     top: 0,
          //     behavior: "instant"
          //   });
          //
          //   // element.offsetLeft += event.deltaY;
          // }


        }
      },
      {
        class: "grid-placeholder",
        // init: element => {
        //   console.log(element);
        //
        //   const grid = element.previousElementSibling;
        //   const header = document.getElementById("header");
        //
        //   element.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
        //
        //   addEventListener("resize", event => {
        //     element.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
        //   });
        //
        //   addEventListener("scroll", event => {
        //     console.log(scrollY, document.body.scrollHeight, grid.scrollWidth, placeholder.clientHeight);
        //     grid.scrollLeft = scrollY;
        //   });
        //
        // }
        complete: element => {


          // element.style.height = "2000px";

          // const main = element.closest(".popup-content");
          // const grid = element.previousElementSibling;
          // const header = document.getElementById("header");





          // addEventListener("resize", event => {
          //   element.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
          // });

          // console.log(main, element, grid, grid.scrollWidth);

          // element.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
          //
          //
          // setTimeout(() => {
          //   element.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
          //   console.log("xx");
          // }, 1000);


          // grid.onwheel = event => {
          //   element.style.height = `${grid.scrollWidth + window.innerHeight - window.innerWidth - header.clientHeight*3}px`;
          //
          //   console.log("onwheel", element.clientHeight, main);
          // }
          //
          // if (main) {
          //
          //   main.onscroll = event => {
          //
          //     console.log(scrollY, document.body.scrollHeight, grid.scrollWidth, element.clientHeight);
          //     grid.scrollLeft = scrollY;
          //   }
          //
          // }


        }
        // complete: element => {
        //   const grid = element.previousElementSibling;
        //   console.log(grid, grid.scrollWidth);
        // }
      }
    ];
  }

}
