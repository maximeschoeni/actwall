addEventListener("popstate", (event) => {
  Actwall.ViewInRoom.update();
});

Actwall.ViewInRoom = class extends Actwall.SingleProduct {


  static async update() {

    if (this.current) {

      if (history.state && history.state.viewInRoomId) {

        this.current.productId = history.state.viewInRoomId;
        this.current.isOpen = true;

        await this.current.query();

      } else {

        this.current.isOpen = false;

      }

      await this.render();

    }

  }

  static async render() {

    const container = document.getElementById("popup-viewinroom");

    if (container && this.current) {

      await abduct(container, this.current.build());

    }

  }

  static async open(id) {

    this.current = new Actwall.ViewInRoom(id);

    await this.render();

    await this.current.query();

    history.pushState({productId: id}, null, this.current.results.photo.permalink);

  }


  // constructor(productId) {
  //
  //   super();
  //
  //   this.isOpen = true;
  //   this.productId = productId;
  //
  // }


  build() {
    return [
      {
        class: "popup-content popup-view-in-room",
        update: (element, implant, render) => {

          this.render = render;
          element.classList.toggle("hidden", !this.isOpen);

          // document.body.classList.toggle("frozen", Boolean(this.isOpen));

          if (this.isOpen) {
            implant.children = [
              {
                class: "loading",
                update: element => element.classList.toggle("hidden", Boolean(this.results))
              },
              {
                tag: "header",
                class: "main-header",
                child: {
                  class: "header-content",
                  children: [
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
                }
              },
              {
                tag: "main",
                class: "single product",
                update: (element, implant) => {
                  element.classList.toggle("hidden", !this.results)
                  if (this.results && this.results.photo) {

                    const edition = this.results.photo.editions[0];

                    implant.children = [
                      {
                        class: "background",
                        child: {
                          tag: "img",
                          init: element => {
                            element.src = `${Actwall.theme_url}/images/viewinroom.jpg`;
                          }
                        }
                      },
                      {
                        class: "artwork",
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
                                    height:${edition.paper_height};
                                    border-style: solid;
                                    border-width:${edition.cadre_width}em;
                                    border-image:url(${edition.border_img}) ${edition.border_slice} stretch;
                                    padding:${(edition.paper_height-edition.image_height)/2}em ${(edition.paper_width-edition.image_width)/2}em;`;
                                } else {
                                  element.classList.add("no-frame");
                                  element.classList.remove("has-frame");
                                  element.style = `
                                    width:auto;
                                    height:${edition.paper_height};
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
                children: [
                  {
                    tag: "h1",
                    update: element => element.innerHTML = this.results.member_title
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
            ];
          }
        }
      }
    ];
  }

}
