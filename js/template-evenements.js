Actwall.Evenements = class extends Actwall.Filters {

  constructor() {

    super();

    this.contentAccordeons = {};
    this.taxonomies = [];

  }

  getResults(paramString) {

    return this.fetch(`evenements${paramString}`);

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

  buildHeader() {
    return {
      tag: "header"
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
            this.buildSearchInput("search", Actwall.__("Search by name")),
            this.buildLettersTab(),
            // this.buildTaxonomyTab({
            //   id: "photo-type",
            //   name: Actwall.__("Types of Photography")
            // }),
            // this.buildTaxonomyTab({
            //   id: "gender",
            //   name: Actwall.__("Gender")
            // }),
            this.buildSearchTab({
              id: "location",
              name: Actwall.__("Location in Switzerland"),
              placeholder: Actwall.__("Location")
            })
          ];
        }
      }
    };
  }

  buildContent() {

    return [
      {
        tag: "ul",
        class: "evenements",
        update: (element, implant, render) => {
          this.renderBody = render;

          if (this.results) {

            implant.children = this.results.map((item, index) => {
              return {
                tag: "li",
                class: "evenement",
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
                    class: "evenement-handle",
                    update: element => {
                      this.contentAccordeons[index].addHandle(element);
                    },
                    children: [
                      {
                        class: "evenement-cell title",
                        child: {
                          tag: "h2",
                          update: element => {
                            element.innerHTML = item.title;
                          }
                        }
                      },
                      {
                        class: "evenement-cell daterange",
                        child: {
                          tag: "span",
                          update: element => {
                            element.innerHTML = item.date_range;
                          }
                        }
                      },
                      {
                        class: "evenement-cell location",
                        child: {
                          tag: "span",
                          update: element => {
                            element.innerHTML = item.location;
                          }
                        }
                      }
                    ]
                  },
                  {
                    class: "evenement-body",
                    child: {
                      class: "evenement-slider",
                      update: element => {
                        this.contentAccordeons[index].addBody(element.parentElement, element);
                      },
                      child: {
                        class: "evenement-columns",
                        children: [
                          {
                            class: "evenement-column left",
                            children: [
                              {
                                class: "evenement-gallery",
                                child: {
                                  tag: "figure",
                                  children: [
                                    {
                                      tag: "img",
                                      update: element => {
                                        if (item.image) {
                                          element.src = item.image.src;
                                          element.srcset = item.image.sizes.map(size => `${size.src} ${size.width}w`).join(",");
                                          element.sizes = "(min-width: 1024px) 1024px, 100vw";
                                        }
                                      }
                                    }
                                  ]
                                }
                              },
                              {
                                class: "more",
                                child: {
                                  tag: "a",
                                  update: element => {
                                    element.onclick = event => {
                                      //
                                    }
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
                            class: "evenement-column right",
                            children: [
                              // {
                              //   class: "evenement-info",
                              //   children: [
                              //     {
                              //       class: "specialisation",
                              //       child: {
                              //         tag: "span",
                              //         update: element => {
                              //           element.innerHTML = item.specialisation || "";
                              //         }
                              //       }
                              //     },
                              //     {
                              //       class: "location",
                              //       child: {
                              //         tag: "span",
                              //         update: element => {
                              //           element.innerHTML = item.location || "";
                              //         }
                              //       }
                              //     }
                              //   ]
                              // },
                              {
                                class: "evenement-content",
                                child: {
                                  class: "text",
                                  update: element => {
                                    element.innerHTML = item.content || "";
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
