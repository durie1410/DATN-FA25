// ============================================
// SCROLL RESTORATION - Ngăn trình duyệt khôi phục vị trí cuộn
// ============================================

// Đảm bảo trang luôn bắt đầu từ đầu khi tải lại
if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}

// ============================================
// UTILITY FUNCTIONS - Performance Optimization
// ============================================

// Throttle function - Giới hạn tần suất gọi hàm
function throttle(func, limit) {
  let inThrottle;
  return function(...args) {
    if (!inThrottle) {
      func.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  };
}

// Debounce function - Trì hoãn thực thi đến khi dừng gọi
function debounce(func, delay) {
  let timeoutId;
  return function(...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func.apply(this, args), delay);
  };
}

// RequestAnimationFrame wrapper cho animations mượt hơn
function smoothAnimation(callback) {
  requestAnimationFrame(() => {
    requestAnimationFrame(callback);
  });
}

// ============================================
// CURSOR EFFECT - Optimized
// ============================================

// Tối ưu hiệu ứng cursor bằng cách giới hạn số lượng elements
const cursorEffectPool = [];
const MAX_CURSOR_EFFECTS = 10;
let cursorEffectIndex = 0;

// Khởi tạo pool of cursor effects
for (let i = 0; i < MAX_CURSOR_EFFECTS; i++) {
  const cursor = document.createElement("div");
  cursor.className = "cursor-effect";
  cursor.style.opacity = '0';
  cursor.style.pointerEvents = 'none';
  document.body.appendChild(cursor);
  cursorEffectPool.push(cursor);
}

// Throttled cursor effect
const handleCursorMove = throttle((e) => {
  const cursor = cursorEffectPool[cursorEffectIndex];
  
  smoothAnimation(() => {
    cursor.style.left = e.clientX + "px";
    cursor.style.top = e.clientY + "px";
    cursor.style.opacity = '1';
    cursor.style.transform = 'translate(-50%, -50%) scale(0)';
  });
  
  cursorEffectIndex = (cursorEffectIndex + 1) % MAX_CURSOR_EFFECTS;
  
  setTimeout(() => {
    cursor.style.opacity = '0';
  }, 600);
}, 50);

document.addEventListener("mousemove", handleCursorMove, { passive: true });

// ============================================
// SMOOTH SCROLL - Enhanced
// ============================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const href = this.getAttribute('href');
    if (href === '#') return;
    
    e.preventDefault();
    const target = document.querySelector(href);
    
    if (target) {
      const headerOffset = 80;
      const elementPosition = target.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  });
});

// ============================================
// INTERSECTION OBSERVER - Enhanced Performance
// ============================================

const observerOptions = {
  threshold: [0, 0.1, 0.2],
  rootMargin: '50px 0px -50px 0px'
};

const contentObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      smoothAnimation(() => {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      });
      
      contentObserver.unobserve(entry.target);
    }
  });
}, observerOptions);

// ============================================
// DOM READY INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  // ========== AUTO SCROLL TO TOP ON PAGE LOAD ==========
  // Tự động cuộn về đầu trang khi tải lại
  window.scrollTo({
    top: 0,
    left: 0,
    behavior: 'instant'
  });
  
  // ========== BOOK ITEMS ANIMATION ==========
  const allBookItems = document.querySelectorAll('.book-item');
  allBookItems.forEach((item, index) => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(30px)';
    item.style.transition = `all 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${(index % 6) * 0.08}s`;
    item.style.willChange = 'opacity, transform';
    contentObserver.observe(item);
  });

  // ========== BANNER BUTTON INTERACTION ==========
  const bannerBtn = document.querySelector('.banner-btn');
  if (bannerBtn) {
    bannerBtn.addEventListener('click', (e) => {
      e.preventDefault();
      smoothAnimation(() => {
        bannerBtn.style.transform = 'scale(0.95)';
      });
      
      setTimeout(() => {
        smoothAnimation(() => {
          bannerBtn.style.transform = 'scale(1)';
        });
      }, 150);
    });
  }

  // ========== LOGO INTERACTION ==========
  const logo = document.querySelector('.logo');
  if (logo) {
    logo.addEventListener('mouseenter', () => {
      smoothAnimation(() => {
        logo.style.color = '#ffdd00';
      });
    });
    
    logo.addEventListener('mouseleave', () => {
      smoothAnimation(() => {
        logo.style.color = '#00ff99';
      });
    });
  }

  // ========== BOOK ITEM HOVER ENHANCEMENT ==========
  allBookItems.forEach(item => {
    item.addEventListener('mouseenter', function() {
      smoothAnimation(() => {
        this.style.zIndex = '10';
      });
    });
    
    item.addEventListener('mouseleave', function() {
      setTimeout(() => {
        smoothAnimation(() => {
          this.style.zIndex = '1';
        });
      }, 300);
    });
  });
});

