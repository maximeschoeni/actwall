Actwall.Photographers = class extends Actwall.Filters {

  constructor() {

    super();

    this.contentAccordeons = {};
    this.taxonomies = ["photo-type", "gender"];

  }

  getResults(paramString) {

    return this.fetch(`photographers${paramString}`);

  }

  async getActiveFilters() {

    const filters = [];

    const letter = this.getParam("letter");

    if (letter) {

      filters.push({
        key: "letter",
        value: letter
      });

    }

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

    return filters;
  }

  // buildHeader() {
  //   return {
  //     tag: "header"
  //   };
  // }
  buildHeader() {
    return {
      tag: "header",
      class: "header-filter",
      init: element => {
        element.id = "header-filter"
      },
      children: [
        {
          class: "filters-button",
          init: element => {
            element.id = "button-filters-container";
          },
          children: this.buildFiltersButton()
        },
        // this.buildHeaderTaxonomy("image-type"),
        // ...this.buildHeaderTaxonomy("member-category")
        // {
        //   class: "artworks-count",
        //   child: {
        //     tag: "span",
        //     update: element => {
        //       element.innerHTML = `201 ARTWORKS`;
        //     }
        //   }
        // }
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

          implant.children = [
            this.buildActiveFilters(),
            this.buildSearchInput("search", Actwall.__("Search by keyword, theme, artists")),
            this.buildLettersTab(),
            // this.buildTaxonomyTab({
            //   id: "photo-type",
            //   name: Actwall.__("Types of Photography")
            // }),
            this.buildTaxonomyTab({
              id: "gender",
              name: Actwall.__("Gender")
            })
            // this.buildSearchTab({
            //   id: "location",
            //   name: Actwall.__("Location in Switzerland"),
            //   placeholder: Actwall.__("Location")
            // })
          ];
        }
      }
    };
  }

  buildContent() {

    return [
      {
        tag: "ul",
        class: "photographers",
        update: (element, implant, render) => {
          this.renderBody = render;

          if (this.results) {

            implant.children = this.results.map((item, index) => {
              return {
                tag: "li",
                class: "photographer",
                // init: (element, implant) => {
                //   this.contentAccordeons[index] = new Accordeon(element, ".handle", ".slider");
                // },
                update: (element, implant) => {
                  if (!this.contentAccordeons[index]) {
                    this.contentAccordeons[index] = new Accordeon(element, ".handle", ".slider");
                  }
                  this.contentAccordeons[index].close();
                },
                children: [
                  {
                    class: "photographer-handle",
                    update: element => {
                      this.contentAccordeons[index].addHandle(element);
                    },
                    child: {
                      tag: "h2",
                      update: element => {
                        element.innerHTML = item.title;
                      }
                    }
                  },

                  {
                    class: "photographer-body",
                    child: {
                      class: "photographer-slider",
                      update: element => {
                        this.contentAccordeons[index].addBody(element.parentElement, element);
                      },
                      child: {
                        class: "photographer-columns",
                        children: [
                          {
                            class: "photographer-column left",
                            children: [
                              {
                                class: "photographer-gallery",
                                update: (element, implant) => {
                                  // load images
                                  this.contentAccordeons[index].onOpen = async () => {
                                    element.innerHTML = await this.fetchText(`member_photos/${item.id}?num=3`);

                                    // const products = await this.fetch(`member_products/${item.id}?num=3`);
                                    // implant.children = [Actwall.SingleProduct.prototype.buildOtherWork(products)];
                                  }
                                }
                              },
                              {
                                class: "more desktop-only",
                                child: {
                                  tag: "a",
                                  update: element => {
                                    element.href = item.permalink;
                                    // element.onclick = event => {
                                    //   //
                                    // }
                                  },
                                  init: element => {
                                    element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                      <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
                                    </svg>
                                    <span>${Actwall.__('See more')}</span>`;
                                  }
                                }
                              }
                            ]
                          },
                          {
                            class: "photographer-column right",
                            children: [
                              {
                                class: "photographer-info",
                                children: [
                                  {
                                    class: "specialisation",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = item.specialisation || "";
                                      }
                                    }
                                  },
                                  {
                                    class: "location",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = item.location || "";
                                      }
                                    }
                                  }
                                ]
                              },
                              {
                                class: "photographer-content",
                                child: {
                                  class: "text",
                                  update: element => {
                                    element.innerHTML = item.content || "";
                                  }
                                }
                              },
                              {
                                class: "more desktop-only",
                                child: {
                                  tag: "a",
                                  update: element => {
                                    element.href = item.permalink;
                                  },
                                  init: element => {
                                    element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                      <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
                                    </svg>
                                    <span>${Actwall.__('See more')}</span>`;
                                  }
                                }
                              }
                            ]
                          }
                        ]
                      }
                    }
                  }
                ]
              };
            });
          }
        }
      }
    ];
  }

}
