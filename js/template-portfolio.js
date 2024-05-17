Actwall.PortfolioSlideshow = class {

  constructor(mediaContainer, serieName) {


    const container = mediaContainer.cloneNode(true);

    this.player = new KarmaPlayer();
    this.serieName = serieName;
    this.transitionDuration = 300;

    this.items = [...container.children];
    // this.slides = [];

    const links = mediaContainer.querySelectorAll("a");

    for (let i = 0; i < links.length; i++) {

      links[i].onclick = event => {
        event.preventDefault();

        this.active = true;
        this.player.index = i;
        this.player.init();
        this.render();

      }

      // this.slides.push({
      //   link: links[i],
      //   caption: links[i].getAttribute("data-caption") || "Serie, Title, year"
      // });

    }

    this.player.onShift = (element, index) => {
      element.style.transform = `translateX(${(index)*100}%)`;
      element.style.transition = "none";
    };

    this.player.onEnter = (element, direction, currentIndex) => {
      if (currentIndex <= -1 || currentIndex >= 1) {
        element.style.transform = `translateX(${direction*100}%)`;
        element.style.transition = "none";
        element.offsetTop; // force reflow
      }
      element.style.transform = "translateX(0)";
      element.style.transition = `transform ${this.transitionDuration}ms`;
    };

    this.player.onLeave = (element, direction) => {
      element.style.transform = `translateX(${-direction*100}%)`;
      element.style.transition = `transform ${this.transitionDuration}ms`;
    };

    this.player.onInit = (element, isCurrent) => {
      element.style.transform = `translateX(${isCurrent ? 0 : -100}%)`;
      element.style.transition = "none";
    };

  }

  build() {

    return [{
      class: "portfolio-slideshow",
      update: (element, implant, render) => {
        this.render = render;
        element.classList.toggle("hidden", !this.active)
      },
      children: [
        {
          class: "slideshow-header",
          children: [
            {
              class: "close",
              init: element => {
                element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none"><path d="M19.3455 0.753669L10.9252 9.17393L2.93326 1.18198L0.681893 3.43334L8.67384 11.4253L0.253584 19.8456L2.65446 22.2464L11.0747 13.8262L19.0667 21.8181L21.318 19.5668L13.3261 11.5748L21.7463 3.15454L19.3455 0.753669Z" fill="black"/></svg>`;
              },
              update: element => {
                element.onclick = event => {
                  this.active = false;
                  this.render();
                }
              }
            },
          ]
        },
        {
          class: "slideshow-body",
          children: [
            {
              class: "navigation left-navigation",
              update: element => {
                element.onclick = event => {
                  this.player.prev();
                  this.render();
                }
              },
              child: {
                class: "arrow left-arrow",
                init: element => {
                  element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="none"><path d="M3.33291 15L14 -9.53674e-07L10.9138 -6.83872e-07L9.53674e-07 15L3.33291 15Z" fill="black"/><path d="M3.33291 15L14 30L10.9138 30L9.53674e-07 15L3.33291 15Z" fill="black"/></svg>`;
                }
              }
            },
            {
              tag: "ul",
              class: "viewer",
              init: element => {
                for (let item of this.items) {
                  element.appendChild(item);
                  this.player.add(item);
                }
              },
              update: element => {
                // for (let i = 0; i < this.items.length; i++) {
                //
                //
                //
                //   if (i === this.player.index) {
                //     this.items[i].style.transform = "translateX(0)";
                //   } else {
                //     this.items[i].style.transform = "translateX(-100%)";
                //   }
                // }
              }
            },
            {
              class: "navigation right-navigation",
              update: element => {
                element.onclick = event => {
                  this.player.next();
                  this.render();
                }
              },
              child: {
                class: "arrow right-arrow",
                init: element => {
                  element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="none"><path d="M10.6671 15L1.31134e-06 -9.53674e-07L3.08618 -6.83872e-07L14 15L10.6671 15Z" fill="black"/><path d="M10.6671 15L-9.49328e-08 30L3.08618 30L14 15L10.6671 15Z" fill="black"/></svg>`;
                }
              }
            }
          ]
        },
        {
          class: "slideshow-footer",
          children: [
            {
              class: "slideshow-caption",
              child: {
                tag: "span",
                update: element => {
                  // element.innerHTML = "Serie, Title, year";
                  const currentItem = this.player.getCurrent();
                  const img = currentItem && currentItem.querySelector("img");
                  if (img) {
                    element.innerHTML = [
                      this.serieName,
                      img.getAttribute("data-title"),
                      img.getAttribute("data-year")
                    ].filter(item => item).join(", ");
                    // element.innerHTML = this.slides[this.currentIndex].caption;
                  }

                }
              }
            },
            {
              class: "slideshow-navigation",
              child: {
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
                          <path d="M17.0098 6.59L9.71977 13.99L9.71977 -3.18656e-07L7.28977 -4.24875e-07L7.28976 13.99L-0.000234892 6.59L-0.000235041 9.99L8.49976 18.49L17.0098 9.99L17.0098 6.59Z" fill="black"></path>
                        </svg>
                        <span>Save</span>`;
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
                          <path d="M17.0098 6.59L9.71977 13.99L9.71977 -3.18656e-07L7.28977 -4.24875e-07L7.28976 13.99L-0.000234892 6.59L-0.000235041 9.99L8.49976 18.49L17.0098 9.99L17.0098 6.59Z" fill="black"></path>
                        </svg>
                        <span>Share</span>`;
                      }
                    }
                  }
                ]
              }
            }
          ]

        }
      ]

    }];

  }

}
