/**
 * Setup improved airport autocomplete functionality with focus flow between "from" and "to" fields
 * @param {string} fromInputId - The ID of the origin input field
 * @param {string} toInputId - The ID of the destination input field
 * @param {string} fromSuggestionsId - The ID of the origin suggestions container
 * @param {string} toSuggestionsId - The ID of the destination suggestions container
 * @param {string} fromHiddenId - The ID of the origin hidden field for IATA code
 * @param {string} toHiddenId - The ID of the destination hidden field for IATA code
 */
/**
 * Cache object to store airport search results
 */
const airportSearchCache = {
    results: {},
    timestamp: {},
    cacheDuration: 60000, // Cache valid for 1 minute
    
    // Get cached results if available and not expired
    get: function(query) {
        const normalizedQuery = query.toLowerCase().trim();
        const now = Date.now();
        
        if (this.results[normalizedQuery] && 
            (now - this.timestamp[normalizedQuery] < this.cacheDuration)) {
            return this.results[normalizedQuery];
        }
        
        return null;
    },
    
    // Store results in cache
    set: function(query, data) {
        const normalizedQuery = query.toLowerCase().trim();
        this.results[normalizedQuery] = data;
        this.timestamp[normalizedQuery] = Date.now();
    }
};

/**
 * Shared airport search function to avoid duplicate API calls
 * @param {string} query - The search query
 * @returns {Promise} - Promise resolving to airport search results
 */
