<div class="contact-footer">
  <nav class="contact-nav" id="contact-button">
    <a class="handle">
      <div class="plus">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="white"/>
        </svg>
      </div>
      <span>Contact</span>
    </a>
    <div class="contact-nav-content">
      <ul>
        <li>
          <a class="mobile-only" href="https://api.whatsapp.com/send?phone=41782214737">
            <span>whatsapp chat</span>
            <div class="arrow">
              <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.582051 2.36326L5.29138 7.07259L0.709332 11.6546L2.36396 13.3093L6.94601 8.72722L8.72792 6.94531L7.07329 5.29068L2.36396 0.581351L0.582051 2.36326Z" fill="white"/>
              </svg>
            </div>
          </a>
          <a class="desktop-only" target="_blank" href="https://web.whatsapp.com/send?phone=41782214737">
            <span>whatsapp chat</span>
            <div class="arrow">
              <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.582051 2.36326L5.29138 7.07259L0.709332 11.6546L2.36396 13.3093L6.94601 8.72722L8.72792 6.94531L7.07329 5.29068L2.36396 0.581351L0.582051 2.36326Z" fill="white"/>
              </svg>
            </div>
          </a>
          <!-- <a class="desktop-only" href="whatsapp://send?phone=41782214737">
            <span>whatsapp chat</span>
            <div class="arrow">
              <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.582051 2.36326L5.29138 7.07259L0.709332 11.6546L2.36396 13.3093L6.94601 8.72722L8.72792 6.94531L7.07329 5.29068L2.36396 0.581351L0.582051 2.36326Z" fill="white"/>
              </svg>
            </div>
          </a> -->
        </li>
        <!-- <li>
          <a href="tel:+41782214737">
            <span>+41(0)78221.47.37</span>
            <div class="arrow">
              <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.582051 2.36326L5.29138 7.07259L0.709332 11.6546L2.36396 13.3093L6.94601 8.72722L8.72792 6.94531L7.07329 5.29068L2.36396 0.581351L0.582051 2.36326Z" fill="white"/>
              </svg>
            </div>
          </a>
        </li> -->
        <li>
          <a href="mailto:info@actwall.ch" target="_blank">
            <span>info@actwall.ch</span>
            <div class="arrow">
              <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.582051 2.36326L5.29138 7.07259L0.709332 11.6546L2.36396 13.3093L6.94601 8.72722L8.72792 6.94531L7.07329 5.29068L2.36396 0.581351L0.582051 2.36326Z" fill="white"/>
              </svg>
            </div>
          </a>
        </li>
        <li>
          <a class="button close">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M13.8239 0.554373L7.97186 6.40642L2.41749 0.852045L0.852798 2.41673L6.40717 7.97111L0.555126 13.8232L2.22372 15.4917L8.07577 9.6397L13.6301 15.1941L15.1948 13.6294L9.64046 8.07502L15.4925 2.22297L13.8239 0.554373Z" fill="white"/>
            </svg>
          </a>
        </li>
      </ul>
    </div>
  </nav>
  <script>
  addEventListener("DOMContentLoaded", event => {
    const element = document.getElementById("contact-button");
    const handle = element.querySelector(".handle");
    const body = element.querySelector(".contact-nav-content");
    const close = element.querySelector(".close");
    // const content = body.firstElementChild;
    new Accordeon(element, handle, body, close, false);
    // Accordeon.register(element, itemSelector, handleSelector, bodySelector, closeSelector);
  });
  </script>
</div>
