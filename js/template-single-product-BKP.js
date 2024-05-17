addEventListener("popstate", (event) => {
  Actwall.SingleProduct.update();
});


Actwall.SingleProduct = class {


  static async update() {

    if (this.current) {

      if (history.state && history.state.productId) {

        this.current.productId = history.state.productId;
        this.current.isOpen = true;

        await this.current.query();

      } else {

        this.current.isOpen = false;

      }

      await this.render();

    }

  }

  static async render() {

    const container = document.getElementById("popup-single-product");

    if (container && this.current) {

      await abduct(container, this.current.build());

    }

  }

  static async open(id) {

    // const location = location.href;
    //
    // this.current.close = () => {
    //   history.pushState({}, null, location);
    // };

    this.current = new Actwall.SingleProduct(id);

    await this.render();

    await this.current.query();

    history.pushState({productId: id}, null, this.current.results.photo.permalink);

    // this.current.close = () => history.back();

  }

  static async close() {

    if (this.current) {

      this.current.isOpen = false;

      await this.render();

    }

  }

  constructor(productId) {

    this.isOpen = true;
    this.productId = productId;
    this.currentEdition = 0;

    // this.fetch();

  }

  close() {

    // Actwall.SingleProduct.close();
    history.back()

  }

  async query() {

    const paramString = `product/${this.productId}`;

    this.results = await this.fetch(paramString);

    await this.render();

  }

  async queryMemberProduct() {

    const paramString = `member_products/${this.results.member_id}?num=10&except_id=${this.results.photo.id}`;

    this.memberProducts = await this.fetch(paramString);

    await this.render();

  }

  async fetch(paramString) {

    if (!Actwall.cache[paramString]) {

      Actwall.cache[paramString] = fetch(`${Actwall.rest_url}actwall/v1/${paramString}`).then(response => response.json());

    }

    return Actwall.cache[paramString];

  }

  async fetchText(paramString) {

    if (!Actwall.cache[paramString]) {

      Actwall.cache[paramString] = fetch(`${Actwall.rest_url}actwall/v1/${paramString}`).then(response => response.text());

    }

    return Actwall.cache[paramString];

  }

  build() {
    return [
      {
        class: "popup-content",
        update: (element, implant, render) => {

          this.render = render;
          element.classList.toggle("hidden", !this.isOpen);

          document.body.classList.toggle("frozen", Boolean(this.isOpen));

          if (this.isOpen) {
            implant.children = [
              {
                class: "loading",
                update: element => element.classList.toggle("hidden", Boolean(this.results))
              },
              {
                tag: "header",
                class: "main-header",
                init: element => {
                  const sticky = new Sticky2(element, element.parentNode);
                },
                child: {
                  class: "header-top",
                  children: [
                    {
                      class: "logo",
                      children: [
                        {
                          tag: "a",
                          class: "content desktop-only",
                          init: element => {
                            element.href = Actwall.home_url;
                            element.innerHTML = "Actwall";
                          }
                        },
                        {
                          tag: "span",
                          class: "content mobile-only",
                          update: element => {
                            // element.href = Actwall.home_url;
                            if (this.results) {
                              element.innerHTML = this.results.member_title;
                            }
                          },
                        }
                      ]
                    },
                    {
                      class: "burger",
                      update: element => {
                        element.onclick = event => {
                          // this.isOpen = false;
                          // this.render();
                          this.close();
                        }
                      },
                      child: {
                        class: "picto",
                        init: element => {
                          element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M11.3858 0.582052L6.67643 5.29138L2.09438 0.709331L0.43975 2.36396L5.0218 6.94601L0.312471 11.6553L2.09438 13.4373L6.80371 8.72792L11.3858 13.31L13.0404 11.6553L8.45834 7.07329L13.1677 2.36396L11.3858 0.582052Z" fill="black"></path>
                          </svg>`;
                        }
                      }
                    }
                  ]
                }
              },
              {
                tag: "header",
                class: "main-header placeholder",
                child: {
                  class: "header-top",
                  children: [
                    {
                      class: "logo",
                      children: [
                        {
                          tag: "a",
                          class: "content desktop-only",
                          init: element => {
                            element.href = Actwall.home_url;
                            element.innerHTML = "Actwall";
                          }
                        },
                        {
                          tag: "span",
                          class: "content mobile-only",
                          update: element => {
                            // element.href = Actwall.home_url;
                            if (this.results) {
                              element.innerHTML = this.results.member_title;
                            }
                          },
                        }
                      ]
                    },
                    {
                      class: "burger",
                      update: element => {
                        element.onclick = event => {
                          event.preventDefault();
                          this.isOpen = false;
                          this.render();
                        }
                      },
                      child: {
                        class: "picto",
                        init: element => {
                          element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M11.3858 0.582052L6.67643 5.29138L2.09438 0.709331L0.43975 2.36396L5.0218 6.94601L0.312471 11.6553L2.09438 13.4373L6.80371 8.72792L11.3858 13.31L13.0404 11.6553L8.45834 7.07329L13.1677 2.36396L11.3858 0.582052Z" fill="black"></path>
                          </svg>`;
                        }
                      }
                    }
                  ]
                }
              },
              {
                tag: "main",
                class: "single product",
                update: (element, implant) => {
                  element.classList.toggle("hidden", !this.results)
                  if (this.results) {
                    implant.children = [
                      this.buildHeader(),
                      ...this.buildBody()
                    ]
                  }
                }
              }
            ];
          }
        }
      }
    ];
  }

  buildHeader() {
    return {
      tag: "header",
      class: "header-bottom",
      init: element => {
        // const header = document.getElementById("header");
        const header = element.parentNode.previousElementSibling; // main header


          // new Sticky2(
          //   element,
          //   header.parentNode,
          //   () => {
          //     if (Actwall.isMobile()) {
          //       return element.firstElementChild.clientHeight; // secondary header without tabs}
          //     } else {
          //       return header.clientHeight;
          //     }
          //   },
          //   () => {
          //     if (Actwall.isMobile()) {
          //       return 0;
          //     } else {
          //       return header.clientHeight;
          //     }
          //   }
          // );


          new Sticky2(
            element,
            header.parentNode,
            () => header.clientHeight,
            () => header.clientHeight
          );

        // new Sticky2(
        //   element,
        //   header.parentNode,
        //   () => header.clientHeight,
        //   () => header.clientHeight
        // );


      },
      children: [
        {
          class: "page-title",
          children: [
            {
              tag: "h1",
              update: element => element.innerHTML = this.results.member_title
            },
            {
              tag: "a", // close button MOBILE ONLY
              class: "close",
              init: element => {
                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                  <path d="M11.3858 0.582052L6.67643 5.29138L2.09438 0.709331L0.43975 2.36396L5.0218 6.94601L0.312471 11.6553L2.09438 13.4373L6.80371 8.72792L11.3858 13.31L13.0404 11.6553L8.45834 7.07329L13.1677 2.36396L11.3858 0.582052Z" fill="black"></path>
                </svg>`;
              }
            }
          ]
        },
        {
          class: "followers"
        },
        {
          tag: "ul",
          children: [

            // {
            //   class: "portfolio"
            // },
            {
              tag: "li",
              class: "desktop-only",
              child: {
                tag: "a",
                update: element => {
                  element.href = this.results.work_to_sale_link;
                },
                child: {
                  tag: "span",
                  init: span => span.innerHTML = Actwall.__("All works")
                }
              }
            },
            {
              tag: "li",
              class: "active mobile-only",
              child: {
                class: "ellipsis",
                update: element => {
                  element.innerHTML = this.results.photo.title;
                }
              }
            },
            // <li class="active"><div class="ellipsis"><?php echo $photo['title']; ?></div></li>
            {
              tag: "li",
              child: {
                tag: "a",
                update: element => {
                  element.href = this.results.member_link;
                },
                child: {
                  tag: "span",
                  init: span => span.innerHTML = Actwall.__("Biography")
                }
              }
            },
            {
              tag: "li",
              class: "follow",
              child: {
                tag: "a",
                init: element => {
                  element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
                  </svg>
                  <span>${Actwall.__("Follow")}</span>`;
                }
              }
            }
          ]
        }
      ]
    };
  }


  buildBody() {
    return [
      {
        tag: "section",
        class: "product-slideshow",
        child: {
          class: "slideshow",
          children: [
            {
              class: "viewer",
              children: [
                {
                  class: "frame",
                  child: this.buildImage(this.results.photo)
                  // child: {
                  //   tag: "figure",
                  //   child: {
                  //     tag: "img",
                  //     update: element => {
                  //       if (this.results.photo && this.results.photo.editions && this.results.photo.editions.length) {
                  //         const photo = this.results.photo;
                  //         const edition = photo.editions[0];
                  //         element.classList.toggle("no-frame", !photo.has_frame);
                  //         element.src = photo.src;
                  //         element.srcset = photo.sizes.map(size => `${size.src} ${size.width}w`).join(",");
                  //         element.sizes = "(min-width: 1024px) 1024px, 100vw";
                  //         if (edition.border_width && edition.border_img && edition.border_slice) {
                  //           element.style = `
                  //             width: ${edition.width}em;
                  //             height: auto;
                  //             border-style: solid;
                  //             border-width: ${edition.border_width.toFixed(4)}em;
                  //             border-image: url(${edition.border_img}) ${edition.border_slice} stretch;
                  //             padding: ${edition.padding_top.toFixed(4)}em ${edition.padding_right.toFixed(4)}em ${edition.padding_bottom.toFixed(4)}em ${edition.padding_left.toFixed(4)}em;
                  //           `;
                  //         } else {
                  //           element.style = `
                  //             width: ${edition.width}em;
                  //             height: auto;
                  //             padding: ${edition.padding_top.toFixed(4)}em ${edition.padding_right.toFixed(4)}em ${edition.padding_bottom.toFixed(4)}em ${edition.padding_left.toFixed(4)}em;
                  //           `;
                  //         }
                  //       }
                  //     }
                  //   }
                  // }
                }
              ]
            }
          ]
        }
      },
      {
        tag: "section",
        class: "photo-details",
        children: [
          {
            class: "single-columns",
            children: [
              {
                class: "mobile-only",
                child: this.buildDetailNavigation()
              },
              {
                class: "single-column",
                children: [
                  {
                    tag: "h1",
                    child: {
                      tag: "span",
                      update: element => element.innerHTML = this.results.photo.title
                    }
                  },
                  {
                    class: "details",
                    child: this.buildProductEdition()
                  }
                ]
              },
              {
                class: "single-column",
                children: [
                  {
                    class: "desktop-only",
                    child: this.buildDetailNavigation()
                  },
                  {
                    class: "about",
                    children: [
                      {
                        tag: "h2",
                        init: element => {
                          element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                            <path d="M7.09471 14.4375L11.804 9.72815L16.3861 14.3102L18.0407 12.6556L13.4587 8.07352L11.6768 6.29161L10.0221 7.94624L5.3128 12.6556L7.09471 14.4375Z" fill="black"/>
                          </svg>
                          <span>${Actwall.__("About the work")}</span>`
                        }
                      },
                      {
                        class: "slider",
                        child: {
                          class: "content",
                          update: element => element.innerHTML = this.results.photo.content
                        }
                      }
                    ]
                  }
                ]
              }
            ]
          }
        ]
      },
      this.buildOtherWork()
      // {
      //   tag: "section",
      //   class: "other-works",
      //   update: (element, implant) => {
      //     if (this.memberProducts) {
      //       if (this.memberProducts.length) {
      //         implant.children = [
      //           {
      //             class: "headline",
      //             child: {
      //               tag: "h3",
      //               init: element => element.innerHTML = Actwall.__("Other works")
      //             }
      //           },
      //           {
      //             tag: "ul",
      //             class: "member-photos",
      //             // update: (element, implant) => {
      //             //   if (this.memberProducts) {
      //             //     implant.children = this.memberProducts.map(photo => {
      //             //       return {
      //             //         tag: "li",
      //             //         child: {
      //             //           tag: "a",
      //             //           update: element => {
      //             //             element.href = photo.permalink;
      //             //             element.onclick = event => {
      //             //               event.preventDefault();
      //             //               Actwall.SingleProduct.open(photo.id)
      //             //             }
      //             //           },
      //             //           child: this.buildImage(photo)
      //             //         }
      //             //       };
      //             //     })
      //             //   } else {
      //             //     this.queryMemberProduct();
      //             //   }
      //             // }
      //             children: this.memberProducts.map(photo => {
      //               return {
      //                 tag: "li",
      //                 child: {
      //                   tag: "a",
      //                   update: element => {
      //                     element.href = photo.permalink;
      //                     element.onclick = event => {
      //                       event.preventDefault();
      //                       Actwall.SingleProduct.open(photo.id)
      //                     }
      //                   },
      //                   child: this.buildImage(photo)
      //                 }
      //               };
      //             })
      //           },
      //
      //           {
      //             class: "more",
      //             child: {
      //               tag: "a",
      //               update: element => {
      //                 element.href = this.results.work_to_sale_link;
      //               },
      //               init: element => {
      //                 element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
      //                   <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
      //                 </svg>
      //                 <span>${Actwall.__("See more")}</span>`;
      //               }
      //             }
      //           }
      //         ];
      //       } else {
      //         implant.children = [];
      //       }
      //     } else {
      //       implant.children = [];
      //       this.queryMemberProduct();
      //     }
      //   }
      // }
    ];
  }

  buildOtherWork() {
    return {
      tag: "section",
      class: "other-works",
      update: (element, implant) => {
        if (this.memberProducts) {
          if (this.memberProducts.length) {
            implant.children = [
              {
                class: "headline",
                child: {
                  tag: "h3",
                  init: element => element.innerHTML = Actwall.__("Other works")
                }
              },
              this.buildMemberProducts(this.memberProducts),
              // {
              //   tag: "ul",
              //   class: "member-photos",
              //   children: this.memberProducts.map(photo => {
              //     return {
              //       tag: "li",
              //       child: {
              //         tag: "a",
              //         update: element => {
              //           element.href = photo.permalink;
              //           element.onclick = event => {
              //             event.preventDefault();
              //             Actwall.SingleProduct.open(photo.id)
              //           }
              //         },
              //         child: this.buildImage(photo)
              //       }
              //     };
              //   })
              // },
              {
                class: "more",
                child: {
                  tag: "a",
                  update: element => {
                    element.href = this.results.work_to_sale_link;
                  },
                  init: element => {
                    element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                      <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
                    </svg>
                    <span>${Actwall.__("See more")}</span>`;
                  }
                }
              }
            ];
          } else {
            implant.children = [];
          }
        } else {
          implant.children = [];
          this.queryMemberProduct();
        }
      }
    };
  }

  buildMemberProducts(photos) {
    return {
      tag: "ul",
      class: "member-photos",
      children: photos.map(photo => {
        return {
          tag: "li",
          child: {
            tag: "a",
            update: element => {
              element.href = photo.permalink;
              element.onclick = event => {
                event.preventDefault();
                Actwall.SingleProduct.open(photo.id)
              }
            },
            child: this.buildImage(photo)
          }
        };
      })
    };
  }

  buildImage(photo) {
    // return {
    //   tag: "figure",
    //   child: {
    //     tag: "img",
    //     update: element => {
    //       if (photo && photo.editions && photo.editions.length) {
    //         const edition = photo.editions[0];
    //         element.classList.toggle("no-frame", !photo.has_frame);
    //         element.src = photo.src;
    //         element.srcset = photo.sizes.map(size => `${size.src} ${size.width}w`).join(",");
    //         element.sizes = "(min-width: 1024px) 1024px, 100vw";
    //         if (edition.border_width && edition.border_img && edition.border_slice) {
    //           element.style = `
    //             width: ${edition.width}em;
    //             height: auto;
    //             border-style: solid;
    //             border-width: ${edition.border_width.toFixed(4)}em;
    //             border-image: url(${edition.border_img}) ${edition.border_slice} stretch;
    //             padding: ${edition.padding_top.toFixed(4)}em ${edition.padding_right.toFixed(4)}em ${edition.padding_bottom.toFixed(4)}em ${edition.padding_left.toFixed(4)}em;
    //           `;
    //         } else {
    //           element.style = `
    //             width: ${edition.width}em;
    //             height: auto;
    //             padding: ${edition.padding_top.toFixed(4)}em ${edition.padding_right.toFixed(4)}em ${edition.padding_bottom.toFixed(4)}em ${edition.padding_left.toFixed(4)}em;
    //           `;
    //         }
    //       }
    //     }
    //   }
    // };

    const item = photo;

    if (!photo.editions) return {};

    const edition = photo.editions[0];

    return {
      tag: "figure",
      children: [
        {
          tag: "img",
          update: element => {



            element.src = item.src;
            element.srcset = item.sizes.map(size => `${size.src} ${size.width}w`).join(",");
            element.sizes = "(min-width: 1024px) 1024px, 100vw";
            if (edition.has_frame && edition.border_width && edition.border_img && edition.border_slice) {
              element.classList.remove("no-frame");
              element.classList.add("has-frame");
              element.style = `
                width:${edition.width}em;
                border-width: 0;
                height:auto;
                border-style: solid;
                border-width:${edition.border_width}em;
                border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
                padding:${edition.padding_top}em ${edition.padding_right}em ${edition.padding_bottom}em ${edition.padding_left}em;`;
            } else {
              element.classList.add("no-frame");
              element.classList.remove("has-frame");
              element.style = `
                width:${edition.width}em;
                height:auto;

                padding:${edition.padding_top}em ${edition.padding_right}em ${edition.padding_bottom}em ${edition.padding_left}em;`;
            }
          }
        },
        {
          class: "border",
          update: element => {
            if (edition.has_frame && edition.border_width && edition.border_img && edition.border_slice) {
              element.classList.remove("hidden");
              element.style = `
                border-style: solid;
                border-width:${edition.border_width}em;
                border-image:url(${edition.border_img}) ${edition.border_slice} stretch;`;
            } else {
              element.classList.add("hidden");
            }
          }
        }

      ]
    };
  }

  buildDetailNavigation() {
    return {
      tag: "ul",
      class: "detail-navigation",
      children: [
        {
          tag: "li",
          class: "save",
          child: {
            tag: "a",
            init: element => {
              element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                <path d="M17.0098 6.59L9.71977 13.99L9.71977 -3.18656e-07L7.28977 -4.24875e-07L7.28976 13.99L-0.000234892 6.59L-0.000235041 9.99L8.49976 18.49L17.0098 9.99L17.0098 6.59Z" fill="black"/>
              </svg>
              <span>${Actwall.__("Save")}</span>`;
            }
          }
        },
        {
          tag: "li",
          class: "view-room",
          child: {
            tag: "a",
            children: [
              {
                class: "picto-square"
              },
              {
                tag: "span",
                init: element => element.innerHTML = Actwall.__("view in room")
              }
            ]
          }
        },
        {
          tag: "li",
          class: "share",
          child: {
            tag: "a",
            init: element => {
              element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                <path d="M17.0098 6.59L9.71977 13.99L9.71977 -3.18656e-07L7.28977 -4.24875e-07L7.28976 13.99L-0.000234892 6.59L-0.000235041 9.99L8.49976 18.49L17.0098 9.99L17.0098 6.59Z" fill="black"/>
              </svg>
              <span>${Actwall.__("Share")}</span>`;
            }
          }
        }
      ]
    }

  }

  buildProductEdition() {
    const photo = this.results.photo;
    const current = this.currentEdition;
    return {
      class: "edition-instance",
      update: (element, implant, render) => {
        const currentEdition = photo.editions[this.currentEdition];
        implant.children = [
          {
            class: "editions-switch",
            children: [

              {
                class: "price",
                child: {
                  tag: "span",
                  update: element => element.innerHTML = `${currentEdition.prix.toLocaleString("de-CH").replace("’", "'") || ""}.-`
                }
              },
              {
                class: "edition-size",
                children: [
                  {
                    class: "edition",
                    update: element => {
                      if (currentEdition.nb_vendu > 1) {
                        element.innerHTML = `Edition de ${currentEdition.nb_edition} • ${currentEdition.nb_vendu} vendues`;
                      } else if (currentEdition.nb_vendu === 1) {
                        element.innerHTML = `Edition de ${currentEdition.nb_edition} • ${currentEdition.nb_vendu} vendue`;
                      } else {
                        element.innerHTML = `Edition de ${currentEdition.nb_edition}`;
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
                            this.currentEdition = parseInt(element.value);
                            render();
                          }
                        },
                        children: photo.editions.map((edition, index) => {
                          return {
                            tag: "option",
                            update: element => {
                              element.innerHTML = `Size ${edition.paper_width} x ${edition.paper_height}CM (${Math.floor(parseInt(edition.paper_width)*0.393701)} x ${Math.floor(parseInt(edition.paper_height)*0.393701)} in)`;
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
                // update: element => {
                //   element.classList.toggle("hidden", !photo.specifications[current]);
                // },
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
                    update: async (element, implant) => {
                      let specifications;

                      if (currentEdition.specifications) {
                        specifications = currentEdition.specifications.split("\n");
                      } else {
                        specifications = await this.getSpecifications(currentEdition);
                      }

                      implant.child = {
                        tag: "ul",
                        children: specifications.map(item => {
                          return {
                            tag: "li",
                            update: element => element.innerHTML = item
                          }
                        })
                      };
                    }
                    // child: {
                    //   update: async element => {
                    //
                    //     // element.innerHTML = photo.specifications[current] || "";
                    //   }
                    // }
                  }
                ]
              },
              {
                tag: "li",
                class: "info-handle",
                // update: element => {
                //   element.classList.toggle("hidden", !photo.framing[current]);
                // },
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
                    update: async (element, implant) => {
                      let framings;

                      if (currentEdition.framing) {
                        framings = currentEdition.framing.split("\n");
                      } else {
                        framings = await this.getFraming(currentEdition)
                      }

                      implant.child = {
                        tag: "ul",
                        children: framings.map(item => {
                          return {
                            tag: "li",
                            update: element => element.innerHTML = item
                          }
                        })
                      };
                    }
                  }
                ]
              },
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.history);
                },
                children: [
                  {
                    class: "info-handle",
                    init: element => {
                      element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                        <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                      </svg>
                      <span>${Actwall.__("Exhibitions history")}</span>`;
                    }
                  },
                  {
                    class: "info-body",
                    child: {
                      update: element => {
                        element.innerHTML = photo.history || "";
                      }
                    }
                  }
                ]
              },
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.shipping);
                },
                children: [
                  {
                    class: "info-handle",
                    init: element => {
                      element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                        <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                      </svg>
                      <span>${Actwall.__("Shipping")}</span>`;
                    }
                  },
                  {
                    class: "info-body",
                    child: {
                      update: element => {
                        element.innerHTML = photo.shipping || "";
                      }
                    }
                  }
                ]
              }
            ]
          }
        ];
      }
    };
  }

  async getSpecifications(currentEdition) {

    const items = [];

    if (currentEdition && currentEdition.format) {

      if (parseInt(currentEdition.format.tirage_id)) {

        const tirage = await this.fetch(`tirage/${currentEdition.format.tirage_id}`);

        if (tirage) {

          items.push(tirage.name);

        }

      }

      if (parseInt(currentEdition.format.papier_id)) {

        const papier = await this.fetch(`papier/${currentEdition.format.papier_id}`);

        if (papier) {

          items.push(papier.name);

        }

      }


      const imageWidth = parseInt(currentEdition.image_width || 0);
      const imageHeight = parseInt(currentEdition.image_height || 0);

      items.push(`Dimension de l’image ${imageWidth}x${imageHeight} cm`)
      items.push(`Dimension du papier ${Math.max(currentEdition.paper_width || 0, imageWidth)}x${Math.max(currentEdition.paper_height || 0, imageHeight)} cm`)

      if (parseInt(currentEdition.format.type_id)) {

        const type = await this.fetch(`cadre_type/${currentEdition.format.type_id}`);

        if (type) {

          items.push(type.name);

        }

      }

    }

    return items;
  }

  async getFraming(currentEdition) {

    const framingItems = [];

    if (currentEdition && currentEdition.format) {

      if (parseInt(currentEdition.format.collage_id)) {

        const collage = await this.fetch(`collage/${currentEdition.format.collage_id}`);

        if (collage) {

          framingItems.push(collage.name);

        }

      }

      if (parseInt(currentEdition.format.cadre_id)) {

        const cadre = await this.fetch(`cadre/${currentEdition.format.cadre_id}`);

        if (cadre) {

          framingItems.push(cadre.name);

        }

      }

      if (parseInt(currentEdition.format.verre_id)) {

        const verre = await this.fetch(`verre/${currentEdition.format.verre_id}`);

        if (verre) {

          framingItems.push(verre.name);

        }

      }

    }

    return framingItems;
  }

}
