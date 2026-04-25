@stack('scripts')

    <script src="{{ asset('frontend') }}/js/jquery-1.12.4.min.js"></script>
    <script src="{{ asset('frontend') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend') }}/js/particles.min.js"></script>
    <script src="{{ asset('frontend') }}/js/particles.js"></script>
    <script src="{{ asset('frontend') }}/js/colorfulTab.min.js"></script>
    <script src="{{ asset('frontend') }}/js/waypoint.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('frontend') }}/js/jquery.counterup.js"></script>
    <script src="{{ asset('frontend') }}/js/wow.min.js"></script>
    <script src="{{ asset('frontend') }}/js/slick.min.js"></script>
    <script src="{{ asset('frontend') }}/js/venobox.min.js"></script>
    <script src="{{ asset('frontend') }}/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <script>
        // When the image is clicked, the file input is triggered
        document.getElementById("profile-image").addEventListener("click", function() {
            document.getElementById("image-upload").click();
        });
    
        // Optionally, you can handle the image selection here:
        document.getElementById("image-upload").addEventListener("change", function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("profile-image").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
        
    </script>

    <script>
      const swiperthumblin = new Swiper('.swiperthumblin', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
    
        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
        },
    
        // Navigation arrows
        navigation: {
          nextEl: '.swiper-button-nextt',
          prevEl: '.swiper-button-prevv',
        },
    
        // If we need scrollbar
        scrollbar: {
          el: '.swiper-scrollbar',
        },
    
         // Autoplay setting
        autoplay: {
          delay: 2500, // Time between slides in milliseconds
          disableOnInteraction: false, // Keep autoplay running even after interaction
        },
    
        // Responsive breakpoints
        breakpoints: {
          // when window width is >= 320px
          320: {
            slidesPerView: 4,
            spaceBetween: 10,
          },
          // when window width is >= 480px
          480: {
            slidesPerView: 4,
            spaceBetween: 20,
          },
          // when window width is >= 768px
          768: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
          // when window width is >= 1024px
          1024: {
            slidesPerView: 4,
            spaceBetween: 40,
          },
        },
      });
    </script>


    <script>
      const swiper = new Swiper('.swiper', {
      // Optional parameters
      direction: 'horizontal',
      loop: true,
    
      // If we need pagination
      pagination: {
        el: '.swiper-pagination',
      },
    
      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
       // Autoplay setting
        autoplay: {
          delay: 2500, // Time between slides in milliseconds
          disableOnInteraction: false, // Keep autoplay running even after interaction
        },
    
      // And if we need scrollbar
      scrollbar: {
        el: '.swiper-scrollbar',
      },
    });
    
    </script>

    <script>
        function changeImage(imageSrc) {
        // Get the main image element by its id
        const mainImage = document.getElementById("main-image");
        
        // Change the src of the main image to the clicked thumbnail's src
        mainImage.src = imageSrc;
    }
    
    </script>
    
    <SCript>
        document.querySelectorAll('.color-img').forEach(img => {
        img.addEventListener('click', function() {
            // Remove 'selected' class from all images
            document.querySelectorAll('.color-img').forEach(image => image.classList.remove('selected'));
    
            // Add 'selected' class to clicked image
            img.classList.add('selected');
        });
    });
    
    // Set the first image as selected by default
    document.querySelector('.color-img').classList.add('selected');
    
    </SCript>
    
    <script>
        document.querySelectorAll('.size-num').forEach(size => {
        size.addEventListener('click', function() {
            // Remove 'selected' class from all size options
            document.querySelectorAll('.size-num').forEach(option => option.classList.remove('selected'));
    
            // Add 'selected' class to clicked size option
            size.classList.add('selected');
        });
    });
    
    // Set the first size option as selected by default
    document.querySelector('.size-num').classList.add('selected');
    
    </script>

    <script>
    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
    const numberDisplay = document.getElementById('number');
    
    let count = 1;
    
    // Function to update UI
    function updateUI() {
        numberDisplay.textContent = count;
        decreaseBtn.disabled = count === 0; // Disable if count is 0
    }
    
    decreaseBtn.addEventListener('click', () => {
        if (count > 0) {
            count--;
            updateUI();
        }
    });
    
    increaseBtn.addEventListener('click', () => {
        count++;
        updateUI();
    });
    
    // Initial UI state
    updateUI();
    
    </script>
    
    <script>
    document.querySelectorAll('.counter').forEach(counter => {
        const decreaseBtn = counter.querySelector('.decrease');
        const increaseBtn = counter.querySelector('.increase');
        const numberDisplay = counter.querySelector('.number');
    
        let count = 1;
    
        // Function to update UI
        function updateUI() {
            numberDisplay.textContent = count;
            decreaseBtn.disabled = count === 0; // Disable if count is 0
        }
    
        decreaseBtn.addEventListener('click', () => {
            if (count > 0) {
                count--;
                updateUI();
            }
        });
    
        increaseBtn.addEventListener('click', () => {
            count++;
            updateUI();
        });
    
        // Initial UI state
        updateUI();
    });
    </script>
    
     <script>
          $('#summernote').summernote({
            placeholder: 'Please Write  Here',
            tabsize: 2,
            height: 120,
            toolbar: [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'clear']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['table', ['table']],
              ['insert', ['link', 'picture', 'video']],
              ['view', ['fullscreen', 'codeview', 'help']]
            ]
          });
        </script>
    
            <script>
          $('#summerhighlight').summernote({
            placeholder: 'Please Write Here',
            tabsize: 2,
            height: 120,
            toolbar: [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'clear']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['table', ['table']],
              ['insert', ['link', 'picture', 'video']],
              ['view', ['fullscreen', 'codeview', 'help']]
            ]
          });
        </script>
        
    <!-- chat -->
      <!-- Minimal JS to populate and interact -->
      <script>
      // Sample data
      const convs = [
        {
          id: "k1",
          name: "Joker",
          avatar:
            "https://i.pinimg.com/236x/2c/b5/60/2cb5607dd16c8bd26d5eda6908323ec0.jpg",
          snippet: "Taxi service for me and...",
          time: "5 minutes ago",
          status: "Active 4 hours ago",
          messages: [
            { side: "right", text: "Hey Karlo, How you doing", time: "Seen 5 minutes ago" },
            { side: "left", text: "Hi, Redwan. I am fine.", time: "4 min ago" },
            { side: "left", text: "Excellent service...", time: "3 min ago" },
            { side: "right", text: "Nice and friendly guy...", time: "2 min ago" },
          ],
        },
        {
          id: "p2",
          name: "Pagla Pur",
          avatar:
            "https://i.pinimg.com/236x/2c/b5/60/2cb5607dd16c8bd26d5eda6908323ec0.jpg",
          snippet: "Me: Hey, Andrew. Requested ...",
          time: "2 hours ago",
          status: "Active 1 hour ago",
          messages: [
            { side: "left", text: "Hello there.", time: "1h ago" },
            { side: "right", text: "Thanks!", time: "58 min ago" },
          ],
        },
    
        {
          id: "22",
          name: "Nasir Scientist",
          avatar:
            "https://i.pinimg.com/236x/2c/b5/60/2cb5607dd16c8bd26d5eda6908323ec0.jpg",
          snippet: "Me: Hey, Andrew. Requested ...",
          time: "2 hours ago",
          status: "Active 1 hour ago",
          messages: [
            { side: "left", text: "Hello there.", time: "1h ago" },
            { side: "right", text: "Thanks!", time: "58 min ago" },
          ],
        },
    
            {
          id: "22",
          name: "ALIEN",
          avatar:
            "https://i.pinimg.com/236x/2c/b5/60/2cb5607dd16c8bd26d5eda6908323ec0.jpg",
          snippet: "Me: Hey, Andrew. Requested ...",
          time: "2 hours ago",
          status: "Active 1 hour ago",
          messages: [
            { side: "left", text: "Hello there.", time: "1h ago" },
            { side: "right", text: "Thanks!", time: "58 min ago" },
          ],
        },
      ];
    
      // Helper: render conversation list (for each .quikctech-conversation-list)
      function renderConvList(container) {
        container.innerHTML = "";
        convs.forEach((c) => {
          const item = document.createElement("div");
          item.className = "quikctech-item";
          item.dataset.convId = c.id;
          item.innerHTML = `
            <div class="quikctech-avatar"><img src="${c.avatar}" alt="${c.name}" /></div>
            <div class="quikctech-meta">
              <div class="d-flex align-items-center">
                <div class="quikctech-title me-auto">${c.name}</div>
                <div class="quikctech-time">${c.time}</div>
              </div>
              <div class="quikctech-sub">${c.snippet}</div>
            </div>
          `;
          item.addEventListener("click", () => openConversation(c.id));
          container.appendChild(item);
        });
      }
    
      // Render messages in chat body
      const chatBody = document.getElementById("quikctech-chatbody");
      function renderMessages(conv) {
        chatBody.innerHTML = "";
        conv.messages.forEach((m) => {
          const row = document.createElement("div");
          row.className =
            "quikctech-msg-row " +
            (m.side === "left" ? "quikctech-msg-left" : "quikctech-msg-right");
    
          const msgTime = m.time || "2 min ago";
    
          if (m.side === "left") {
            row.innerHTML = `
              <div class="quikctech-avatar" style="width:46px;height:46px;border-radius:50%;flex-shrink:0;">
                <img src="${conv.avatar}" alt="">
              </div>
              <div>
                <div class="quikctech-bubble quikctech-bubble-left">${m.text}</div>
                <div class="quikctech-small-time">${msgTime}</div>
              </div>
            `;
          } else {
            row.innerHTML = `
              <div class="quikctech-avatar-small" style="width:36px;height:36px;border-radius:50%;flex-shrink:0;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjfMbisBe9VBlWhtG4Q-q0XIiuqQyR20aXOQ&s" alt="">
              </div>
              <div>
                <div class="quikctech-bubble quikctech-bubble-right">${m.text}</div>
                <div class="quikctech-small-time">${msgTime}</div>
              </div>
            `;
          }
          chatBody.appendChild(row);
        });
        chatBody.scrollTop = chatBody.scrollHeight;
      }
    
      function openConversation(id) {
        const conv = convs.find((c) => c.id === id);
        if (!conv) return;
        document.getElementById("quikctech-header-name").textContent = conv.name;
        document.getElementById("quikctech-header-status").textContent = conv.status;
        renderMessages(conv);
        chatBody.dataset.activeConv = conv.id;
      }
    
      // Send message
      const sendBtn = document.getElementById("quikctech-send");
      const inputEl = document.getElementById("quikctech-input");
    
      sendBtn.addEventListener("click", sendMessage);
      inputEl.addEventListener("keydown", (e) => {
        if (e.key === "Enter") sendMessage();
      });
    
      function sendMessage() {
        const text = inputEl.value.trim();
        if (!text) return;
        const activeId = chatBody.dataset.activeConv || convs[0].id;
        const conv = convs.find((c) => c.id === activeId);
        if (!conv) return;
    
        conv.messages.push({ side: "right", text, time: "just now" });
        renderMessages(conv);
        inputEl.value = "";
      }
    
      // Initial render for ALL conversation lists
      document.querySelectorAll(".quikctech-conversation-list").forEach((listEl) => {
        renderConvList(listEl);
      });
      openConversation(convs[0].id);
    </script>
    
    <script>
      // Grab all conversation items
      document.addEventListener("DOMContentLoaded", function () {
        const convItems = document.querySelectorAll(".quikctech-item");
        const offcanvasEl = document.getElementById("message-list");
        const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
    
        convItems.forEach(item => {
          item.addEventListener("click", () => {
            bsOffcanvas.hide(); // Auto close on click
          });
        });
      });
    </script>
    
    
    <!-- chat -->
    
    <script>
        document.querySelectorAll('.quikctech-toggle').forEach(button => {
          button.addEventListener('click', () => {
            const content = button.previousElementSibling; // get the <p> before button
            content.classList.toggle('quikctech-expanded');
            button.textContent = content.classList.contains('quikctech-expanded') 
              ? "See Less" 
              : "See More..";
          });
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
          const moreToggle = document.querySelector(".quikctech-more-toggle");
          const moreBox = document.getElementById("quikctechMoreBox");
          const closeBtn = document.querySelector(".quikctech-more-close");
        
          moreToggle.addEventListener("click", (e) => {
            e.preventDefault();
            moreBox.classList.toggle("active");
          });
        
          closeBtn.addEventListener("click", () => {
            moreBox.classList.remove("active");
          });
        });
    </script>