// ============================================
// SCROLL EFFECTS - Optimized with Throttle
// ============================================

const handleScroll = throttle(() => {
  const header = document.querySelector('header');
  const scrollY = window.pageYOffset;
  
  smoothAnimation(() => {
    if (scrollY > 50) {
      header.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.5)';
      header.style.backdropFilter = 'blur(10px)';
    } else {
      header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.3)';
      header.style.backdropFilter = 'none';
    }
  });
}, 100);

window.addEventListener('scroll', handleScroll, { passive: true });

// ============================================
// RIPPLE EFFECT - Enhanced
// ============================================

const createRipple = (e, element) => {
  const ripple = document.createElement('span');
  const rect = element.getBoundingClientRect();
  const size = Math.max(rect.width, rect.height);
  const x = e.clientX - rect.left - size / 2;
  const y = e.clientY - rect.top - size / 2;

  ripple.style.width = ripple.style.height = size + 'px';
  ripple.style.left = x + 'px';
  ripple.style.top = y + 'px';
  ripple.classList.add('ripple-effect');
  ripple.style.position = 'absolute';
  ripple.style.borderRadius = '50%';
  ripple.style.background = 'rgba(255, 255, 255, 0.5)';
  ripple.style.transform = 'scale(0)';
  ripple.style.animation = 'ripple-animation 0.6s ease-out';
  ripple.style.pointerEvents = 'none';

  element.style.position = 'relative';
  element.style.overflow = 'hidden';
  element.appendChild(ripple);

  setTimeout(() => {
    ripple.remove();
  }, 600);
};

document.querySelectorAll('.btn, .banner-btn, .auth-buttons .btn').forEach(button => {
  button.addEventListener('click', function(e) {
    createRipple(e, this);
  });
});