function fetchAirportResults(query) {
    // Check cache first
    const cachedResults = airportSearchCache.get(query);
    if (cachedResults) {
        return Promise.resolve(cachedResults);
    }
    
    // Make API call if no cache hit
    return fetch(`/api/airports/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            // Store in cache for future use
            airportSearchCache.set(query, data);
            return data;
        });
}

function setupImprovedAirportAutocomplete(fromInputId, toInputId, fromSuggestionsId, toSuggestionsId, fromHiddenId, toHiddenId) {
    const fromInput = document.getElementById(fromInputId);
    const toInput = document.getElementById(toInputId);
    const fromSuggestions = document.getElementById(fromSuggestionsId);
    const toSuggestions = document.getElementById(toSuggestionsId);
    const fromHidden = document.getElementById(fromHiddenId);
    const toHidden = document.getElementById(toHiddenId);
    
    if (!fromInput || !toInput || !fromSuggestions || !toSuggestions) return;
    
    // Variables for debounce
    let fromDebounceTimeout;
    let toDebounceTimeout;
    const debounceDelay = 200; // Reduced from 250ms to 200ms for faster response
    
    // Track field selection status
    let fromFieldSelected = false;
    let toFieldSelected = false;
    
    // FROM Input handling
    fromInput.addEventListener('input', function() {
        // Reset selection status when user starts typing again
        if (fromFieldSelected) {
            fromFieldSelected = false;
            fromHidden.value = '';
        }
        
        clearTimeout(fromDebounceTimeout);
        const query = this.value.trim();
        
        // Clear suggestions if query is too short - changed to require at least 3 characters
        if (query.length < 3) {
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
            return;
        }
        
        fromDebounceTimeout = setTimeout(() => {
            // Hide destination suggestions while searching from field
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
            
            // Use shared fetch function
            fetchAirportResults(query)
                .then(data => {
                    renderEnhancedSuggestions(data, fromInput, fromSuggestions, fromHidden);
                })
                .catch(error => {
                    console.error('Error fetching airport suggestions:', error);
                    fromSuggestions.innerHTML = '<div class="text-danger p-2">Error fetching suggestions</div>';
                });
        }, debounceDelay);
    });
    
    // TO Input handling
    toInput.addEventListener('input', function() {
        // Reset selection status when user starts typing again
        if (toFieldSelected) {
            toFieldSelected = false;
            toHidden.value = '';
        }
        
        clearTimeout(toDebounceTimeout);
        const query = this.value.trim();
        
        // Clear suggestions if query is too short - changed to require at least 3 characters
        if (query.length < 3) {
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
            return;
        }
        
        toDebounceTimeout = setTimeout(() => {
            // Hide origin suggestions while searching destination field
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
            
            // Use shared fetch function
            fetchAirportResults(query)
                .then(data => {
                    renderEnhancedSuggestions(data, toInput, toSuggestions, toHidden);
                })
                .catch(error => {
                    console.error('Error fetching airport suggestions:', error);
                    toSuggestions.innerHTML = '<div class="text-danger p-2">Error fetching suggestions</div>';
                });
        }, debounceDelay);
    });
    
    // Custom selection handler for FROM field
    function selectFromAirport(iataCode, displayName) {
        fromInput.value = displayName;
        fromHidden.value = iataCode;
        fromFieldSelected = true;
        
        // Add visual feedback with smooth transition
        fromInput.style.transition = 'all 0.2s ease';
        fromInput.style.backgroundColor = 'rgba(6, 182, 212, 0.1)';
        
        // Return to normal after a short delay
        setTimeout(() => {
            fromInput.style.backgroundColor = '';
        }, 500);
        
        // Clear suggestions
        fromSuggestions.innerHTML = '';
        fromSuggestions.classList.remove('p-2');
        
        // Focus destination field if empty
        if (!toInput.value) {
            setTimeout(() => {
                toInput.focus();
                
                // Show message to type at least 3 characters for destination
                if (toInput.value.trim() === '') {
                    toSuggestions.innerHTML = `<div class="text-center text-muted p-2">
                        <i class="fas fa-info-circle me-2"></i>
                        Type at least 3 characters to see airport suggestions
                    </div>`;
                    toSuggestions.classList.add('p-2');
                }
            }, 150);
        }
        
        // Trigger change event for any listeners
        fromInput.dispatchEvent(new Event('change'));
    }
    
    // Custom selection handler for TO field
    function selectToAirport(iataCode, displayName) {
        toInput.value = displayName;
        toHidden.value = iataCode;
        toFieldSelected = true;
        
        // Add visual feedback with smooth transition
        toInput.style.transition = 'all 0.2s ease';
        toInput.style.backgroundColor = 'rgba(6, 182, 212, 0.1)';
        
        // Return to normal after a short delay
        setTimeout(() => {
            toInput.style.backgroundColor = '';
        }, 500);
        
        // Clear suggestions
        toSuggestions.innerHTML = '';
        toSuggestions.classList.remove('p-2');
        
        // Trigger change event for any listeners
        toInput.dispatchEvent(new Event('change'));
    }
    
    // Show typing hint when focusing on FROM input
    fromInput.addEventListener('focus', function() {
        // Only show hint if this field isn't already selected
        if (!fromFieldSelected && this.value.trim() === '') {
            // Hide destination suggestions
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
            
            // Show typing hint
            fromSuggestions.innerHTML = `<div class="text-center text-muted p-2">
                <i class="fas fa-info-circle me-2"></i>
                Type at least 3 characters to see airport suggestions
            </div>`;
            fromSuggestions.classList.add('p-2');
        }
    });
    
    // Show typing hint when focusing on TO input
    toInput.addEventListener('focus', function() {
        // Only show hint if this field isn't already selected
        if (!toFieldSelected && this.value.trim() === '') {
            // Hide origin suggestions
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
            
            // Show typing hint
            toSuggestions.innerHTML = `<div class="text-center text-muted p-2">
                <i class="fas fa-info-circle me-2"></i>
                Type at least 3 characters to see airport suggestions
            </div>`;
            toSuggestions.classList.add('p-2');
        }
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== fromInput && !fromSuggestions.contains(e.target)) {
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
        }
        
        if (e.target !== toInput && !toSuggestions.contains(e.target)) {
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
        }
    });
}

/**
 * Render enhanced airport suggestions with icons and better styling
 * @param {Array} data - The airport data to render
 * @param {HTMLElement} inputField - The input field
 * @param {HTMLElement} container - The suggestions container
 * @param {HTMLElement} hiddenField - The hidden field for IATA code
 * @param {string} [title] - Optional title for the suggestions list
 * @param {Function} [customSelectHandler] - Optional custom selection handler
 */
function renderEnhancedSuggestions(data, inputField, container, hiddenField, title = null, customSelectHandler = null) {
    if (!data || data.length === 0) {
        container.innerHTML = `<div class="text-center text-muted p-2">
            <i class="fas fa-exclamation-circle me-2"></i>
            No airports found
        </div>`;
        container.classList.add('p-2');
        return;
    }
    
    let html = `<div class="suggestions-table">`;
    
    // Add title if provided
    if (title) {
        html += `<div class="suggestions-title text-muted small mb-2">
            <i class="fas fa-star text-warning me-1"></i> ${title}
        </div>`;
    }
    
    html += `<table class="table table-sm table-hover mb-0">
        <thead>
            <tr class="bg-light">
                <th class="small text-muted py-1 px-2"><i class="fas fa-plane-departure text-primary me-1"></i>Code</th>
                <th class="small text-muted py-1 px-2"><i class="fas fa-building text-primary me-1"></i>Airport</th>
                <th class="small text-muted py-1 px-2"><i class="fas fa-map-marker-alt text-primary me-1"></i>Location</th>
            </tr>
        </thead>
        <tbody>`;
    
    data.forEach(airport => {
        html += `<tr class="suggestion-item cursor-pointer" 
                    data-iata="${airport.iata_code}" 
                    data-name="${airport.display_name || airport.name + ' (' + airport.iata_code + ')'}">
                    <td class="fw-bold text-primary small py-1 px-2">${airport.iata_code}</td>
                    <td class="small py-1 px-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">${airport.name}</div>
                            ${airport.international ? '<span class="ms-1 badge bg-info text-white" style="font-size: 0.6rem;">INTL</span>' : ''}
                        </div>
                    </td>
                    <td class="small text-muted py-1 px-2">${airport.city}, ${airport.country}</td>
                </tr>`;
    });
    
    html += `</tbody></table>
        <div class="text-end mt-1 px-2 py-1 bg-light border-top">
            <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Click to select airport</small>
        </div>
    </div>`;
    
    container.innerHTML = html;
    container.classList.add('p-2');
    
    // Add click event listeners to suggestion items
    container.querySelectorAll('.suggestion-item').forEach(item => {
        item.addEventListener('click', function() {
            const iataCode = this.getAttribute('data-iata');
            const displayName = this.getAttribute('data-name');
            
            // Use custom handler if provided
            if (customSelectHandler) {
                customSelectHandler(iataCode, displayName);
                return;
            }
            
            // Default behavior
            inputField.value = displayName;
            if (hiddenField) hiddenField.value = iataCode;
            
            // Add visual feedback with smooth transition
            inputField.style.transition = 'all 0.2s ease';
            inputField.style.backgroundColor = 'rgba(6, 182, 212, 0.1)';
            
            // Return to normal after a short delay
            setTimeout(() => {
                inputField.style.backgroundColor = '';
            }, 500);
            
            container.innerHTML = '';
            container.classList.remove('p-2');
            
            // Trigger change event for any listeners
            inputField.dispatchEvent(new Event('change'));
        });
    });
    
    // Add touch support for mobile
    container.querySelectorAll('.suggestion-item').forEach(item => {
        item.addEventListener('touchstart', function(e) {
            e.preventDefault();
            this.classList.add('bg-light');
        });
        
        item.addEventListener('touchend', function(e) {
            e.preventDefault();
            const iataCode = this.getAttribute('data-iata');
            const displayName = this.getAttribute('data-name');
            
            // Use custom handler if provided
            if (customSelectHandler) {
                customSelectHandler(iataCode, displayName);
                return;
            }
            
            // Default behavior
            inputField.value = displayName;
            if (hiddenField) hiddenField.value = iataCode;
            
            container.innerHTML = '';
            container.classList.remove('p-2');
            
            // Trigger change event for any listeners
            inputField.dispatchEvent(new Event('change'));
        });
    });
}

/**
 * Add tooltip to explain the flight search process
 */
function addFlightSearchTooltip() {
    const searchContainer = document.querySelector('.container[style*="min-height: 60vh"]');
    
    if (!searchContainer) return;
    
    // Create the help tooltip button
    const helpButton = document.createElement('button');
    helpButton.className = 'btn btn-sm btn-outline-warning rounded-circle position-absolute';
    helpButton.style.top = '6.5rem';
    helpButton.style.right = '1.5rem';
    helpButton.innerHTML = '<i class="fas fa-question"></i>';
    helpButton.setAttribute('data-bs-toggle', 'tooltip');
    helpButton.setAttribute('data-bs-placement', 'left');
    helpButton.setAttribute('title', 'How to search for flights');
    helpButton.onclick = showSearchHelp;
    
    searchContainer.style.position = 'relative';
    searchContainer.appendChild(helpButton);
    
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

/**
 * Show help modal with search instructions
 */
function showSearchHelp() {
    // Create modal if it doesn't exist yet
    let helpModal = document.getElementById('searchHelpModal');
    
    if (!helpModal) {
        helpModal = document.createElement('div');
        helpModal.className = 'modal fade';
        helpModal.id = 'searchHelpModal';
        helpModal.tabIndex = -1;
        helpModal.setAttribute('aria-labelledby', 'searchHelpModalLabel');
        helpModal.setAttribute('aria-hidden', 'true');
        
        helpModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="searchHelpModalLabel">
                            <i class="fas fa-search me-2"></i>How to Search Flights
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2"><i class="fas fa-plane-departure text-primary me-2"></i>1. Select departure airport</h6>
                            <p class="text-muted ms-4 mb-0">Type at least 3 characters and select from the suggestions.</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2"><i class="fas fa-plane-arrival text-primary me-2"></i>2. Select destination airport</h6>
                            <p class="text-muted ms-4 mb-0">Type at least 3 characters and select from the suggestions.</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2"><i class="fas fa-calendar-alt text-primary me-2"></i>3. Choose travel dates</h6>
                            <p class="text-muted ms-4 mb-0">Select departure and return dates (for round trips).</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2"><i class="fas fa-users text-primary me-2"></i>4. Passenger information</h6>
                            <p class="text-muted ms-4 mb-0">Specify number of adults, children, and infants.</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2"><i class="fas fa-star text-primary me-2"></i>5. Select travel class</h6>
                            <p class="text-muted ms-4 mb-0">Choose from Economy, Premium Economy, Business, or First Class.</p>
                        </div>
                        
                        <div>
                            <h6 class="fw-bold mb-2"><i class="fas fa-search text-primary me-2"></i>6. Search</h6>
                            <p class="text-muted ms-4 mb-0">Click the "Search" button to find available flights.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(helpModal);
    }
    
    // Show the modal
    if (typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(helpModal);
        modal.show();
    }
}

/**
 * Enhanced form submission with loading states and notifications
 */
function setupEnhancedFormSubmissions() {
    const searchForms = document.querySelectorAll('form[action="/api/search"]');
    
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const fromIataField = form.querySelector('input[name="from_iata"], input[name="from_iata[]"]');
            const toIataField = form.querySelector('input[name="to_iata"], input[name="to_iata[]"]');
            
            // Validate required fields
            if (!validateFlightForm(form, fromIataField, toIataField)) {
                return;
            }
            
            // Start the enhanced flight search process
            startFlightSearch(form);
        });
    });
}

/**
 * Validate flight search form
 */
function validateFlightForm(form, fromIataField, toIataField) {
    // Check IATA codes
    if (!fromIataField?.value || !toIataField?.value) {
        showErrorToast('Please select valid airports from the suggestions');
        
        const errorMessage = document.createElement('div');
        errorMessage.className = 'alert alert-danger mt-3 fade-in-alert';
        errorMessage.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Please select valid airports from the suggestions';
        
        // Remove any existing error messages
        const existingError = form.querySelector('.alert-danger');
        if (existingError) existingError.remove();
        
        form.prepend(errorMessage);
        
        // Scroll to the error message
        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Remove the error message after 5 seconds
        setTimeout(() => {
            errorMessage.classList.add('fade-out');
            setTimeout(() => errorMessage.remove(), 500);
        }, 5000);
        
        return false;
    }
    
    // Check dates
    const dateFields = form.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    
    for (let dateField of dateFields) {
        if (dateField.required && (!dateField.value || dateField.value < today)) {
            showErrorToast('Please select a valid future date');
            dateField.focus();
            return false;
        }
    }
    
    return true;
}

/**
 * Start the enhanced flight search process
 */
function startFlightSearch(form) {
    // Show initial search notification
    showSearchStartToast();
    
    // Update search button state
    const searchButton = form.querySelector('button[type="submit"]');
    updateSearchButtonState(searchButton, 'searching');
    
    // Create and show flight search overlay
    createFlightSearchOverlay();
    
    // Show airport suggestion loading if user is still typing
    showAirportSearchProgress();
    
    // Submit form after a short delay to show loading states
    setTimeout(() => {
        submitFlightSearchForm(form);
    }, 1000);
}

/**
 * Show search start notification
 */
function showSearchStartToast() {
    if (typeof Toastify !== 'undefined') {
        Toastify({
            text: "🔍 Starting flight search...",
            duration: 2000,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #00b4db, #0083b0)",
            stopOnFocus: true,
            className: "search-toast",
            avatar: "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIxIDRIMy41TDMgNlYxOEMzIDE5LjEgMy45IDIwIDUgMjBIMTlDMjAuMSAyMCAyMSAxOS4xIDIxIDE4VjZDMjEgNC45IDIwLjEgNCAyMSA0WiIgZmlsbD0iI0ZGRkZGRiIvPgo8L3N2Zz4K"
        }).showToast();
    }
}

/**
 * Show error notification
 */
function showErrorToast(message) {
    if (typeof Toastify !== 'undefined') {
        Toastify({
            text: `❌ ${message}`,
            duration: 4000,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            stopOnFocus: true,
            className: "error-toast"
        }).showToast();
    }
}

/**
 * Show airport search progress notification
 */
function showAirportSearchProgress() {
    // Override the existing fetchAirportResults function temporarily
    const originalFetch = window.fetchAirportResults;
    
    window.fetchAirportResults = function(query) {
        // Show search progress toast
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: `🛂 Searching airports for "${query}"...`,
                duration: 1500,
                gravity: "bottom",
                position: "left",
                backgroundColor: "linear-gradient(to right, #667eea, #764ba2)",
                stopOnFocus: true,
                className: "airport-search-toast"
            }).showToast();
        }
        
        // Call original function
        return originalFetch(query);
    };
    
    // Restore original function after 5 seconds
    setTimeout(() => {
        window.fetchAirportResults = originalFetch;
    }, 5000);
}

/**
 * Update search button state
 */
function updateSearchButtonState(button, state) {
    const buttonText = button.querySelector('span:last-child');
    const buttonIcon = button.querySelector('i');
    const spinner = button.querySelector('.spinner-border') || createSpinner();
    
    switch (state) {
        case 'searching':
            button.disabled = true;
            button.classList.add('btn-loading');
            buttonIcon.style.display = 'none';
            spinner.classList.remove('d-none');
            buttonText.textContent = 'Searching...';
            break;
            
        case 'redirecting':
            buttonText.textContent = 'Redirecting...';
            break;
            
        case 'error':
            button.disabled = false;
            button.classList.remove('btn-loading');
            buttonIcon.style.display = 'inline-block';
            spinner.classList.add('d-none');
            buttonText.textContent = 'Try Again';
            button.classList.add('btn-danger');
            break;
            
        default: // reset
            button.disabled = false;
            button.classList.remove('btn-loading', 'btn-danger');
            buttonIcon.style.display = 'inline-block';
            spinner.classList.add('d-none');
            buttonText.textContent = 'Search';
    }
}

/**
 * Create spinner element
 */
function createSpinner() {
    const spinner = document.createElement('span');
    spinner.className = 'spinner-border spinner-border-sm me-2 d-none';
    spinner.setAttribute('role', 'status');
    spinner.setAttribute('aria-hidden', 'true');
    
    const button = document.querySelector('button[type="submit"]');
    button.insertBefore(spinner, button.firstChild);
    
    return spinner;
}

/**
 * Create and show flight search overlay
 */
function createFlightSearchOverlay() {
    // Remove existing overlay if present
    const existingOverlay = document.getElementById('flightSearchOverlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    // Create overlay
    const overlay = document.createElement('div');
    overlay.id = 'flightSearchOverlay';
    overlay.className = 'flight-search-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(5px);
        animation: fadeIn 0.5s ease-in-out;
    `;
    
    // Create content
    const content = document.createElement('div');
    content.className = 'flight-search-content';
    content.style.cssText = `
        background: white;
        padding: 2rem;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 90%;
        animation: scaleIn 0.5s ease-in-out;
    `;
    
    content.innerHTML = `
        <div class="flight-animation mb-4">
            <svg width="100" height="60" viewBox="0 0 100 60" class="flight-svg">
                <!-- Flight path -->
                <path d="M10 40 Q30 20 50 40 Q70 20 90 40" stroke="#ddd" stroke-width="2" fill="none" stroke-dasharray="5,5">
                    <animate attributeName="stroke-dashoffset" values="0;10" dur="1s" repeatCount="indefinite"/>
                </path>
                
                <!-- Airplane -->
                <g class="airplane" transform="translate(10, 35)">
                    <path d="M0 5 L20 0 L25 2 L30 5 L25 8 L20 10 L0 5 Z" fill="#2563eb"/>
                    <circle cx="8" cy="5" r="2" fill="#ffc107"/>
                    <animateTransform
                        attributeName="transform"
                        type="translate"
                        values="10,35; 90,35"
                        dur="3s"
                        repeatCount="indefinite"/>
                </g>
                
                <!-- Departure city -->
                <circle cx="10" cy="40" r="4" fill="#10b981" opacity="0.8"/>
                <text x="10" y="55" text-anchor="middle" font-size="10" fill="#666">From</text>
                
                <!-- Destination city -->
                <circle cx="90" cy="40" r="4" fill="#ef4444" opacity="0.8"/>
                <text x="90" y="55" text-anchor="middle" font-size="10" fill="#666">To</text>
            </svg>
        </div>
        
        <h4 class="mb-3" style="color: #1e293b; font-weight: 600;">🛫 Searching Flights</h4>
        
        <div class="search-progress mb-3">
            <div class="progress" style="height: 8px; border-radius: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     style="width: 0%; background: linear-gradient(90deg, #2563eb, #3b82f6); border-radius: 10px;"
                     id="searchProgress"></div>
            </div>
        </div>
        
        <div class="search-status mb-3">
            <p class="mb-2" style="color: #64748b; font-size: 0.9rem;" id="searchStatusText">
                Connecting to flight databases...
            </p>
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                <small class="text-muted" id="searchStepText">Initializing search...</small>
            </div>
        </div>
        
        <div class="search-tips" style="background: #f8fafc; padding: 1rem; border-radius: 10px; border-left: 4px solid #2563eb;">
            <small style="color: #475569;">
                <strong>💡 Tip:</strong> We're checking multiple airlines to find you the best deals!
            </small>
        </div>
    `;
    
    overlay.appendChild(content);
    document.body.appendChild(overlay);
    
    // Start progress animation
    animateSearchProgress();
}

/**
 * Animate search progress
 */
function animateSearchProgress() {
    const progressBar = document.getElementById('searchProgress');
    const statusText = document.getElementById('searchStatusText');
    const stepText = document.getElementById('searchStepText');
    
    const steps = [
        { progress: 15, status: 'Connecting to flight databases...', step: 'Establishing secure connection...' },
        { progress: 30, status: 'Scanning available flights...', step: 'Checking airline schedules...' },
        { progress: 50, status: 'Comparing prices across airlines...', step: 'Finding best deals...' },
        { progress: 70, status: 'Filtering results by preferences...', step: 'Applying search criteria...' },
        { progress: 85, status: 'Finalizing flight options...', step: 'Preparing results...' },
        { progress: 100, status: 'Redirecting to results...', step: 'Almost ready!' }
    ];
    
    let currentStep = 0;
    
    const progressInterval = setInterval(() => {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            
            // Animate progress bar
            progressBar.style.width = step.progress + '%';
            
            // Update text
            if (statusText) statusText.textContent = step.status;
            if (stepText) stepText.textContent = step.step;
            
            currentStep++;
        } else {
            clearInterval(progressInterval);
        }
    }, 800);
}

