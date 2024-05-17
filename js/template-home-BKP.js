Actwall.Home = class {

  static async render() {

    if (this.renderFilters) {

      await this.renderFilters();
    }

    if (this.renderBody) {

      await this.renderBody();
    }

  }

  static fetch(paramString) {

    if (!this.cache[paramString]) {

      this.cache[paramString] = fetch(`${Actwall.rest_url}actwall/v1/${paramString}`).then(response => response.json());

    }

    return this.cache[paramString];

  }

  // static fetchTaxonomy(taxonomy) {
  //
  //   return this.fetch(`taxonomy/${taxonomy}`);
  //
  // }

  static getParam(key) {

    return this.store[key];

  }

  static setParam(key, value) {

    this.store[key] = value;

  }

  static removeParam(key) {

    delete this.store[key];

  }

  static async setFilter(key, value) { // -> used by page image-typesl links in header

    this.setParam(key, value);

    await this.renderFilters();
    await this.query();
    await this.renderBody();
  }

  static async query() {

    let paramString = Object.entries(this.store).filter(([key, value]) => value).map(([key, value]) => `${key}=${value}`).join("&");

    if (paramString) {

      paramString = `?${paramString}`;

    }

    this.results = await this.fetch(`photographies${paramString}`);

  }

  static async getActiveFilters() {

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

    for (let taxonomy of Actwall.Home.taxonomies) {

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

    for (let key in Actwall.Home.filters) {

      const current = this.getParam(key);

      if (current) {

        const option = Actwall.Home.filters[key].find(option => option.id === current);

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

  static updateImageTypes() {

    const imageTypes = document.getElementById("image-types");
    const currentFileType = this.getParam("image-type") || Actwall.defaultImageType;
    const links = imageTypes.querySelectorAll("a");
    for (let link of links) {
      link.classList.remove("active");
    }
    const link = document.getElementById(`image-type-${currentFileType}`);
    if (link) {
      link.classList.add("active");
    }

  }

	static debounce(callback, interval = 1000) {

	  if (this.debounceTimer) {

	    clearTimeout(this.debounceTimer);

	  }

	  this.debounceTimer = setTimeout(() => callback(), interval);

	}

  static buildFiltersButton() {
    return [
      {
        tag: "button",
        update: (element, implant, render) => {
          // this.filtersAccordeon.addHandle(element);
          element.onclick = event => {
            event.preventDefault();
            this.filtersAccordeon.toggle();
            render();
          }
        },
        children: [
          {
            class: "picto plus",
            update: element => {
              if (this.filtersAccordeon.isOpen) {
                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M11.6563 0.582052L6.94694 5.29138L2.36489 0.709331L0.710258 2.36396L5.29231 6.94601L0.582979 11.6553L2.36489 13.4373L7.07422 8.72792L11.6563 13.31L13.3109 11.6553L8.72885 7.07329L13.4382 2.36396L11.6563 0.582052Z" fill="black"/></svg>`;
              } else {
                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/></svg>`;
              }
            }
          },
          {
            tag: "span",
            update: element => {
              if (this.filtersAccordeon.isOpen) {
                if (window.innerWidth < 900) {
                  element.innerHTML = Actwall.__("Filters");
                } else {
                  element.innerHTML = Actwall.__("Close filters");
                }
              } else {
                if (window.innerWidth < 900) {
                  element.innerHTML = Actwall.__("Filters");
                } else {
                  element.innerHTML = Actwall.__("Open filters");
                }
              }
            }
          }
        ]

      }
    ];

  }


  static buildSearchInput(id, name) {
    return {
      class: `search-input ${id}`,
      children: [
        {
          class: "picto arrow",
          init: element => {
            element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none"><path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="#C9C8C8"/></svg>`;
          }
        },
        {
          tag: "input",
          init: element => {
            element.type = "text";
          },
          update: element => {
            element.classList.remove("loading");
            element.placeholder = name;
            element.value = this.getParam(id) || "";
            element.oninput = event => {
              this.debounce(async () => {
                this.setParam(id, element.value);
                await this.renderFilters();
                element.classList.add("loading");
                await this.query();
                await this.renderBody();
              }, 300);
            }
          }
        }
      ]
    };
  }

  static buildCheckbox(key, id, name) {
    return {
      tag: "li",
      child: {
        tag: "label",
        children: [
          {
            tag: "input",
            init: element => {
              element.type = "checkbox"
            },
            update: element => {
              element.classList.remove("loading");
              const current = this.getParam(key) || "";
              element.checked = current === id;
              element.onchange = async event => {
                if (element.checked) {
                  this.setParam(key, id);
                } else {
                  this.removeParam(key);
                }
                element.classList.add("loading");
                // await this.query();
                // this.render();
                await this.renderFilters();
                await this.query();
                await this.renderBody();
              }
            }
          },
          {
            tag: "span",
            update: element => {
              element.innerHTML = name;
            }
          }
        ]
      }
    };
  }

  static buildPrice() {
    return {
      class: `filter price`,
      init: (element, implant) => {
        // this.accordeon.add(element, ".handle", ".slider");
        // new Accordeon(element, ".handle", ".slider");
        implant.complete = () => {
          new Accordeon(element, ".handle", ".slider");
          delete implant.complete;
        }
      },
      children: [
        {
          class: "handle",
          children: [
            {
              class: "picto chevron",
              init: element => {
                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none"><path d="M11.3867 0.582052L6.67741 5.29138L2.09536 0.709331L0.440727 2.36396L5.02278 6.94601L6.80469 8.72792L8.45932 7.07329L13.1686 2.36396L11.3867 0.582052Z" fill="black"/></svg>`;
              }
            },
            {
              tag: "span",
              init: element => {
                element.innerHTML = Actwall.__("Price");
              }
            }
          ]
        },
        {
          class: "slider",
          child: {
            class: "price-form",
            update: (element, implant, render) => {
              const max = 20000;
              implant.children = [
                {
                  class: "minmax",
                  children: [
                    {
                      tag: "input",
                      init: element => {
                        element.type = "text";
                        element.placeholder = "Min";
                      },
                      update: element => {
                        element.value = this.getParam("min-price") || "";
                        element.oninput = event => {
                          this.debounce(async () => {
                            this.setParam("min-price", parseInt(element.value));
                            await this.renderFilters();
                            element.classList.add("loading");
                            await this.query();
                            await this.renderBody();
                          }, 300);
                        }
                      }
                    },
                    {
                      tag: "input",
                      init: element => {
                        element.type = "text";
                        element.placeholder = "Max";
                      },
                      update: element => {
                        element.value = this.getParam("max-price") || "";
                        element.oninput = event => {
                          this.debounce(async () => {
                            this.setParam("max-price", parseInt(element.value));
                            await this.renderFilters();
                            element.classList.add("loading");
                            await this.query();
                            await this.renderBody();
                          }, 300);
                        }
                      }
                    }
                  ]
                },
                {
                  class: "slider-input",
                  children: [
                    {
                      class: "slider-input-content",
                      children: [
                        {
                          class: "line bare",
                        },
                        {
                          class: "dot min",
                          init: element => {
                            element.draggable = false;
                          },
                          update: element => {
                            const tracker = new Actwall.Tracker(element);
                            const width = element.parentNode.clientWidth;
                            let minValue = this.getParam("min-price") || 0;
                            let maxValue = this.getParam("max-price") || max;
                            let minX = width*minValue/max;
                            let maxX = width*maxValue/max
                            element.style.transform = `translateX(${minX}px)`;
                            tracker.onupdate = () => {
                              const box = element.parentNode.getBoundingClientRect();
                              // console.log(tracker.clientX - box.left);
                              minX = Math.max(0, Math.min(tracker.clientX - box.left, maxX));
                              // element.style.transform = `translateX(${value}px)`;
                              minValue = parseInt(max*minX/width);
                              this.setParam("min-price", minValue);
                              render();
                            }
                            tracker.oncomplete = async () => {
                              await this.renderFilters();
                              await this.query();
                              await this.renderBody();
                            }
                          }
                        },
                        {
                          class: "dot max",
                          update: element => {
                            const tracker = new Actwall.Tracker(element);
                            const width = element.parentNode.clientWidth;
                            let minValue = this.getParam("min-price") || 0;
                            let maxValue = this.getParam("max-price");
                            if (maxValue === undefined || maxValue === "") {
                              maxValue = max;
                            }
                            let minX = width*minValue/max;
                            let maxX = width*maxValue/max
                            element.style.transform = `translateX(${maxX}px)`;
                            tracker.onupdate = () => {
                              const box = element.parentNode.getBoundingClientRect();
                              maxX = Math.max(minX, Math.min(tracker.clientX - box.left, width));
                              maxValue = parseInt(max*maxX/width);
                              if (maxValue === max) {
                                maxValue = "";
                              }
                              this.setParam("max-price", maxValue);
                              render();
                            }
                            tracker.oncomplete = async () => {
                              await this.renderFilters();
                              await this.query();
                              await this.renderBody();
                            }
                          }
                        }
                      ]
                    }
                  ]
                },
                {
                  class: "slider-values",
                  children: [
                    {
                      class: "slider-value min",
                      update: element => {
                        element.innerHTML = `${this.getParam("min-price") || 0}.-`;
                      }
                    },
                    {
                      class: "slider-value max",
                      update: element => {
                        element.innerHTML = `${(this.getParam("max-price") || max).toLocaleString("de-CH")}.-`;
                      }
                    }
                  ]
                }
              ];
            }
          }
        }
      ]
    };
  }

  static cook() {

    // return [
    //   {
    //     class: "filters",
    //     init: (element, implant, render) => {
    //       this.render = render;
    //
    //
    //     },
    //     children:

        return [
          {
            class: "filters",

            child: {
              class: "filters-content",
              init: element => {
                this.filtersAccordeon.addBody(element.parentNode, element);

                // Actwall.Home.accordeon.add(element.parentNode.parentNode, document.getElementById("button-filters"), element.parentNode);
              },
              update: (root, implant, render) => {
                this.renderFilters = render;


                this.updateImageTypes();

                implant.children = [
                  {
                    class: "active-filters",
                    tag: "ul",

                    update: async (element, implant) => {

                      const filters = await this.getActiveFilters();

                      implant.children = filters.map(filter => {
                        return {
                          tag: "li",
                          update: element => {
                            element.classList.remove("loading");
                            element.onclick = async event => {
                              this.removeParam(filter.key);
                              element.classList.add("loading");
                              // await this.query();
                              // this.render();
                              await this.renderFilters();
                              await this.query();
                              await this.renderBody();
                            }
                          },
                          children: [
                            {
                              class: "close",
                              init: element => {
                                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M8.58185 2.51154L6.00055 5.09284L3.48901 2.5813L2.58206 3.48825L5.0936 5.99979L2.5123 8.58109L3.48901 9.5578L6.07031 6.9765L8.58185 9.48804L9.4888 8.58109L6.97726 6.06955L9.55856 3.48825L8.58185 2.51154Z" fill="black"/></svg>`;
                              }
                            },
                            {
                              tag: "span",
                              update: element => {
                                element.innerHTML = filter.value;
                              }
                            }
                          ]
                        }
                      });
                    },


                    // children: Object.entries(this.store).map(([key, value]) => {
                    //   return {
                    //     tag: "li",
                    //     update: element => {
                    //       element.classList.remove("loading");
                    //       element.onclick = async event => {
                    //         this.removeParam(key);
                    //         element.classList.add("loading");
                    //         await this.query();
                    //         this.render();
                    //       }
                    //     },
                    //     children: [
                    //       {
                    //         class: "close",
                    //         init: element => {
                    //           element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M8.58185 2.51154L6.00055 5.09284L3.48901 2.5813L2.58206 3.48825L5.0936 5.99979L2.5123 8.58109L3.48901 9.5578L6.07031 6.9765L8.58185 9.48804L9.4888 8.58109L6.97726 6.06955L9.55856 3.48825L8.58185 2.51154Z" fill="black"/></svg>`;
                    //         }
                    //       },
                    //       {
                    //         tag: "span",
                    //         update: element => {
                    //           element.innerHTML = value;
                    //         }
                    //       }
                    //     ]
                    //   }
                    // })
                  },
                  ...[
                    {
                      id: "search",
                      name: Actwall.__("Search by keyword, theme, artists")
                    }
                    // ,
                    // {
                    //   id: "location",
                    //   name: Actwall.__("Search by keyword, theme, artists")
                    // }
                  ].map(search => {
                    return this.buildSearchInput(search.id, search.name);
                    // return {
                    //   class: `search-input ${search.id}`,
                    //   children: [
                    //     {
                    //       class: "picto chevron",
                    //       init: element => {
                    //         element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none"><path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="#C9C8C8"/></svg>`;
                    //       }
                    //     },
                    //     {
                    //       tag: "input",
                    //       init: element => {
                    //         element.type = "text";
                    //       },
                    //       update: element => {
                    //         element.classList.remove("loading");
                    //         element.placeholder = search.name;
                    //         element.value = this.getParam(search.id) || "";
                    //         element.oninput = async event => {
                    //           this.setParam(search.id, element.value);
                    //           await this.renderFilters();
                    //           element.classList.add("loading");
                    //           await this.query();
                    //           await this.renderBody();
                    //         }
                    //       }
                    //     }
                    //   ]
                    // };
                  }),
                  this.buildPrice(),

                  // {
                  //   class: `filter price`,
                  //   complete: element => {
                  //     this.accordeon.add(element, ".handle", ".slider");
                  //   },
                  //   children: [
                  //     {
                  //       class: "handle",
                  //       children: [
                  //         {
                  //           class: "picto chevron",
                  //           init: element => {
                  //             element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none"><path d="M11.3867 0.582052L6.67741 5.29138L2.09536 0.709331L0.440727 2.36396L5.02278 6.94601L6.80469 8.72792L8.45932 7.07329L13.1686 2.36396L11.3867 0.582052Z" fill="black"/></svg>`;
                  //           }
                  //         },
                  //         {
                  //           tag: "span",
                  //           init: element => {
                  //             element.innerHTML = Actwall.__("Price");
                  //           }
                  //         }
                  //       ]
                  //     },
                  //     {
                  //       class: "slider",
                  //       child: {
                  //         class: "price-form",
                  //         update: (element, implant, render) => {
                  //           const max = 20000;
                  //           implant.children = [
                  //             {
                  //               class: "minmax",
                  //               children: [
                  //                 {
                  //                   tag: "input",
                  //                   init: element => {
                  //                     element.type = "text";
                  //                     element.placeholder = "Min";
                  //                   },
                  //                   update: element => {
                  //                     element.value = this.getParam("min-price") || "";
                  //                     element.oninput = event => {
                  //                       this.debounce(async () => {
                  //                         this.setParam("min-price", parseInt(element.value));
                  //                         await this.renderFilters();
                  //                         element.classList.add("loading");
                  //                         await this.query();
                  //                         await this.renderBody();
                  //                       }, 300);
                  //                     }
                  //                   }
                  //                 },
                  //                 {
                  //                   tag: "input",
                  //                   init: element => {
                  //                     element.type = "text";
                  //                     element.placeholder = "Max";
                  //                   },
                  //                   update: element => {
                  //                     element.value = this.getParam("max-price") || "";
                  //                     element.oninput = event => {
                  //                       this.debounce(async () => {
                  //                         this.setParam("max-price", parseInt(element.value));
                  //                         await this.renderFilters();
                  //                         element.classList.add("loading");
                  //                         await this.query();
                  //                         await this.renderBody();
                  //                       }, 300);
                  //                     }
                  //                   }
                  //                 }
                  //               ]
                  //             },
                  //             {
                  //               class: "slider-input",
                  //               children: [
                  //                 {
                  //                   class: "slider-input-content",
                  //                   children: [
                  //                     {
                  //                       class: "line bare",
                  //                     },
                  //                     {
                  //                       class: "dot min",
                  //                       init: element => {
                  //                         element.draggable = false;
                  //                       },
                  //                       update: element => {
                  //                         const tracker = new Actwall.Tracker(element);
                  //                         const width = element.parentNode.clientWidth;
                  //                         let minValue = this.getParam("min-price") || 0;
                  //                         let maxValue = this.getParam("max-price") || max;
                  //                         let minX = width*minValue/max;
                  //                         let maxX = width*maxValue/max
                  //                         element.style.transform = `translateX(${minX}px)`;
                  //                         tracker.onupdate = () => {
                  //                           const box = element.parentNode.getBoundingClientRect();
                  //                           // console.log(tracker.clientX - box.left);
                  //                           minX = Math.max(0, Math.min(tracker.clientX - box.left, maxX));
                  //                           // element.style.transform = `translateX(${value}px)`;
                  //                           minValue = parseInt(max*minX/width);
                  //                           this.setParam("min-price", minValue);
                  //                           render();
                  //                         }
                  //                         tracker.oncomplete = async () => {
                  //                           await this.renderFilters();
                  //                           await this.query();
                  //                           await this.renderBody();
                  //                         }
                  //                       }
                  //                     },
                  //                     {
                  //                       class: "dot max",
                  //                       update: element => {
                  //                         const tracker = new Actwall.Tracker(element);
                  //                         const width = element.parentNode.clientWidth;
                  //                         let minValue = this.getParam("min-price") || 0;
                  //                         let maxValue = this.getParam("max-price");
                  //                         if (maxValue === undefined || maxValue === "") {
                  //                           maxValue = max;
                  //                         }
                  //                         let minX = width*minValue/max;
                  //                         let maxX = width*maxValue/max
                  //                         element.style.transform = `translateX(${maxX}px)`;
                  //                         tracker.onupdate = () => {
                  //                           const box = element.parentNode.getBoundingClientRect();
                  //                           maxX = Math.max(minX, Math.min(tracker.clientX - box.left, width));
                  //                           maxValue = parseInt(max*maxX/width);
                  //                           if (maxValue === max) {
                  //                             maxValue = "";
                  //                           }
                  //                           this.setParam("max-price", maxValue);
                  //                           render();
                  //                         }
                  //                         tracker.oncomplete = async () => {
                  //                           await this.renderFilters();
                  //                           await this.query();
                  //                           await this.renderBody();
                  //                         }
                  //                       }
                  //                     }
                  //                   ]
                  //                 }
                  //               ]
                  //             },
                  //             {
                  //               class: "slider-values",
                  //               children: [
                  //                 {
                  //                   class: "slider-value min",
                  //                   update: element => {
                  //                     element.innerHTML = `${this.getParam("min-price") || 0}.-`;
                  //                   }
                  //                 },
                  //                 {
                  //                   class: "slider-value max",
                  //                   update: element => {
                  //                     element.innerHTML = `${(this.getParam("max-price") || max).toLocaleString("de-CH")}.-`;
                  //                   }
                  //                 }
                  //               ]
                  //             }
                  //           ];
                  //         }
                  //       }
                  //     }
                  //   ]
                  // },

                  ...[
                    {
                      id: "orderby",
                      name: Actwall.__("Sort By")
                    },
                    {
                      id: "availability",
                      name: Actwall.__("Availability")
                    },
                    {
                      id: "size",
                      name: Actwall.__("Size")
                    },
                    {
                      id: "color",
                      name: Actwall.__("Colors")
                    }
                  ].map(filter => {
                    return {
                      class: `filter checkboxes ${filter.id}`,
                      init: (element, implant) => {
                        // this.accordeon.add(element, ".handle", ".slider");
                        // new Accordeon(element, ".handle", ".slider");
                        implant.complete = () => {
                          new Accordeon(element, ".handle", ".slider");
                          delete implant.complete;
                        }
                      },
                      children: [
                        {
                          class: "handle",
                          children: [
                            {
                              class: "picto chevron",
                              init: element => {
                                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none"><path d="M11.3867 0.582052L6.67741 5.29138L2.09536 0.709331L0.440727 2.36396L5.02278 6.94601L6.80469 8.72792L8.45932 7.07329L13.1686 2.36396L11.3867 0.582052Z" fill="black"/></svg>`;
                              }
                            },
                            {
                              tag: "span",
                              init: element => {
                                element.innerHTML = filter.name;
                              }
                            }
                          ]
                        },
                        {
                          class: "slider",
                          child: {
                            tag: "ul",
                            children: Actwall.Home.filters[filter.id].map(item => {
                              return this.buildCheckbox(filter.id, item.id, item.name);
                              // return {
                              //   tag: "li",
                              //   child: {
                              //     tag: "label",
                              //     children: [
                              //       {
                              //         tag: "input",
                              //         init: element => {
                              //           element.type = "checkbox"
                              //         },
                              //         update: element => {
                              //           element.classList.remove("loading");
                              //           const current = this.getParam(filter.id) || "";
                              //           element.checked = current === item.id;
                              //           element.onchange = async event => {
                              //             if (element.checked) {
                              //               this.setParam(filter.id, item.id);
                              //             } else {
                              //               this.removeParam(filter.id);
                              //             }
                              //             // this.setParam(filter.id, item.id);
                              //             element.classList.add("loading");
                              //             await this.renderFilters();
                              //             await this.query();
                              //             await this.renderBody();
                              //             // this.render();
                              //           }
                              //         }
                              //       },
                              //       {
                              //         tag: "span",
                              //         update: element => {
                              //           element.innerHTML = item.name;
                              //         }
                              //       }
                              //     ]
                              //   }
                              // };
                            })
                          }
                        }
                      ]
                    };
                  }),
                  ...[
                    {id: "photo-type", name: Actwall.__("Types of Photography")},
                    {id: "print", name: Actwall.__("Types of Print")},
                    {id: "member-category", name: Actwall.__("Member of Category")},
                    {id: "gender", name: Actwall.__("Gender")},
                  ].map(filter => {
                    return {
                      class: `filter taxonomy-filter checkboxes ${filter.id}`,
                      init: (element, implant) => {
                        // this.accordeon.add(element, ".handle", ".slider");
                        // new Accordeon(element, ".handle", ".slider");
                        implant.complete = () => {
                          new Accordeon(element, ".handle", ".slider");
                          delete implant.complete;
                        }
                      },
                      children: [
                        {
                          class: "handle",
                          children: [
                            {
                              class: "picto chevron",
                              init: element => {
                                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none"><path d="M11.3867 0.582052L6.67741 5.29138L2.09536 0.709331L0.440727 2.36396L5.02278 6.94601L6.80469 8.72792L8.45932 7.07329L13.1686 2.36396L11.3867 0.582052Z" fill="black"/></svg>`;
                              }
                            },
                            {
                              tag: "span",
                              init: element => {
                                element.innerHTML = filter.name;
                              }
                            }
                          ]
                        },
                        {
                          class: "slider",
                          child: {
                            tag: "ul",
                            update: async (element, implant) => {
                              const terms = await this.fetch(`taxonomy/${filter.id}`);
                              implant.children = terms.map(item => {
                                return this.buildCheckbox(filter.id, item.id, item.name);
                                // return {
                                //   tag: "li",
                                //   child: {
                                //     tag: "label",
                                //     children: [
                                //       {
                                //         tag: "input",
                                //         init: element => {
                                //           element.type = "checkbox"
                                //         },
                                //         update: element => {
                                //           element.classList.remove("loading");
                                //           const current = this.getParam(filter.id) || "";
                                //           element.checked = current === item.id;
                                //           element.onchange = async event => {
                                //             if (element.checked) {
                                //               this.setParam(filter.id, item.id);
                                //             } else {
                                //               this.removeParam(filter.id);
                                //             }
                                //             element.classList.add("loading");
                                //             // await this.query();
                                //             // this.render();
                                //             await this.renderFilters();
                                //             await this.query();
                                //             await this.renderBody();
                                //           }
                                //         }
                                //       },
                                //       {
                                //         tag: "span",
                                //         update: element => {
                                //           element.innerHTML = item.name;
                                //         }
                                //       }
                                //     ]
                                //   }
                                // };
                                // return {
                                //   tag: "li",
                                //   update: element => {
                                //     const current = this.getParam(taxonomy.id) || "";
                                //     element.classList.toggle("active", current === term.id);
                                //   },
                                //   children: [
                                //     {
                                //       class: "picto"
                                //     },
                                //     {
                                //       tag: "a",
                                //       init: element => {
                                //         element.innerHTML = term.name;
                                //       },
                                //       update: element => {
                                //         element.classList.remove("loading");
                                //         element.onclick = async event => {
                                //           event.preventDefault();
                                //           const current = this.getParam(taxonomy.id) || "";
                                //           if (current !== term.id) {
                                //             this.setParam(taxonomy.id, term.id);
                                //             element.classList.add("loading");
                                //             await this.query();
                                //             this.render();
                                //           }
                                //         }
                                //       }
                                //     }
                                //   ]
                                // };
                              });
                            }
                          }
                        }
                      ]
                    };
                  }),
                  {
                    class: `filter location-search location`,
                    init: (element, implant) => {
                      // this.accordeon.add(element, ".handle", ".slider");
                      implant.complete = () => {
                        new Accordeon(element, ".handle", ".slider");
                        delete implant.complete;
                      }
                    },
                    children: [
                      {
                        class: "handle",
                        children: [
                          {
                            class: "picto chevron",
                            init: element => {
                              element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none"><path d="M11.3867 0.582052L6.67741 5.29138L2.09536 0.709331L0.440727 2.36396L5.02278 6.94601L6.80469 8.72792L8.45932 7.07329L13.1686 2.36396L11.3867 0.582052Z" fill="black"/></svg>`;
                            }
                          },
                          {
                            tag: "span",
                            init: element => {
                              element.innerHTML = Actwall.__("Location in Switzerland");
                            }
                          }
                        ]
                      },
                      {
                        class: "slider",
                        init: element => {
                          // console.log(element, element.parentNode, element.parentNode.parentNode);
                        },
                        child: this.buildSearchInput("location", "Location")
                      }
                    ]
                  }





                ];
              }
            }
          },
          {
            tag: "ul",
            class: "grid",
            update: (element, implant, render) => {
              this.renderBody = render;

              if (this.results) {
                implant.children = this.results.map(item => {
                  return {
                    tag: "li",
                    update: element => {
                      element.style.margin = `margin:${item.margin.top}em ${item.margin.right}em ${item.margin.bottom}em ${item.margin.left}em`;
                    },
                    child: {
                      tag: "figure",
                      children: [
                        {
                          tag: "img",
                          update: element => {
                            element.src = item.src;
                            element.srcset = item.sizes.map(size => `${size.src} ${size.width}w`).join(",");
                            element.sizes = "(min-width: 1024px) 1024px, 100vw";
                            element.style = `
                              width:${item.width}em;
                              height:auto;
                              border-style: solid;
                              border-width:${item.border_width}em;
                              border-image:url(${item.border_img}) ${item.border_slice} stretch;
                              padding:${item.padding.top}em ${item.padding.right}em ${item.padding.bottom}em ${item.padding.left}em;`;
                          }
                        },
                        {
                          tag: "figcaption",
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
                                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18">
                                  <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"></path>
                                </svg>
                                A partir de ${item.price}.-`;
                              }
                            }
                          ]
                        }
                      ]
                    }
                  }
                });
              }
            }
          }
        ];
    //   }
    // ];
  }


}



Actwall.Home.cache = {};
Actwall.Home.store = {};
// Actwall.Home.accordeon = new Accordeon();
Actwall.Home.filtersAccordeon = new AccordeonItem();


Actwall.Home.taxonomies = ["photo-type", "print", "member-category", "gender"];

Actwall.Home.filters = {
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
