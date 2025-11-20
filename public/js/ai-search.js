/**
 * AI Search Autocomplete
 * Gợi ý tìm kiếm thông minh với AI
 */

(function() {
    'use strict';
    
    // Debounce function để tránh gọi API quá nhiều
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Khởi tạo AI search autocomplete
    function initAiSearch() {
        const searchInputs = document.querySelectorAll('.search-input, input[name="keyword"]');
        
        searchInputs.forEach(input => {
            // Tạo container cho suggestions
            const suggestionsContainer = document.createElement('div');
            suggestionsContainer.className = 'ai-search-suggestions';
            suggestionsContainer.style.cssText = `
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #ddd;
                border-top: none;
                border-radius: 0 0 8px 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                max-height: 400px;
                overflow-y: auto;
                z-index: 1000;
                display: none;
            `;
            
            // Đảm bảo parent có position relative
            const parent = input.closest('form') || input.parentElement;
            if (parent) {
                parent.style.position = 'relative';
                parent.appendChild(suggestionsContainer);
            }
            
            // Xử lý input với debounce
            const handleInput = debounce(async function(e) {
                const query = e.target.value.trim();
                
                if (query.length < 2) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                
                try {
                    const response = await fetch(`/api/search/suggest?q=${encodeURIComponent(query)}`);
                    const data = await response.json();
                    
                    if (data.suggestions && data.suggestions.length > 0) {
                        renderSuggestions(data.suggestions, suggestionsContainer, input);
                        suggestionsContainer.style.display = 'block';
                    } else {
                        suggestionsContainer.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error fetching suggestions:', error);
                }
            }, 300);
            
            input.addEventListener('input', handleInput);
            
            // Ẩn suggestions khi click bên ngoài
            document.addEventListener('click', function(e) {
                if (!parent.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });
        });
    }
    
    // Render suggestions
    function renderSuggestions(suggestions, container, input) {
        container.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('a');
            item.href = suggestion.url;
            item.className = 'ai-search-suggestion-item';
            item.style.cssText = `
                display: flex;
                align-items: center;
                padding: 12px 15px;
                text-decoration: none;
                color: #333;
                border-bottom: 1px solid #f0f0f0;
                transition: background-color 0.2s;
                gap: 12px;
            `;
            
            // Icon dựa trên type
            const icon = document.createElement('span');
            icon.style.cssText = 'font-size: 20px; flex-shrink: 0;';
            switch(suggestion.type) {
                case 'book':
                    icon.textContent = '📚';
                    break;
                case 'author':
                    icon.textContent = '✍️';
                    break;
                case 'category':
                    icon.textContent = '📂';
                    break;
                case 'keyword':
                    icon.textContent = '🔍';
                    break;
                default:
                    icon.textContent = '📖';
            }
            item.appendChild(icon);
            
            // Image nếu có
            if (suggestion.image) {
                const img = document.createElement('img');
                img.src = suggestion.image;
                img.alt = suggestion.title;
                img.style.cssText = 'width: 40px; height: 40px; object-fit: cover; border-radius: 4px; flex-shrink: 0;';
                item.appendChild(img);
            }
            
            // Text content
            const textContainer = document.createElement('div');
            textContainer.style.cssText = 'flex: 1; min-width: 0;';
            
            const title = document.createElement('div');
            title.textContent = suggestion.title;
            title.style.cssText = 'font-weight: 500; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;';
            textContainer.appendChild(title);
            
            if (suggestion.subtitle) {
                const subtitle = document.createElement('div');
                subtitle.textContent = suggestion.subtitle;
                subtitle.style.cssText = 'font-size: 12px; color: #666; margin-top: 2px;';
                textContainer.appendChild(subtitle);
            }
            
            item.appendChild(textContainer);
            
            // Hover effect
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f5f5f5';
            });
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'white';
            });
            
            container.appendChild(item);
        });
    }
    
    // Khởi tạo khi DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAiSearch);
    } else {
        initAiSearch();
    }
})();