// ============================================
// SEARCH MODAL FUNCTIONALITY
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  const searchBtn = document.getElementById('searchBtn');
  const searchModal = document.getElementById('searchModal');
  const searchClose = document.getElementById('searchClose');
  const searchOverlay = document.getElementById('searchOverlay');
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');
  
  // Sample data
  const sampleBooks = [
    { title: 'Đắc Nhân Tâm', author: 'Dale Carnegie', category: 'Kỹ năng sống' },
    { title: 'Nhà Giả Kim', author: 'Paulo Coelho', category: 'Tiểu thuyết' },
    { title: 'Tuổi Trẻ Đáng Giá Bao Nhiêu', author: 'Rosie Nguyễn', category: 'Kỹ năng sống' },
    { title: 'Cà Phê Cùng Tony', author: 'Tony Buổi Sáng', category: 'Kinh doanh' },
    { title: 'Tư Duy Ngược', author: 'Nguyễn Anh Dũng', category: 'Tư duy' },
    { title: 'Muôn Kiếp Nhân Sinh', author: 'Nguyên Phong', category: 'Tâm linh' },
    { title: 'Chủ Nghĩa Tối Giản', author: 'Sasaki Fumio', category: 'Phong cách sống' },
    { title: 'Dọn Dẹp Tối Giản', author: 'Marie Kondo', category: 'Phong cách sống' },
  ];
  
  // Open modal
  const openSearchModal = () => {
    smoothAnimation(() => {
      searchModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
    
    setTimeout(() => {
      searchInput.focus();
    }, 300);
  };
  
  // Close modal
  const closeSearchModal = () => {
    smoothAnimation(() => {
      searchModal.classList.remove('active');
      document.body.style.overflow = '';
      searchInput.value = '';
      searchResults.innerHTML = '';
    });
  };
  
  // Search functionality
  const performSearch = debounce((query) => {
    if (!query.trim()) {
      searchResults.innerHTML = '';
      return;
    }
    
    const results = sampleBooks.filter(book => 
      book.title.toLowerCase().includes(query.toLowerCase()) ||
      book.author.toLowerCase().includes(query.toLowerCase()) ||
      book.category.toLowerCase().includes(query.toLowerCase())
    );
    
    if (results.length === 0) {
      searchResults.innerHTML = `
        <div style="text-align: center; padding: 40px; color: #888;">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 15px; opacity: 0.3;">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
            <line x1="11" y1="8" x2="11" y2="14"/>
            <line x1="8" y1="11" x2="14" y2="11"/>
          </svg>
          <p>Không tìm thấy kết quả cho "${query}"</p>
          <p style="font-size: 13px; margin-top: 10px;">Thử tìm kiếm với từ khóa khác</p>
        </div>
      `;
      return;
    }
    
    searchResults.innerHTML = results.map(book => `
      <div class="search-result-item">
        <div class="search-result-cover" style="background: linear-gradient(135deg, #${Math.floor(Math.random()*16777215).toString(16)}, #${Math.floor(Math.random()*16777215).toString(16)});"></div>
        <div class="search-result-info">
          <h4>${book.title}</h4>
          <p>${book.author} • ${book.category}</p>
        </div>
      </div>
    `).join('');
    
    document.querySelectorAll('.search-result-item').forEach(item => {
      item.addEventListener('click', function() {
        this.style.background = 'rgba(0, 255, 153, 0.1)';
        setTimeout(() => {
          closeSearchModal();
        }, 200);
      });
    });
  }, 300);
  
  // Event listeners
  if (searchBtn) {
    searchBtn.addEventListener('click', openSearchModal);
  }
  
  if (searchClose) {
    searchClose.addEventListener('click', closeSearchModal);
  }
  
  if (searchOverlay) {
    searchOverlay.addEventListener('click', closeSearchModal);
  }
  
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      performSearch(e.target.value);
    });
    
    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        const firstResult = document.querySelector('.search-result-item');
        if (firstResult) {
          firstResult.click();
        }
      }
    });
  }
  
  // Handle suggestion tags
  const suggestionTags = document.querySelectorAll('.suggestion-tag');
  suggestionTags.forEach(tag => {
    tag.addEventListener('click', function() {
      searchInput.value = this.textContent;
      performSearch(this.textContent);
      searchInput.focus();
    });
  });
  
  // Keyboard shortcuts
  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      if (searchModal.classList.contains('active')) {
        closeSearchModal();
      } else {
        openSearchModal();
      }
    }
    
    if (e.key === 'Escape' && searchModal.classList.contains('active')) {
      closeSearchModal();
    }
  });
});

// ============================================
// BANNER CAROUSEL FUNCTIONALITY
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.banner-slide');
  const dots = document.querySelectorAll('.banner-dot');
  const prevBtn = document.querySelector('.banner-prev');
  const nextBtn = document.querySelector('.banner-next');
  
  if (!slides.length) return;
  
  let currentSlide = 0;
  let autoPlayInterval;
  const autoPlayDelay = 5000;
  
  const showSlide = (index) => {
    if (index >= slides.length) {
      currentSlide = 0;
    } else if (index < 0) {
      currentSlide = slides.length - 1;
    } else {
      currentSlide = index;
    }
    
    slides.forEach((slide, i) => {
      if (i === currentSlide) {
        smoothAnimation(() => {
          slide.classList.add('active');
        });
      } else {
        smoothAnimation(() => {
          slide.classList.remove('active');
        });
      }
    });
    
    dots.forEach((dot, i) => {
      if (i === currentSlide) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });
  };
  
  const nextSlide = () => {
    showSlide(currentSlide + 1);
  };
  
  const prevSlide = () => {
    showSlide(currentSlide - 1);
  };
  
  const startAutoPlay = () => {
    stopAutoPlay();
    autoPlayInterval = setInterval(nextSlide, autoPlayDelay);
  };
  
  const stopAutoPlay = () => {
    if (autoPlayInterval) {
      clearInterval(autoPlayInterval);
    }
  };
  
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      stopAutoPlay();
      startAutoPlay();
    });
  }
  
  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      stopAutoPlay();
      startAutoPlay();
    });
  }
  
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      showSlide(index);
      stopAutoPlay();
      startAutoPlay();
    });
  });
  
  const bannerCarousel = document.querySelector('.banner-carousel');
  if (bannerCarousel) {
    bannerCarousel.addEventListener('mouseenter', stopAutoPlay);
    bannerCarousel.addEventListener('mouseleave', startAutoPlay);
  }
  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
      prevSlide();
      stopAutoPlay();
      startAutoPlay();
    } else if (e.key === 'ArrowRight') {
      nextSlide();
      stopAutoPlay();
      startAutoPlay();
    }
  });
  
  startAutoPlay();
  
  window.addEventListener('beforeunload', stopAutoPlay);
});