/**
 * Submit flight search form
 */
function submitFlightSearchForm(form) {
    const formData = new FormData(form);
    
    // Show final status
    setTimeout(() => {
        const statusText = document.getElementById('searchStatusText');
        const stepText = document.getElementById('searchStepText');
        
        if (statusText) statusText.textContent = 'Redirecting to flight results...';
        if (stepText) stepText.textContent = 'Preparing your journey...';
        
        // Submit the form
        form.submit();
    }, 4000);
}


function submitFlightSearchForm(form) {
    const formData = new FormData(form);
    
    // Show final status before submission
    setTimeout(() => {
        const statusText = document.getElementById('searchStatusText');
        const stepText = document.getElementById('searchStepText');
        
        if (statusText) statusText.textContent = 'Redirecting to results...';
        if (stepText) stepText.textContent = 'Almost ready!';
        
        // Submit the form
        form.submit();
    }, 4000);
}

// Add this function to ensure proper display of flight results
function displayFlightResults(flights) {
    const container = document.querySelector('.flight-results');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!flights || flights.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                No flights found matching your criteria. Please try different dates or destinations.
            </div>
        `;
        return;
    }
    
    flights.forEach(flight => {
        const flightCard = createFlightCard(flight);
        container.appendChild(flightCard);
    });
}


// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes scaleIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    @keyframes fadeInAlert {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOutAlert {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    
    .fade-in-alert {
        animation: fadeInAlert 0.5s ease-in-out;
    }
    
    .fade-out {
        animation: fadeOutAlert 0.5s ease-in-out;
    }
    
    .btn-loading {
        position: relative;
        color: transparent !important;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .search-toast {
        border-radius: 10px !important;
        font-weight: 500 !important;
    }
    
    .error-toast {
        border-radius: 10px !important;
        font-weight: 500 !important;
    }
    
    .airport-search-toast {
        border-radius: 10px !important;
        font-weight: 500 !important;
    }
`;
document.head.appendChild(style);

// Execute when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Airport autocomplete functionality with focus flow
    setupImprovedAirportAutocomplete('rt-origin', 'rt-destination', 'fromSuggestions', 'toSuggestions', 'from_iata', 'to_iata');
    setupImprovedAirportAutocomplete('ow-origin', 'ow-destination', 'owFromSuggestions', 'owToSuggestions', 'ow-from-iata', 'ow-to-iata');
    
    // Set up multi-city flight form airport autocomplete
    setupMultiCityAutocomplete();
    
    // Initialize date fields with defaults
    setDefaultDates();
    
    // Setup tab navigation
    setupTabNavigation();
    
    // Add the help tooltip
    addFlightSearchTooltip();
    
    // Set up form submissions with loading indicators
        setupEnhancedFormSubmissions();

    
    console.log('Flight search JS initialized successfully');
});

