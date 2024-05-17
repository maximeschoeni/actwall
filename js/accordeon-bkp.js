addEventListener("DOMContentLoaded", event => {
  const elements = document.querySelectorAll(".accordeon");
  for (let element of elements) {
    new Accordeon(element, ".accordeon-item", ".accordeon-handle", ".accordeon-body", ".accordeon-close");
  }
});

window.Accordeon = class {

  constructor(element, itemSelector, handleSelector, bodySelector, closeSelector) {

    this.duration = 300;

    if (element && itemSelector) {

      const items = element.querySelectorAll(itemSelector);

      for (let item of items) {

        this.add(item, itemSelector, handleSelector, bodySelector, closeSelector)

        // let isOpen = false;
        // const handle = item.querySelector(handleSelector);
        // const body = item.querySelector(bodySelector);
        // const arrow = item.querySelector(closeSelector);
        //
        // if (handle && body) {
        //
        //   const content = body.children[0];
        //
        //   function open() {
        //     isOpen = true;
        //     body.style.height = `${content.clientHeight}px`;
        //     item.classList.add("open");
        //   }
        //
        //   function close() {
        //     isOpen = false;
        //     body.style.height = `${content.clientHeight}px`;
        //     body.style.overflow = "hidden";
        //     body.clientHeight; // -> force reflow
        //     body.style.height = "0";
        //     item.classList.remove("open");
        //   }
        //
        //   handle.onclick = event => {
        //     event.preventDefault();
        //     if (isOpen) {
        //       close();
        //     } else {
        //       open();
        //     }
        //   }
        //
        //   if (item.classList.contains('open')) {
        //
        //     isOpen = true;
        //     body.style.height = `auto`;
        //
        //   }
        //
        //   body.style.transition = `height ${this.duration}ms`;
        //
        //   body.ontransitionend = event => {
        //     if (isOpen) {
        //       body.style.height = "auto";
        //       body.style.overflow = "visible";
        //     }
        //   }
        //
        //   if (arrow) {
        //     arrow.onclick = event => {
        //       close();
        //     }
        //   }
        //
        // }

      }

    }

  }

  add(item, handle, body, arrow) {

    let isOpen = false;

    if (typeof handle === "string") {
      handle = item.querySelector(handle);
    }

    if (typeof body === "string") {
      body = item.querySelector(body);
    }

    if (typeof arrow === "string") {
      arrow = item.querySelector(arrow);
    }

    // const handle = item.querySelector(handleSelector);
    // const body = item.querySelector(bodySelector);
    // const arrow = item.querySelector(closeSelector);

    if (handle && body) {

      const content = body.children[0];

      function open() {
        isOpen = true;
        body.style.height = `${content.clientHeight}px`;
        item.classList.add("open");
      }

      function close() {
        isOpen = false;
        body.style.height = `${content.clientHeight}px`;
        body.style.overflow = "hidden";
        body.clientHeight; // -> force reflow
        body.style.height = "0";
        item.classList.remove("open");
      }

      handle.onclick = event => {
        event.preventDefault();

        if (isOpen) {
          close();
        } else {
          open();
        }
      }

      if (item.classList.contains('open')) {

        isOpen = true;
        body.style.height = `auto`;

      }



      body.style.transition = `height ${this.duration}ms`;

      body.ontransitionend = event => {
        if (isOpen) {
          body.style.height = "auto";
          body.style.overflow = "visible";
        }
      }

      if (arrow) {
        arrow.onclick = event => {
          close();
        }
      }
    }
  }

}