// ============================================
// RANKING TABS FUNCTIONALITY
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  const tabButtons = document.querySelectorAll('.tab-btn');
  
  if (!tabButtons.length) return;
  
  const handleTabClick = (clickedTab) => {
    tabButtons.forEach(tab => {
      smoothAnimation(() => {
        tab.classList.remove('active');
      });
    });
    
    smoothAnimation(() => {
      clickedTab.classList.add('active');
    });
    
    const ripple = document.createElement('span');
    ripple.style.position = 'absolute';
    ripple.style.borderRadius = '50%';
    ripple.style.background = 'rgba(255, 255, 255, 0.5)';
    ripple.style.width = '20px';
    ripple.style.height = '20px';
    ripple.style.transform = 'scale(0)';
    ripple.style.animation = 'ripple-animation 0.6s ease-out';
    ripple.style.pointerEvents = 'none';
    ripple.style.left = '50%';
    ripple.style.top = '50%';
    ripple.style.marginLeft = '-10px';
    ripple.style.marginTop = '-10px';
    
    clickedTab.appendChild(ripple);
    
    setTimeout(() => {
      ripple.remove();
    }, 600);
  };
  
  tabButtons.forEach(tab => {
    tab.addEventListener('click', function() {
      handleTabClick(this);
    });
    
    tab.addEventListener('mouseenter', function() {
      if (!this.classList.contains('active')) {
        smoothAnimation(() => {
          this.style.background = 'rgba(255, 255, 255, 0.08)';
        });
      }
    });
    
    tab.addEventListener('mouseleave', function() {
      if (!this.classList.contains('active')) {
        smoothAnimation(() => {
          this.style.background = 'transparent';
        });
      }
    });
  });
  
  const tabsContainer = document.querySelector('.tabs-container');
  if (tabsContainer) {
    tabsContainer.addEventListener('keydown', (e) => {
      const currentTab = document.querySelector('.tab-btn.active');
      const currentIndex = Array.from(tabButtons).indexOf(currentTab);
      
      if (e.key === 'ArrowLeft') {
        e.preventDefault();
        const prevIndex = currentIndex > 0 ? currentIndex - 1 : tabButtons.length - 1;
        handleTabClick(tabButtons[prevIndex]);
        tabButtons[prevIndex].focus();
      } else if (e.key === 'ArrowRight') {
        e.preventDefault();
        const nextIndex = currentIndex < tabButtons.length - 1 ? currentIndex + 1 : 0;
        handleTabClick(tabButtons[nextIndex]);
        tabButtons[nextIndex].focus();
      }
    });
    
    tabButtons.forEach(tab => {
      tab.setAttribute('tabindex', '0');
      tab.setAttribute('role', 'tab');
    });
  }
});