/**
 * Set up multi-city autocomplete for all segments
 */
function setupMultiCityAutocomplete() {
    const multiCityContainer = document.getElementById('multi-city-flights');
    if (!multiCityContainer) return;
    
    // Initial setup for the first segment
    updateMultiCitySegmentAutocomplete(multiCityContainer.querySelector('.flight-segment'));
    
    // Watch for new segments
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1 && node.classList.contains('flight-segment')) {
                        updateMultiCitySegmentAutocomplete(node);
                    }
                });
            }
        });
    });
    
    observer.observe(multiCityContainer, { childList: true });
}

/**
 * Set up autocomplete for a specific multi-city segment
 * @param {HTMLElement} segment - The segment to set up
 */
function updateMultiCitySegmentAutocomplete(segment) {
    if (!segment) return;
    
    const fromInput = segment.querySelector('input[name="from[]"]');
    const toInput = segment.querySelector('input[name="to[]"]');
    const fromHiddenInput = segment.querySelector('input[name="from_iata[]"]');
    const toHiddenInput = segment.querySelector('input[name="to_iata[]"]');
    
    if (!fromInput || !toInput || !fromHiddenInput || !toHiddenInput) return;
    
    // Create suggestion containers if they don't exist
    let fromSuggestions = segment.querySelector('.from-suggestions');
    let toSuggestions = segment.querySelector('.to-suggestions');
    
    if (!fromSuggestions) {
        fromSuggestions = document.createElement('div');
        fromSuggestions.className = 'airport-suggestions from-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg';
        fromSuggestions.style.zIndex = '1000';
        fromInput.parentNode.appendChild(fromSuggestions);
    }
    
    if (!toSuggestions) {
        toSuggestions = document.createElement('div');
        toSuggestions.className = 'airport-suggestions to-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg';
        toSuggestions.style.zIndex = '1000';
        toInput.parentNode.appendChild(toSuggestions);
    }
    
    // Set up autocomplete with the same logic as other fields
    setupSegmentAutocomplete(fromInput, toInput, fromSuggestions, toSuggestions, fromHiddenInput, toHiddenInput);
}

