addEventListener("popstate", (event) => {
  Actwall.SingleProduct.update();
});


Actwall.SingleProduct = class {


  static async update() {


    if (this.current) {

      if (history.state) {

        this.current.productId = history.state.productId;
        this.current.page = history.state.page || "product";

        this.current.currentEdition = history.state.formatIndex || 0;
        this.current.viewInRoom = history.state.viewInRoom || false;
        this.current.isOpen = true;

        // this.current.results = null;

        // await this.current.query();

      } else {

        this.current.isOpen = false;

      }

      await this.current.render();

    }

  }

  // static async render() {
  //
  //   const container = document.getElementById("popup-single-product");
  //
  //   if (container && this.current) {
  //
  //     await abduct(container, this.current.build());
  //
  //     await abduct(document.getElementById("popup-viewinroom"), this.current.buildViewInRoom());
  //
  //   }
  //
  // }

  static async open(id, permalink, formatIndex) {

    // const location = location.href;
    //
    // this.current.close = () => {
    //   history.pushState({}, null, location);
    // };

    this.current = new Actwall.SingleProduct(id, formatIndex);

    // await this.current.render();
    //
    // await this.current.query();

    history.pushState({productId: id, formatIndex: formatIndex}, null, permalink);

    // this.current.close = () => history.back();


    await this.current.render();

  }

  static async close() {

    if (this.current) {

      this.current.isOpen = false;

      await this.current.render();

    }

  }

  constructor(productId, formatIndex = 0) {

    this.isOpen = true;
    this.page = "product";
    this.productId = productId;
    this.currentEdition = formatIndex;

    // this.fetch();

  }

  async abduct() {

    // if (!this.results) {
    //
    //   // await this.query();
    //
    //   // const paramString = `product/${this.productId}`;
    //   //
    //   // this.results = await this.fetch(paramString);
    //
    // }

    if (this.nopopup) {

      await abduct(document.getElementById("single-product-container"), [...this.buildHeader(), ...this.buildBody()]);

    } else {

      await abduct(document.getElementById("popup-single-product"), this.build());

    }

    await abduct(document.getElementById("popup-viewinroom"), this.buildViewInRoom());

  }

  async render() {

    // if (this.rendering) {
    //
    //   await this.rendering;
    //
    // }
    //
    // this.rendering = this.abduct();


    document.body.classList.toggle("product-popup-open", Boolean(this.isOpen && !this.nopopup));


    this.rendering = Promise.resolve(this.rendering).then(() => this.abduct());



    // if (this.rendering) {
    //
    //   this.rendering = true;
    //
    //   if (this.nopopup) {
    //
    //     await abduct(document.getElementById("single-product-container"), [...this.buildHeader(), ...this.buildBody()]);
    //
    //   } else {
    //
    //     await abduct(document.getElementById("popup-single-product"), this.build());
    //
    //   }
    //
    //
    //
    //   await abduct(document.getElementById("popup-viewinroom"), this.buildViewInRoom());
    //
    //   // this.rendering = false;
    //
    //
    // }

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

  async queryBio() {

    const paramString = `bio/${this.results.member_id}`;

    this.bio = await this.fetch(paramString);

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

          element.classList.toggle("hidden", !this.isOpen);

          document.body.classList.toggle("frozen", Boolean(this.isOpen));

          element.clientHeight;

          element.classList.add("ready");

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
                  // element.appendChild(document.getElementById("slider"));
                  element.appendChild(document.getElementById("header-top").cloneNode(true));
                }
                // children: [
                //   {},
                //   {
                //     class: "header-top",
                //     children: [
                //       {
                //         class: "artist-name",
                //         children: [
                //           {
                //             tag: "a",
                //             class: "content",
                //             init: element => {
                //               element.href = Actwall.home_url;
                //               element.innerHTML = "Actwall";
                //             }
                //           },
                //           // {
                //           //   tag: "a",
                //           //   // class: "content",
                //           //   update: element => {
                //           //     if (this.results) {
                //           //       element.href = this.results.member_link;
                //           //       element.innerHTML = this.results.member_title;
                //           //     }
                //           //   }
                //           // }
                //           // {
                //           //   tag: "span",
                //           //   class: "content mobile-only",
                //           //   update: element => {
                //           //     // element.href = Actwall.home_url;
                //           //     if (this.results) {
                //           //       element.innerHTML = this.results.member_title;
                //           //     }
                //           //   },
                //           // }
                //         ]
                //       },
                //       {
                //         class: "burger",
                //         update: element => {
                //           // element.onclick = event => {
                //           //   // this.isOpen = false;
                //           //   // this.render();
                //           //   this.close();
                //           // }
                //           element.onclick = event => {
                //             event.preventDefault();
                //             document.body.classList.toggle('menu-open');
                //           };
                //         },
                //         child: {
                //           class: "picto",
                //           init: element => {
                //             // element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                //             //   <path d="M11.3858 0.582052L6.67643 5.29138L2.09438 0.709331L0.43975 2.36396L5.0218 6.94601L0.312471 11.6553L2.09438 13.4373L6.80371 8.72792L11.3858 13.31L13.0404 11.6553L8.45834 7.07329L13.1677 2.36396L11.3858 0.582052Z" fill="black"></path>
                //             // </svg>`;
                //
                //             element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                //               <rect width="25" height="4" fill="black"/>
                //               <rect y="10" width="25" height="4" fill="black"/>
                //               <rect y="20" width="25" height="4" fill="black"/>
                //             </svg>`;
                //
                //
                //           }
                //         }
                //       }
                //     ]
                //   }
                // ]
              },
              {
                tag: "header",
                class: "main-header placeholder",
                child: {
                  class: "header-top",
                  children: [
                    {
                      class: "artist-name",
                      children: [
                        {
                          tag: "a",
                          class: "content desktop-only",
                          init: element => {
                            element.href = Actwall.home_url;
                            element.innerHTML = "Actwall";
                          }
                        }
                        // {
                        //   tag: "a",
                        //   // class: "content",
                        //   update: element => {
                        //     if (this.results) {
                        //       element.href = this.results.member_link;
                        //       element.innerHTML = this.results.member_title;
                        //     }
                        //   }
                        // }
                        // {
                        //   tag: "span",
                        //   class: "content mobile-only",
                        //   update: element => {
                        //     // element.href = Actwall.home_url;
                        //     if (this.results) {
                        //       element.innerHTML = this.results.member_title;
                        //     }
                        //   },
                        // }
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
                  element.classList.toggle("hidden", !this.results);
                  if (this.results && this.results.photo.id === this.productId) {
                    implant.children = [
                      ...this.buildHeader(),
                      ...this.buildBody()
                    ];
                  } else {
                    implant.children = [];
                    this.query();
                  }
                }
              },
              {
                class: "contact-footer",
                init: element => {
                  const button = document.getElementById("contact-button").cloneNode(true);
                  element.appendChild(button);
                  const handle = button.querySelector(".handle");
                  const body = button.querySelector(".contact-nav-content");
                  const close = button.querySelector(".close");
                  new Accordeon(button, handle, body, close, false);
                }
              },
              {
                // tag: "footer",
                class: "footer",
                update: element => {
                  element.classList.toggle("hidden", !this.results);
                },
                init: element => {
                  // element.innerHTML = document.getElementById("main-footer").innerHTML;
                  element.appendChild(document.getElementById("main-footer").cloneNode(1));
                }
              }
            ];
          }
        }
      }
    ];
  }

  buildHeader() {

    // if (!this.results) {
    //   this.query();
    //   return [];
    // }

    return [
      {
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
                update: element => {
                  if (this.results) {
                    element.innerHTML = this.results.member_title;
                  }
                }
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
                update: element => {
                  element.classList.toggle("active", this.page === "product");
                },
                child: {
                  tag: "a",
                  class: "ellipsis",
                  update: element => {
                    // element.innerHTML = this.results.photo.title;
                    if (this.results) {
                      element.href = this.results.photo.permalink;
                    }
                    element.onclick = event => {
                      event.preventDefault();
                      this.page = "product";
                      history.pushState({...history.state, page: "product"}, null, this.results.photo.permalink);
                      this.render();
                    }
                  },
                  child: {
                    tag: "span",
                    update: element => {
                      element.innerHTML = this.results.photo.title;
                    }
                  }
                }
              },
              {
                tag: "li",
                update: element => {
                  element.classList.toggle("active", this.page === "worktosale");
                },
                child: {
                  tag: "a",
                  update: element => {
                    element.href = this.results.work_to_sale_link;
                    element.onclick = event => {
                      event.preventDefault();
                      this.page = "worktosale";
                      history.pushState({...history.state, page: "worktosale"}, null, this.results.work_to_sale_link);
                      this.render();
                    }
                  },
                  child: {
                    tag: "span",
                    init: span => span.innerHTML = Actwall.__("All works")
                  }
                }
              },

              // <li class="active"><div class="ellipsis"><?php echo $photo['title']; ?></div></li>
              {
                tag: "li",
                update: element => {
                  element.classList.toggle("active", this.page === "bio");
                },
                child: {
                  tag: "a",
                  update: element => {
                    element.href = this.results.member_link;
                    element.onclick = event => {
                      event.preventDefault();
                      this.page = "bio";
                      history.pushState({...history.state, page: "bio"}, null, this.results.member_link);
                      this.render();
                    }
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
      },
      {
        tag: "header",
        class: "header-bottom placeholder",
        children: [
          {
            class: "page-title",
            children: [
              {
                tag: "h1",
                update: element => element.innerHTML = this.results.member_title
              }
            ]
          },
          {
            class: "followers"
          },
          {
            tag: "ul",
            children: [
              {
                tag: "li",
                child: {
                  tag: "a",
                  class: "ellipsis",
                  child: {
                    tag: "span",
                    update: element => {
                      element.innerHTML = "";
                    }
                  }
                }
              },
              {
                tag: "li",
                child: {
                  tag: "a",
                  child: {
                    tag: "span",
                    init: span => span.innerHTML = Actwall.__("All works")
                  }
                }
              },
              {
                tag: "li",
                child: {
                  tag: "a",
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

              // {
              //   tag: "li",
              //   class: "desktop-only",
              //   child: {
              //     tag: "a",
              //     update: element => {
              //       element.href = this.results.work_to_sale_link;
              //     },
              //     child: {
              //       tag: "span",
              //       init: span => span.innerHTML = Actwall.__("All works")
              //     }
              //   }
              // },
              // {
              //   tag: "li",
              //   class: "active mobile-only",
              //   child: {
              //     class: "ellipsis",
              //     update: element => {
              //       element.innerHTML = this.results.photo.title;
              //     }
              //   }
              // },
              // {
              //   tag: "li",
              //   child: {
              //     tag: "a",
              //     update: element => {
              //       element.href = this.results.member_link;
              //     },
              //     child: {
              //       tag: "span",
              //       init: span => span.innerHTML = Actwall.__("Biography")
              //     }
              //   }
              // },
              // {
              //   tag: "li",
              //   class: "follow",
              //   child: {
              //     tag: "a",
              //     init: element => {
              //       element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
              //         <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
              //       </svg>
              //       <span>${Actwall.__("Follow")}</span>`;
              //     }
              //   }
              // }
            ]
          }
        ]
      }
    ];
  }


  buildBody() {

    // if (!this.results) {
    //   this.query();
    //   return [];
    // }

    return [
      {
        class: "product-body",
        update: (element, implant) => {
          element.classList.toggle("hidden", this.page !== "product");
          if (this.page === "product") {
            implant.children = this.buildProductPage();
          }
        }
      },
      {
        class: "worktosale-body",
        update: (element, implant) => {
          element.classList.toggle("hidden", this.page !== "worktosale");
          if (this.page === "worktosale") {
            if (!this.worktosale || !this.worktosale.results) {
              this.worktosale = new Actwall.Worktosale();
              this.worktosale.store = {member_id: this.results.member_id};
              this.worktosale.query().then(() => this.render());
            } else {
              implant.children = this.worktosale.buildContent();
            }
          }
        }
      },
      {
        class: "bio-body",
        update: (element, implant) => {
          element.classList.toggle("hidden", this.page !== "bio");
          if (this.page === "bio") {
            if (!this.bio) {
              this.queryBio();
            } else {
              implant.children = this.buildBioPage();
            }
          }
        }
      }
    ];
  }


  buildBioPage() {
    return [
      {
        class: "member-portrait",
        child: {
          class: "single-columns",
          children: [
            {
              class:"single-column",
              child: {
                class:"portrait",
                child: {
                  tag: "figure",
                  children: [
                    {
                      tag: "img",
                      update: element => {
                        if (this.bio.portrait) {
                          element.src = this.bio.portrait.src;
                          element.srcset = this.bio.portrait.sizes.map(size => `${size.src} ${size.width}w`).join(",");
                          element.sizes = "(min-width: 900px) 50vw, 100vw";
                        }
                      }
                    },
                    {
                      tag: "small",
                      child: {
                        tag: "span",
                        update: element => {
                          element.innerHTML = this.bio.portrait_caption;
                        }
                      }
                    }
                  ]
                }
              }
            },
            {
              class: "single-column text-column",
              children: [
                {
                  tag: "section",
                  class: "bio-section",
                  children: [
                    {
                      class: "section-body",
                      child: {
                        class: "section-content",
                        update: element => {
                          element.innerHTML = this.bio.content;
                        }
                      }
                    },
                    {
                      class: "section-footer",
                      update: element => {
                        element.classList.toggle("hidden", !this.bio.representation);
                      },
                      child: {
                        tag: "span",
                        update: element => {
                          element.innerHTML = `${Actwall.__("Represented by")} ${this.bio.representation}`;
                        }
                      }
                    }
                  ]
                },
                {
                  class: "details accordeons",
                  update: (element, implant, render) => {
                    implant.children = [
                      {
                        title: Actwall.__("Contact"),
                        visible: true,
                        content: [
                          {
                            tag: "ul",
                            children: [
                              {
                                tag: "li",
                                update: element => {
                                  element.classList.toggle("hidden", !this.bio.website);
                                },
                                child: {
                                  tag: "a",
                                  update: element => {
                                    element.href = this.bio.website;
                                    element.innerHTML = this.bio.website_name;
                                    element.target = "_blank";
                                  }
                                }
                              },
                              {
                                tag: "li",
                                update: element => {
                                  element.classList.toggle("hidden", !this.bio.email);
                                },
                                child: {
                                  tag: "a",
                                  update: element => {
                                    element.href = `mailto:${this.bio.email}`;
                                    element.innerHTML = Actwall.__("Email");
                                  }
                                }
                              },
                              {
                                tag: "li",
                                update: element => {
                                  element.classList.toggle("hidden", !this.bio.instagram);
                                },
                                child: {
                                  tag: "a",
                                  update: element => {
                                    element.href = this.bio.instagram;
                                    element.innerHTML = Actwall.__("Instagram");
                                  }
                                }
                              }
                            ]
                          }
                        ]
                      },
                      {
                        title: Actwall.__("Awards"),
                        visible: Boolean(this.bio.awards && this.bio.awards.length),
                        content: [
                          {
                            tag: "table",
                            children: this.bio.awards.map(award => {
                              return {
                                tag: "tr",
                                children: [
                                  {
                                    tag: "td",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = award.name;
                                      }
                                    }
                                  },
                                  {
                                    tag: "td",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = award.description;
                                      }
                                    }
                                  }
                                ]
                              };
                            })
                          }
                        ]
                      },
                      {
                        title: Actwall.__("Publications"),
                        visible: Boolean(this.bio.publications && this.bio.publications.length),
                        content: [
                          {
                            tag: "table",
                            children: this.bio.publications.map(publication => {
                              return {
                                tag: "tr",
                                children: [
                                  {
                                    tag: "td",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = publication.name;
                                      }
                                    }
                                  },
                                  {
                                    tag: "td",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = publication.description;
                                      }
                                    }
                                  }
                                ]
                              };
                            })
                          }
                        ]
                      },
                      {
                        title: Actwall.__("Exhibitions"),
                        visible: Boolean(this.bio.exhibitions && this.bio.exhibitions.length),
                        content: [
                          {
                            tag: "table",
                            children: this.bio.exhibitions.map(exhibition => {
                              return {
                                tag: "tr",
                                children: [
                                  {
                                    tag: "td",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = exhibition.name;
                                      }
                                    }
                                  },
                                  {
                                    tag: "td",
                                    child: {
                                      tag: "span",
                                      update: element => {
                                        element.innerHTML = exhibition.description;
                                      }
                                    }
                                  }
                                ]
                              };
                            })
                          }
                        ]
                      }
                    ].map((tab, tabIndex) => {
                      return {
                        tag: "section",
                        update: element => {
                          // console.log(element, tab.visible, this.bio);
                          element.classList.toggle("hidden", !tab.visible);
                          element.classList.toggle("open", this.currentBioTab === tabIndex);
                        },
                        children: [
                          {
                            class: "section-header",
                            update: element => {
                              element.onclick = event => {
                                if (this.currentBioTab === tabIndex) {
                                  this.currentBioTab = null;
                                } else {
                                  this.currentBioTab = tabIndex;
                                }
                                render();
                              };
                            },
                            children: [
                              {
                                class: "picto",
                                init: element => {
                                  element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                                    <path d="M16.3858 7.58205L11.6764 12.2914L7.09438 7.70933L5.43975 9.36396L10.0218 13.946L11.8037 15.7279L13.4583 14.0733L18.1677 9.36396L16.3858 7.58205Z" fill="black"/>
                                  </svg>`;
                                }
                              },
                              {
                                class: "section-title",
                                child: {
                                  tag: "h3",
                                  update: element => {
                                    element.innerHTML = tab.title;
                                  }
                                }
                              }
                            ]
                          },
                          {
                            class: "section-body",
                            init: element => {

                            },
                            update: element => {
                              element.ontransitionend = event => {
                                if (this.currentBioTab === tabIndex) {
                                  element.style.height = "auto";
                                }
                              }
                              if (element.style.height === "auto") {
                                element.style.height = `${element.firstElementChild.clientHeight}px`;
                              }
                              element.clientHeight;
                              if (this.currentBioTab === tabIndex) {
                                element.style.height = `${element.firstElementChild.clientHeight}px`;
                              } else {
                                element.style.height = '0';
                              }
                            },
                            child: {
                              class: "section-content",
                              children: tab.content
                            }
                          }
                        ]
                      };
                    });
                  }
                }
              ]
            }
          ]
        }
      }
    ];
  }

  buildProductPage() {

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
                  // update: element => {
                  //   element.onclick = async event => {
                  //     this.viewInRoom = true;
                  //     await this.render();
                  //     history.pushState({productId: this.productId, formatIndex: this.currentEdition, viewInRoom: true}, null, this.results.photo.permalink);
                  //   }
                  // },
                  // children: [
                  //   this.buildDesktopImage(this.results.photo, this.currentEdition)
                  // ]
                  child: {
                    tag: "a",
                    update: element => {

                      element.onclick = async event => {
                        event.preventDefault();
                        this.viewInRoom = true;
                        await this.render();
                        history.pushState({productId: this.productId, formatIndex: this.currentEdition, viewInRoom: true}, null, this.results.photo.permalink);
                      }
                    },
                    child: this.buildDesktopImage(this.results.photo, this.currentEdition)
                  }

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
                    children: [
                      {
                        tag: "span",
                        update: element => {
                          // console.log(this.results);
                          element.innerHTML = this.results.photo.title;
                        }
                      },
                      {
                        tag: "span",
                        update: element => element.innerHTML = this.results.photo.nice_date || this.results.photo.date || ""
                      }
                    ]
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
                      // {
                      //   tag: "h2",
                      //   class: "mobile-only",
                      //   init: element => {
                      //     element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                      //       <path d="M7.09471 14.4375L11.804 9.72815L16.3861 14.3102L18.0407 12.6556L13.4587 8.07352L11.6768 6.29161L10.0221 7.94624L5.3128 12.6556L7.09471 14.4375Z" fill="black"/>
                      //     </svg>
                      //     <span class="mobile-only">${Actwall.__("About the work")}</span>`
                      //   }
                      // },
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
                  init: element => element.innerHTML = Actwall.__("All works")
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
            // child: this.buildImage(photo)
            child: this.buildDesktopImage(photo, 0)
          }
        };
      })
    };
  }

  // buildImage(photo) { // still used for other works
  //   // return {
  //   //   tag: "figure",
  //   //   child: {
  //   //     tag: "img",
  //   //     update: element => {
  //   //       if (photo && photo.editions && photo.editions.length) {
  //   //         const edition = photo.editions[0];
  //   //         element.classList.toggle("no-frame", !photo.has_frame);
  //   //         element.src = photo.src;
  //   //         element.srcset = photo.sizes.map(size => `${size.src} ${size.width}w`).join(",");
  //   //         element.sizes = "(min-width: 1024px) 1024px, 100vw";
  //   //         if (edition.border_width && edition.border_img && edition.border_slice) {
  //   //           element.style = `
  //   //             width: ${edition.width}em;
  //   //             height: auto;
  //   //             border-style: solid;
  //   //             border-width: ${edition.border_width.toFixed(4)}em;
  //   //             border-image: url(${edition.border_img}) ${edition.border_slice} stretch;
  //   //             padding: ${edition.padding_top.toFixed(4)}em ${edition.padding_right.toFixed(4)}em ${edition.padding_bottom.toFixed(4)}em ${edition.padding_left.toFixed(4)}em;
  //   //           `;
  //   //         } else {
  //   //           element.style = `
  //   //             width: ${edition.width}em;
  //   //             height: auto;
  //   //             padding: ${edition.padding_top.toFixed(4)}em ${edition.padding_right.toFixed(4)}em ${edition.padding_bottom.toFixed(4)}em ${edition.padding_left.toFixed(4)}em;
  //   //           `;
  //   //         }
  //   //       }
  //   //     }
  //   //   }
  //   // };
  //
  //   const item = photo;
  //
  //   if (!photo.editions) return {};
  //
  //   const edition = photo.editions[0];
  //
  //   return {
  //     tag: "figure",
  //     class: "desktop-only",
  //     update: element => {
  //       element.classList.toggle("panorama", edition.width > edition.height);
  //       element.classList.toggle("portrait", edition.width < edition.height);
  //     },
  //     children: [
  //       {
  //         tag: "img",
  //         update: element => {
  //
  //           element.setAttribute("data-zoom", edition.zoom);
  //           element.setAttribute("data-zoom_h", edition.zoom_h);
  //           element.setAttribute("data-image_width", edition.image_width);
  //           element.setAttribute("data-image_height", edition.image_height);
  //
  //           element.src = item.src;
  //           element.srcset = item.sizes.map(size => `${size.src} ${size.width}w`).join(",");
  //           element.sizes = "(min-width: 1024px) 1024px, 100vw";
  //           if (edition.has_frame && edition.border_width && edition.border_img && edition.border_slice) {
  //             element.classList.remove("no-frame");
  //             element.classList.add("has-frame");
  //             element.style = `
  //               width:${edition.width*edition.zoom_h/edition.zoom}em;
  //               height:auto;
  //               border-style: solid;
  //               border-width:${edition.border_width}em;
  //               border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
  //               padding:${edition.padding_top}em ${edition.padding_right}em ${edition.padding_bottom}em ${edition.padding_left}em;`;
  //           } else {
  //             element.classList.add("no-frame");
  //             element.classList.remove("has-frame");
  //             element.style = `
  //               width:${edition.width*edition.zoom_h/edition.zoom}em;
  //               height:auto;
  //
  //               padding:${edition.padding_top}em ${edition.padding_right}em ${edition.padding_bottom}em ${edition.padding_left}em;`;
  //           }
  //         }
  //       },
  //       {
  //         class: "border",
  //         update: element => {
  //           if (edition.has_frame && edition.border_width && edition.border_img && edition.border_slice) {
  //             element.classList.remove("hidden");
  //             element.style = `
  //               border-style: solid;
  //               border-width:${edition.border_width}em;
  //               border-image:url(${edition.border_img}) ${edition.border_slice} stretch;`;
  //           } else {
  //             element.classList.add("hidden");
  //           }
  //         }
  //       }
  //
  //     ]
  //   };
  // }

  buildDesktopImage(photo, editionIndex = 0) {

    const item = photo;

    if (!photo.editions) return {};


    return {
      tag: "figure",
      // class: "desktop-only",
      update: (element, implant) => {



        // const edition = photo.editions[this.currentEdition || 0];
        const edition = photo.editions[editionIndex];


        const ratio = edition.image_width/edition.image_height;
        element.classList.toggle("panorama", ratio > 1.1);
        element.classList.toggle("portrait", ratio < 0.8);
        element.classList.toggle("very-large", ratio > 1.8);

        element.setAttribute("data-ratio", ratio);

        implant.children = [
          {
            tag: "img",
            update: element => {

              // element.setAttribute("data-zoom", edition.zoom);
              // element.setAttribute("data-zoom_h", edition.zoom_h);
              // element.setAttribute("data-image_width", edition.image_width);
              // element.setAttribute("data-image_height", edition.image_height);

              element.onload = event => {
                const ratio = element.naturalWidth/element.naturalHeight;
                element.parentNode.setAttribute("data-ratio-natural", ratio);
                element.parentNode.classList.toggle("panorama", ratio > 1.1);
                element.parentNode.classList.toggle("very-large", ratio > 1.8);
                element.parentNode.classList.toggle("portrait", ratio < 0.8);
              };

              element.src = item.src;
              element.srcset = item.sizes.map(size => `${size.src} ${size.width}w`).join(",");
              element.sizes = "(min-width: 1024px) 1024px, 100vw";
              if (edition.has_frame && edition.border_width && edition.border_img && edition.border_slice) {
                element.classList.remove("no-frame");
                element.classList.add("has-frame");
                element.style = `
                  width:auto;
                  height:1em;
                  border-style: solid;
                  border-width:${edition.cadre_width/(edition.paper_height + edition.cadre_width*2)}em;
                  border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
                  padding:${(edition.paper_height-edition.image_height)/(edition.paper_height + edition.cadre_width*2)/2}em ${(edition.paper_width-edition.image_width)/(edition.paper_height + edition.cadre_width*2)/2}em;`;
              } else {
                element.classList.add("no-frame");
                element.classList.remove("has-frame");
                element.style = `
                  width:auto;
                  height:1em;
                  padding:${(edition.paper_height-edition.image_height)/edition.paper_height/2}em ${(edition.paper_width-edition.image_width)/edition.paper_height/2}em;`;
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
                  border-width:${edition.cadre_width/(edition.paper_height + edition.cadre_width*2)}em;
                  border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
                  `;
              } else {
                element.classList.add("hidden");
              }
            }
          }

        ];
      }
    };
  }

  // buildMobileImage(photo) {
  //
  //   const item = photo;
  //
  //   if (!photo.editions) return {};
  //
  //   const edition = photo.editions[this.currentEdition || 0];
  //
  //   return {
  //     tag: "figure",
  //     class: "mobile-only",
  //     update: (element, implant) => {
  //
  //       const edition = photo.editions[this.currentEdition || 0];
  //
  //       element.classList.toggle("panorama", edition.image_width > edition.image_height);
  //       element.classList.toggle("portrait", edition.image_width < edition.image_height);
  //
  //       implant.children = [
  //         {
  //           tag: "img",
  //           update: element => {
  //             element.src = item.src;
  //             element.srcset = item.sizes.map(size => `${size.src} ${size.width}w`).join(",");
  //             element.sizes = "(min-width: 1024px) 1024px, 100vw";
  //             if (edition.has_frame && edition.border_width && edition.border_img && edition.border_slice) {
  //               element.classList.remove("no-frame");
  //               element.classList.add("has-frame");
  //               element.style = `
  //                 width:1em;
  //                 height:auto;
  //                 border-style: solid;
  //                 border-width:${edition.cadre_width/edition.paper_width}em;
  //                 border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
  //                 padding:${(edition.paper_height - edition.image_height)/edition.paper_width/2}em ${(edition.paper_width - edition.image_width)/edition.paper_width/2}em`;
  //             } else {
  //               element.classList.add("no-frame");
  //               element.classList.remove("has-frame");
  //               element.style = `
  //                 width:1em;
  //                 height:auto;
  //                 padding:${(edition.paper_height - edition.image_height)/edition.paper_width/2}em ${(edition.paper_width - edition.image_width)/edition.paper_width/2}em`;
  //             }
  //           }
  //         },
  //         {
  //           class: "border",
  //           update: element => {
  //             if (edition.has_frame && edition.border_width && edition.border_img && edition.border_slice) {
  //               element.classList.remove("hidden");
  //               element.style = `
  //               border-style: solid;
  //               border-width:${edition.cadre_width/edition.paper_width}em;
  //               border-image:url(${edition.border_img}) ${edition.border_slice} stretch;`;
  //             } else {
  //               element.classList.add("hidden");
  //             }
  //           }
  //         }
  //
  //       ];
  //     }
  //
  //   };
  // }

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
          children: [
            {
              tag: "a",
              update: element => {
                element.onclick = async event => {
                  event.preventDefault();
                  // Actwall.ViewInRoom.open(this.results.photo.product_id);

                  // abduct(document.getElementById("popup-viewinroom"), this.buildViewInRoom());

                  this.viewInRoom = true;

                  await this.render();

                  history.pushState({productId: this.productId, formatIndex: this.currentEdition, viewInRoom: true}, null, this.results.photo.permalink);

                }
              },
              children: [
                {
                  class: "picto-square"
                },
                {
                  tag: "span",
                  init: element => element.innerHTML = Actwall.__("View in room")
                }
              ]
            }

          ]
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


    console.log(photo.editions);

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
                  update: element => {
                    // element.innerHTML = `${currentEdition.prix_encadre.toLocaleString("de-CH").replace("’", "'") || ""}.-`;
                    element.innerHTML = `${currentEdition.prix.toLocaleString("de-CH").replace("’", "'") || ""}.-`;
                  }
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
                            this.currentEdition = parseInt(element.value);

                            // history.pushState({productId: this.productId, formatIndex: this.currentEdition, viewInRoom: true}, null, this.results.photo.permalink);

                            history.pushState({...history.state, formatIndex: this.currentEdition}, null);
                            this.render();
                          }
                        },
                        children: photo.editions.map((edition, index) => {
                          return {
                            tag: "option",
                            update: element => {
                              // console.log(edition);
                              element.innerHTML = `${edition.has_frame ? Actwall.__("Framed print") : Actwall.__("Print only")}, ${Actwall.__("Size")} ${edition.paper_width} x ${edition.paper_height}CM<!--(${Math.floor(parseInt(edition.paper_width)*0.393701)} x ${Math.floor(parseInt(edition.paper_height)*0.393701)} in)-->`;
                              element.selected = index === (this.currentEdition || 0);
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
              element.href = "mailto:info@actwall.ch?subject=subject&body=body";
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
            update: (element, implant, render) => {

              if (this.format === undefined) {
                this.fetch(`format/${currentEdition.format.id}`).then(results => {
                  this.format = results;
                  render();
                });
              }

            },
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
                    update: async (element, implant, render) => {

                      if (this.format) {
                        let specifications = this.format.specifications || [];
                        if (currentEdition.framing) {
                          specifications = specifications.concat(currentEdition.specifications.split("\n"));
                        }
                        if (specifications.length) {
                          implant.child = {
                            tag: "ul",
                            children: specifications.map(item => {
                              return {
                                tag: "li",
                                update: element => {
                                  element.classList.toggle('hidden', !item);
                                  element.innerHTML = item;
                                }
                              }
                            })
                          };
                        }
                      }


                      // if (this.specifications === undefined) {
                      //
                      //   if (currentEdition.specifications) {
                      //
                      //     this.specifications = currentEdition.specifications.split("\n");
                      //
                      //   } else {
                      //
                      //     this.getSpecifications(currentEdition).then(specifications => {
                      //       this.specifications = specifications || null;
                      //       render();
                      //     });
                      //
                      //     this.specifications = null;
                      //
                      //   }
                      //
                      // }
                      //
                      //
                      //
                      // if (this.specifications) {
                      //
                      //   implant.child = {
                      //     tag: "ul",
                      //     children: this.specifications.map(item => {
                      //       return {
                      //         tag: "li",
                      //         update: element => element.innerHTML = item
                      //       }
                      //     })
                      //   };
                      //
                      // }


                    }


                    // update: async (element, implant, render) => {
                    //   let specifications = (currentEdition.specifications || currentEdition._specifications);
                    //   if (specifications) {
                    //     implant.child = {
                    //       tag: "ul",
                    //       children: specifications.split("\n").map(item => {
                    //         return {
                    //           tag: "li",
                    //           update: element => element.innerHTML = item
                    //         }
                    //       })
                    //     };
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
                    // update: async (element, implant) => {
                    //   // let framings;
                    //   //
                    //   // if (currentEdition.framing) {
                    //   //   framings = currentEdition.framing.split("\n");
                    //   // } else {
                    //   //   framings = await this.getFraming(currentEdition)
                    //   // }
                    //
                    //   if (this.framings === undefined) {
                    //
                    //     if (currentEdition.framing) {
                    //
                    //       this.framings = currentEdition.framing.split("\n");
                    //
                    //     } else {
                    //
                    //       this.getFraming(currentEdition).then(framings => {
                    //         this.framings = framings || null;
                    //         render();
                    //       });
                    //
                    //       this.framings = null;
                    //
                    //     }
                    //
                    //   }
                    //
                    //   if (this.framings) {
                    //
                    //     implant.child = {
                    //       tag: "ul",
                    //       children: this.framings.map(item => {
                    //         return {
                    //           tag: "li",
                    //           update: element => element.innerHTML = item
                    //         }
                    //       })
                    //     };
                    //
                    //   }
                    //
                    // }
                    update: async (element, implant, render) => {
                      // let framing = (currentEdition.framing || currentEdition._framing);
                      // if (framing) {
                      //   implant.child = {
                      //     tag: "ul",
                      //     children: framing.split("\n").map(item => {
                      //       return {
                      //         tag: "li",
                      //         update: element => element.innerHTML = item
                      //       }
                      //     })
                      //   };
                      // }
                      if (this.format) {
                        let framing = this.format.framing || [];
                        if (currentEdition.framing) {
                          framing = framing.concat(currentEdition.framing.split("\n"));
                        }
                        if (framing.length) {
                          implant.child = {
                            tag: "ul",
                            children: framing.map(item => {
                              return {
                                tag: "li",
                                update: element => {
                                  element.classList.toggle('hidden', !item);
                                  element.innerHTML = item;
                                }
                              }
                            })
                          };
                        }
                      }
                    }
                  }
                ]
              },
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.selected_exhibitions || !photo.selected_exhibitions.length);
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
                      // update: element => {
                      //   element.innerHTML = photo.selected_exhibitions || "";
                      // }
                      child: {
                        tag: "table",
                        children: photo.selected_exhibitions.map(item => {
                          return {
                            tag: "tr",
                            children: [
                              {
                                tag: "td",
                                child: {
                                  tag: "span",
                                  update: element => {
                                    element.innerHTML = item.name;
                                  }
                                }
                              },
                              {
                                tag: "td",
                                child: {
                                  tag: "span",
                                  update: element => {
                                    element.innerHTML = item.description;
                                  }
                                }
                              }
                            ]
                          };
                        })
                      }
                    }
                  }
                ]
              },
              {
                tag: "li",
                class: "info-handle",
                update: element => {
                  element.classList.toggle("hidden", !photo.acquired || !photo.acquired.length);
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
                      // update: element => {
                      //   element.innerHTML = photo.acquired || "";
                      // }
                      child: {
                        tag: "table",
                        children: photo.acquired.map(item => {
                          return {
                            tag: "tr",
                            children: [
                              {
                                tag: "td",
                                child: {
                                  tag: "span",
                                  update: element => {
                                    element.innerHTML = item.name;
                                  }
                                }
                              },
                              {
                                tag: "td",
                                child: {
                                  tag: "span",
                                  update: element => {
                                    element.innerHTML = item.description;
                                  }
                                }
                              }
                            ]
                          };
                        })
                      }
                    }
                  }
                ]
              }
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
              //         <span>${Actwall.__("Signature")}</span>`;
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
              // {
              //   tag: "li",
              //   class: "info-handle",
              //   update: element => {
              //     element.classList.toggle("hidden", !photo.history);
              //   },
              //   children: [
              //     {
              //       class: "info-handle",
              //       init: element => {
              //         element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
              //           <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
              //         </svg>
              //         <span>${Actwall.__("Exhibitions history")}</span>`;
              //       }
              //     },
              //     {
              //       class: "info-body",
              //       child: {
              //         update: element => {
              //           element.innerHTML = photo.history || "";
              //         }
              //       }
              //     }
              //   ]
              // },
              // {
              //   tag: "li",
              //   class: "info-handle",
              //   update: element => {
              //     element.classList.toggle("hidden", !photo.shipping);
              //   },
              //   children: [
              //     {
              //       class: "info-handle",
              //       init: element => {
              //         element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
              //           <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
              //         </svg>
              //         <span>${Actwall.__("Shipping")}</span>`;
              //       }
              //     },
              //     {
              //       class: "info-body",
              //       child: {
              //         update: element => {
              //           element.innerHTML = photo.shipping || "";
              //         }
              //       }
              //     }
              //   ]
              // }
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



  buildViewInRoom() {
    return [
      {
        class: "popup-content popup-view-in-room",
        update: (element, implant, render) => {

          // this.render = render;
          element.classList.toggle("hidden", !this.viewInRoom);

          element.classList.toggle("align-v", Boolean(this.viewInRoomV));




          // setTimeout(() => {
          //   element.classList.add("ready");
          // }, 1000);

          document.body.classList.toggle("frozen", Boolean(this.viewInRoom));

          // document.body.classList.toggle("viewinroom-open", Boolean(this.isOpen));

          if (this.viewInRoom) {
            implant.children = [
              {
                class: "loading",
                update: element => element.classList.toggle("hidden", Boolean(this.results))
              },
              {
                tag: "header",
                class: "view-in-room",
                child: {
                  class: "header-content",
                  children: [
                    {
                      tag: "a",
                      class: "burger close",
                      update: element => {
                        element.onclick = event => {
                          event.preventDefault();
                          history.back();
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
                class: "view-in-room align-v",
                update: (element, implant) => {
                  element.classList.toggle("hidden", !this.results);

                  // element.onclick = event => {
                  //   event.preventDefault();
                  //   element.classList.toggle("align-v");
                  // }

                  if (this.results && this.results.photo) {

                    const item = this.results.photo;
                    const edition = this.results.photo.editions[this.currentEdition || 0];

                    implant.children = [
                      {
                        class: "background",
                        child: {
                          tag: "img",
                          init: element => {
                            element.src = `${Actwall.theme_url}/images/view-in-room-vertical.jpg`;
                          }
                        }
                      },
                      {
                        class: "artwork",
                        // child: this.buildDesktopImage(this.results.photo, this.currentEdition)
                        child: {
                          tag: "figure",
                          // class: "desktop-only",
                          update: element => {
                            element.classList.toggle("panorama", edition.width > edition.height);
                            element.classList.toggle("portrait", edition.width < edition.height);
                          },
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
                                    width:auto;
                                    height:${edition.paper_height}em;
                                    border-style: solid;
                                    border-width:${edition.cadre_width}em;
                                    border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
                                    padding:${(edition.paper_height-edition.image_height)/2}em ${(edition.paper_width-edition.image_width)/2}em;`;
                                } else {
                                  element.classList.add("no-frame");
                                  element.classList.remove("has-frame");
                                  element.style = `
                                    width:auto;
                                    height:${edition.paper_height}em;
                                    padding:${(edition.paper_height-edition.image_height)/2}em ${(edition.paper_width-edition.image_width)/2}em;`;
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
                                    border-width:${edition.cadre_width}em;
                                    border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
                                    `;
                                } else {
                                  element.classList.add("hidden");
                                }
                              }
                            }

                          ]
                        }
                      }
                    ]
                  }
                }
              },
              {
                tag: "footer",
                class: "view-in-room desktop-only",
                child: {
                  tag: "ul",
                  class: "detail-navigation",
                  children: [
                    {
                      tag: "li",
                      class: "title-tab",
                      child: {
                        tag: "h1",
                        update: element => element.innerHTML = this.results.member_title
                      }
                    },
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
            ];
          }
        }
      }
    ];
  }

}