// ============================================
// BOOK CAROUSEL NAVIGATION
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  const initializeBookCarousels = () => {
    const bookSections = document.querySelectorAll('.books');
    
    bookSections.forEach((section) => {
      const bookList = section.querySelector('.book-list');
      if (!bookList) return;
      
      if (!bookList.parentElement.classList.contains('book-carousel-wrapper')) {
        const wrapper = document.createElement('div');
        wrapper.className = 'book-carousel-wrapper';
        bookList.parentNode.insertBefore(wrapper, bookList);
        wrapper.appendChild(bookList);
        
        const prevBtn = document.createElement('button');
        prevBtn.className = 'book-nav book-nav-prev';
        prevBtn.setAttribute('aria-label', 'Previous books');
        prevBtn.innerHTML = `
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        `;
        
        const nextBtn = document.createElement('button');
        nextBtn.className = 'book-nav book-nav-next';
        nextBtn.setAttribute('aria-label', 'Next books');
        nextBtn.innerHTML = `
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        `;
        
        wrapper.appendChild(prevBtn);
        wrapper.appendChild(nextBtn);
        
        const scrollAmount = 600;
        
        const updateButtonVisibility = () => {
          const scrollLeft = bookList.scrollLeft;
          const maxScroll = bookList.scrollWidth - bookList.clientWidth;
          
          if (scrollLeft <= 10) {
            prevBtn.style.opacity = '0';
            prevBtn.style.pointerEvents = 'none';
          } else if (wrapper.matches(':hover')) {
            prevBtn.style.opacity = '1';
            prevBtn.style.pointerEvents = 'all';
          }
          
          if (scrollLeft >= maxScroll - 10) {
            nextBtn.style.opacity = '0';
            nextBtn.style.pointerEvents = 'none';
          } else if (wrapper.matches(':hover')) {
            nextBtn.style.opacity = '1';
            nextBtn.style.pointerEvents = 'all';
          }
        };
        
        prevBtn.addEventListener('click', () => {
          smoothAnimation(() => {
            bookList.scrollBy({
              left: -scrollAmount,
              behavior: 'smooth'
            });
          });
          
          createRipple(event, prevBtn);
        });
        
        nextBtn.addEventListener('click', () => {
          smoothAnimation(() => {
            bookList.scrollBy({
              left: scrollAmount,
              behavior: 'smooth'
            });
          });
          
          createRipple(event, nextBtn);
        });
        
        bookList.addEventListener('scroll', throttle(updateButtonVisibility, 100));
        
        wrapper.addEventListener('mouseenter', updateButtonVisibility);
        
        setTimeout(updateButtonVisibility, 100);
        
        window.addEventListener('resize', throttle(updateButtonVisibility, 200));
      }
    });
  };
  
  initializeBookCarousels();
  
  // Touch/Swipe support
  const addTouchSupport = () => {
    const bookLists = document.querySelectorAll('.book-list');
    
    bookLists.forEach(bookList => {
      let isDown = false;
      let startX;
      let scrollLeft;
      
      bookList.addEventListener('mousedown', (e) => {
        if (e.target.closest('.book-item')) return;
        
        isDown = true;
        bookList.style.cursor = 'grabbing';
        startX = e.pageX - bookList.offsetLeft;
        scrollLeft = bookList.scrollLeft;
      });
      
      bookList.addEventListener('mouseleave', () => {
        isDown = false;
        bookList.style.cursor = 'grab';
      });
      
      bookList.addEventListener('mouseup', () => {
        isDown = false;
        bookList.style.cursor = 'grab';
      });
      
      bookList.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - bookList.offsetLeft;
        const walk = (x - startX) * 2;
        bookList.scrollLeft = scrollLeft - walk;
      });
      
      let touchStartX = 0;
      let touchScrollLeft = 0;
      
      bookList.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].pageX;
        touchScrollLeft = bookList.scrollLeft;
      }, { passive: true });
      
      bookList.addEventListener('touchmove', (e) => {
        const touchX = e.touches[0].pageX;
        const walk = (touchStartX - touchX) * 1.5;
        bookList.scrollLeft = touchScrollLeft + walk;
      }, { passive: true });
    });
  };
  
  addTouchSupport();
});

// ============================================
// BOOK DETAIL MODAL FUNCTIONALITY
// ============================================

let modalHoverInitialized = false;