/**
 * Set up autocomplete for a multi-city segment
 */
function setupSegmentAutocomplete(fromInput, toInput, fromSuggestions, toSuggestions, fromHidden, toHidden) {
    // Variables for debounce
    let fromDebounceTimeout;
    let toDebounceTimeout;
    const debounceDelay = 200;
    
    // Track field selection status
    let fromFieldSelected = false;
    let toFieldSelected = false;
    
    // FROM Input handling
    fromInput.addEventListener('input', function() {
        if (fromFieldSelected) {
            fromFieldSelected = false;
            fromHidden.value = '';
        }
        
        clearTimeout(fromDebounceTimeout);
        const query = this.value.trim();
        
        if (query.length < 3) {
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
            return;
        }
        
        fromDebounceTimeout = setTimeout(() => {
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
            
            // Use shared fetch function
            fetchAirportResults(query)
                .then(data => {
                    renderEnhancedSuggestions(data, fromInput, fromSuggestions, fromHidden);
                })
                .catch(error => {
                    console.error('Error fetching airport suggestions:', error);
                    fromSuggestions.innerHTML = '<div class="text-danger p-2">Error fetching suggestions</div>';
                });
        }, debounceDelay);
    });
    
    // TO Input handling (similar to FROM)
    toInput.addEventListener('input', function() {
        if (toFieldSelected) {
            toFieldSelected = false;
            toHidden.value = '';
        }
        
        clearTimeout(toDebounceTimeout);
        const query = this.value.trim();
        
        if (query.length < 3) {
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
            return;
        }
        
        toDebounceTimeout = setTimeout(() => {
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
            
            // Use shared fetch function
            fetchAirportResults(query)
                .then(data => {
                    renderEnhancedSuggestions(data, toInput, toSuggestions, toHidden);
                })
                .catch(error => {
                    console.error('Error fetching airport suggestions:', error);
                    toSuggestions.innerHTML = '<div class="text-danger p-2">Error fetching suggestions</div>';
                });
        }, debounceDelay);
    });
    
    // Show typing hints
    fromInput.addEventListener('focus', function() {
        if (!fromFieldSelected && this.value.trim() === '') {
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
            
            fromSuggestions.innerHTML = `<div class="text-center text-muted p-2">
                <i class="fas fa-info-circle me-2"></i>
                Type at least 3 characters to see airport suggestions
            </div>`;
            fromSuggestions.classList.add('p-2');
        }
    });
    
    toInput.addEventListener('focus', function() {
        if (!toFieldSelected && this.value.trim() === '') {
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
            
            toSuggestions.innerHTML = `<div class="text-center text-muted p-2">
                <i class="fas fa-info-circle me-2"></i>
                Type at least 3 characters to see airport suggestions
            </div>`;
            toSuggestions.classList.add('p-2');
        }
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== fromInput && !fromSuggestions.contains(e.target)) {
            fromSuggestions.innerHTML = '';
            fromSuggestions.classList.remove('p-2');
        }
        
        if (e.target !== toInput && !toSuggestions.contains(e.target)) {
            toSuggestions.innerHTML = '';
            toSuggestions.classList.remove('p-2');
        }
    });
}

/**
 * Set default dates for date fields
 */
function setDefaultDates() {
    // Get all date input fields
    const departDateFields = document.querySelectorAll('input[name="date"], input[name="date[]"]');
    const returnDateFields = document.querySelectorAll('input[name="return_date"]');
    
    // Set minimum date to tomorrow
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const nextWeek = new Date(today);
    nextWeek.setDate(nextWeek.getDate() + 7);
    
    const formatDate = date => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    // Set default and min dates for departure fields
    departDateFields.forEach(field => {
        const tomorrowStr = formatDate(tomorrow);
        field.min = tomorrowStr;
        if (!field.value) field.value = tomorrowStr;
    });
    
    // Set default and min dates for return fields
    returnDateFields.forEach(field => {
        const nextWeekStr = formatDate(nextWeek);
        field.min = formatDate(tomorrow);
        if (!field.value) field.value = nextWeekStr;
    });
}

/**
 * Set up tab navigation
 */
function setupTabNavigation() {
    const tabButtons = document.querySelectorAll('.nav-tab-btn');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Show selected tab content
            const selectedTab = document.querySelector(`.tab-content[data-tab="${tabName}"]`);
            if (selectedTab) selectedTab.style.display = 'block';
        });
    });
}