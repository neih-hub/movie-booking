document.addEventListener("DOMContentLoaded", function () { 
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');
  
  tabBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const tabId = this.dataset.tab;
      
      tabBtns.forEach(b => b.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));
      this.classList.add('active');
      document.getElementById(tabId).classList.add('active');

      resetLoadMore(tabId);
    });
  });
  
  
  let visibleCount = {};
  
  function initLoadMore(tabId) {
    const tab = document.getElementById(tabId);
    if (!tab) return;
    
    const items = tab.querySelectorAll('.movie-item');
    const loadMoreBtn = tab.querySelector('.btn-load-more');
    
    if (items.length === 0) return;
    visibleCount[tabId] = 10;
  
    items.forEach((item, index) => {
      if (index < visibleCount[tabId]) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
    
    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', function() {
        visibleCount[tabId] += 5;
        
        items.forEach((item, index) => {
          if (index < visibleCount[tabId]) {
            item.style.display = 'block';
          }
        });
        
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
    
    visibleCount[tabId] = 10;
    
    items.forEach((item, index) => {
      if (index < visibleCount[tabId]) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });

    if (loadMoreBtn && items.length > 10) {
      loadMoreBtn.style.display = 'block';
    }
  }
  ['now-showing', 'coming-soon'].forEach(initLoadMore);
  
  console.log('Movie tabs initialized');
  const blogTabBtns = document.querySelectorAll('.blog-tab-btn');
  const blogTabContents = document.querySelectorAll('.blog-tab-content');
  
  blogTabBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const tabId = 'blog-' + this.dataset.blogTab;
      blogTabBtns.forEach(b => b.classList.remove('active'));
      blogTabContents.forEach(c => c.classList.remove('active'));
      this.classList.add('active');
      const targetContent = document.getElementById(tabId);
      if (targetContent) {
        targetContent.classList.add('active');
      }
    });
  });
  
  console.log('Blog tabs initialized');
});