// Function to initialize modal hover
function initializeBookModalHover() {
  // Prevent multiple initializations
  if (modalHoverInitialized) {
    return;
  }
  
  const bookDetailModal = document.getElementById('bookDetailModal');
  const bookDetailClose = document.querySelector('.book-detail-close');
  const bookDetailOverlay = document.querySelector('.book-detail-overlay');
  
  if (!bookDetailModal) {
    console.warn('BookDetailModal not found in DOM');
    return;
  }
  
  console.log('BookDetailModal found, initializing hover functionality...');
  modalHoverInitialized = true;
  
  let hoverTimeout = null;
  let isHoveringModal = false;
  let currentModalBookData = null;
  
  const openBookDetailModal = (bookData) => {
    console.log('Opening modal for:', bookData.title);
    
    // Don't reopen if same book is already open
    if (currentModalBookData && currentModalBookData.element === bookData.element && bookDetailModal.classList.contains('active')) {
      return;
    }
    
    currentModalBookData = bookData;
    
    const modalCover = document.getElementById('modalBookCover');
    const modalTitle = document.getElementById('modalBookTitle');
    const modalAuthor = document.getElementById('modalBookAuthor');
    const modalGenre = document.getElementById('modalBookGenre');
    const modalRating = document.getElementById('modalBookRating');
    const modalYear = document.getElementById('modalBookYear');
    const modalDescription = document.getElementById('modalBookDescription');
    const modalBadge = document.getElementById('modalBookBadge');
    const modalBookId = document.getElementById('modalBookId');
    
    const bookCover = bookData.element.querySelector('.book-cover');
    if (bookCover && modalCover) {
      modalCover.innerHTML = bookCover.innerHTML;
    }
    
    if (modalTitle) modalTitle.textContent = bookData.title;
    if (modalAuthor) modalAuthor.textContent = bookData.author;
    if (modalGenre) modalGenre.textContent = bookData.genre;
    if (modalRating) modalRating.textContent = bookData.rating;
    if (modalYear) modalYear.textContent = bookData.year;
    if (modalDescription) modalDescription.textContent = bookData.description;
    if (modalBookId) modalBookId.value = bookData.id || '';
    
    if (modalBadge) {
      if (bookData.premium === 'true') {
        modalBadge.style.display = 'inline-flex';
      } else {
        modalBadge.style.display = 'none';
      }
    }
    
    // Show modal immediately
    bookDetailModal.classList.add('active');
    console.log('Modal should be visible now');
  };
  
  const closeBookDetailModal = () => {
    bookDetailModal.classList.remove('active');
    currentModalBookData = null;
  };
  
  const bookItems = document.querySelectorAll('.book-item');
  console.log(`Found ${bookItems.length} book items`);
  
  if (bookItems.length === 0) {
    console.warn('No book items found! Make sure .book-item elements exist in the DOM.');
  }
  
  bookItems.forEach((book, index) => {
    const bookData = {
      element: book,
      id: book.dataset.bookId,
      title: book.dataset.bookTitle,
      author: book.dataset.bookAuthor,
      genre: book.dataset.bookGenre,
      rating: book.dataset.bookRating,
      year: book.dataset.bookYear,
      description: book.dataset.bookDescription,
      premium: book.dataset.bookPremium
    };
    
    console.log(`Book ${index + 1}:`, bookData.title || 'No title', bookData);
    
    if (bookData.title) {
      book.style.cursor = 'pointer';
      
      // Open modal on hover
      book.addEventListener('mouseenter', function(e) {
        console.log('Mouse entered book:', bookData.title);
        // Clear any pending close timeout
        if (hoverTimeout) {
          clearTimeout(hoverTimeout);
          hoverTimeout = null;
        }
        isHoveringModal = false;
        openBookDetailModal(bookData);
      });
      
      // Close modal when mouse leaves book item (with delay)
      book.addEventListener('mouseleave', function(e) {
        console.log('Mouse left book:', bookData.title);
        // Check if mouse is moving to modal
        const relatedTarget = e.relatedTarget;
        const modal = document.getElementById('bookDetailModal');
        if (relatedTarget && modal && (modal.contains(relatedTarget) || relatedTarget === modal)) {
          isHoveringModal = true;
          return;
        }
        
        if (!isHoveringModal) {
          hoverTimeout = setTimeout(() => {
            if (!isHoveringModal) {
              closeBookDetailModal();
            }
          }, 300); // Delay to allow moving to modal
        }
      });
    } else {
      console.warn(`Book item ${index + 1} has no title data attribute`);
    }
  });
  
  console.log('Hover event listeners attached to book items');
  
  // Keep modal open when hovering over container
  const bookDetailContainer = document.querySelector('.book-detail-container');
  if (bookDetailContainer) {
    bookDetailContainer.addEventListener('mouseenter', () => {
      isHoveringModal = true;
      if (hoverTimeout) {
        clearTimeout(hoverTimeout);
        hoverTimeout = null;
      }
    });
    
    bookDetailContainer.addEventListener('mouseleave', () => {
      isHoveringModal = false;
      hoverTimeout = setTimeout(() => {
        closeBookDetailModal();
      }, 300);
    });
    
    bookDetailContainer.addEventListener('click', (e) => {
      e.stopPropagation();
    });
  }
  
  if (bookDetailClose) {
    bookDetailClose.addEventListener('click', closeBookDetailModal);
  }
  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && bookDetailModal.classList.contains('active')) {
      closeBookDetailModal();
    }
  });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initializeBookModalHover, 100);
  });
} else {
  // DOM already loaded
  setTimeout(initializeBookModalHover, 100);
}

// Also try after a short delay to catch dynamically loaded content
setTimeout(initializeBookModalHover, 500);

// ============================================
// PERFORMANCE MONITORING
// ============================================

if (window.performance && window.performance.timing) {
  window.addEventListener('load', () => {
    setTimeout(() => {
      const perfData = window.performance.timing;
      const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
      console.log(`✅ Trang tải xong trong: ${pageLoadTime}ms`);
    }, 0);
  });
}

// Cleanup function
window.addEventListener('beforeunload', () => {
  if (contentObserver) contentObserver.disconnect();
});

