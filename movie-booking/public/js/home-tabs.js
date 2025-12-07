document.addEventListener("DOMContentLoaded", function () {
  
  // =====================
  // TAB SWITCHING
  // =====================
  
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');
  
  tabBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const tabId = this.dataset.tab;
      
      // Remove active from all tabs and contents
      tabBtns.forEach(b => b.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));
      
      // Add active to clicked tab and corresponding content
      this.classList.add('active');
      document.getElementById(tabId).classList.add('active');
      
      // Reset load more for this tab
      resetLoadMore(tabId);
    });
  });
  
  // =====================
  // LOAD MORE FUNCTIONALITY
  // =====================
  
  let visibleCount = {};
  
  function initLoadMore(tabId) {
    const tab = document.getElementById(tabId);
    if (!tab) return;
    
    const items = tab.querySelectorAll('.movie-item');
    const loadMoreBtn = tab.querySelector('.btn-load-more');
    
    if (items.length === 0) return;
    
    // Initialize visible count
    visibleCount[tabId] = 10;
    
    // Hide items beyond initial count
    items.forEach((item, index) => {
      if (index < visibleCount[tabId]) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
    
    // Load more button click
    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', function() {
        visibleCount[tabId] += 5; // Load 5 more items
        
        items.forEach((item, index) => {
          if (index < visibleCount[tabId]) {
            item.style.display = 'block';
          }
        });
        
        // Hide button if all items are shown
        if (visibleCount[tabId] >= items.length) {
          loadMoreBtn.style.display = 'none';
        }
      });
    }
  }
  
  function resetLoadMore(tabId) {
    const tab = document.getElementById(tabId);
    if (!tab) return;
    
    const items = tab.querySelectorAll('.movie-item');
    const loadMoreBtn = tab.querySelector('.btn-load-more');
    
    if (items.length === 0) return;
    
    // Reset visible count
    visibleCount[tabId] = 10;
    
    // Reset visibility
    items.forEach((item, index) => {
      if (index < visibleCount[tabId]) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
    
    // Show load more button if there are more items
    if (loadMoreBtn && items.length > 10) {
      loadMoreBtn.style.display = 'block';
    }
  }
  
  // Initialize all tabs
  ['now-showing', 'coming-soon'].forEach(initLoadMore);
  
  console.log('Movie tabs initialized');
  
  // =====================
  // BLOG TABS SWITCHING
  // =====================
  
  const blogTabBtns = document.querySelectorAll('.blog-tab-btn');
  const blogTabContents = document.querySelectorAll('.blog-tab-content');
  
  blogTabBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const tabId = 'blog-' + this.dataset.blogTab;
      
      // Remove active from all blog tabs and contents
      blogTabBtns.forEach(b => b.classList.remove('active'));
      blogTabContents.forEach(c => c.classList.remove('active'));
      
      // Add active to clicked tab and corresponding content
      this.classList.add('active');
      const targetContent = document.getElementById(tabId);
      if (targetContent) {
        targetContent.classList.add('active');
      }
    });
  });
  
  console.log('Blog tabs initialized');
});
