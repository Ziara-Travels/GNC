/**
 * Wishlist functionality for Ziara Travel
 * Handles adding/removing items from wishlist for both guest and authenticated users
 */

class WishlistManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateWishlistCounts();
    }

    bindEvents() {
        // Add to wishlist buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="add-to-wishlist"]') || 
                e.target.closest('[data-action="add-to-wishlist"]')) {
                e.preventDefault();
                const button = e.target.matches('[data-action="add-to-wishlist"]') ? 
                              e.target : e.target.closest('[data-action="add-to-wishlist"]');
                this.addToWishlist(button);
            }
        });

        // Remove from wishlist buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="remove-from-wishlist"]') || 
                e.target.closest('[data-action="remove-from-wishlist"]')) {
                e.preventDefault();
                const button = e.target.matches('[data-action="remove-from-wishlist"]') ? 
                              e.target : e.target.closest('[data-action="remove-from-wishlist"]');
                this.removeFromWishlist(button);
            }
        });

        // Clear wishlist
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="clear-wishlist"]')) {
                e.preventDefault();
                this.clearWishlist();
            }
        });
    }

    async addToWishlist(button) {
        const itemData = this.extractItemData(button);
        
        try {
            button.disabled = true;
            button.innerHTML = '<i class="bx bx-loader bx-spin"></i> Adding...';

            const response = await fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(itemData)
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateWishlistButton(button, true);
                this.updateWishlistCounts();
            } else if (data.require_login) {
                if (data.guest_item_stored) {
                    this.showMessage(data.message, 'info');
                    this.showLoginPrompt();
                } else {
                    this.showMessage(data.message, 'warning');
                    this.redirectToLogin();
                }
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Wishlist error:', error);
            this.showMessage('An error occurred. Please try again.', 'error');
        } finally {
            button.disabled = false;
            this.resetButtonText(button);
        }
    }

    async removeFromWishlist(button) {
        const itemId = button.dataset.itemId;
        
        if (!itemId) {
            this.showMessage('Item ID not found', 'error');
            return;
        }

        try {
            button.disabled = true;

            const response = await fetch(`/wishlist/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateWishlistButton(button, false);
                this.updateWishlistCounts();
                
                // Remove item from DOM if on wishlist page
                const itemElement = button.closest('.wishlist-item');
                if (itemElement) {
                    itemElement.remove();
                }
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Remove wishlist error:', error);
            this.showMessage('An error occurred. Please try again.', 'error');
        } finally {
            button.disabled = false;
        }
    }

    async clearWishlist() {
        if (!confirm('Are you sure you want to clear your entire wishlist?')) {
            return;
        }

        try {
            const response = await fetch('/wishlist/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateWishlistCounts();
                
                // Remove all items from DOM if on wishlist page
                document.querySelectorAll('.wishlist-item').forEach(item => item.remove());
            } else {
                this.showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Clear wishlist error:', error);
            this.showMessage('An error occurred. Please try again.', 'error');
        }
    }

    extractItemData(button) {
        return {
            item_id: button.dataset.itemId || '',
            type: button.dataset.type || '',
            title: button.dataset.title || '',
            description: button.dataset.description || '',
            image: button.dataset.image || '',
            price: parseFloat(button.dataset.price) || null,
            original_price: parseFloat(button.dataset.originalPrice) || null,
            location: button.dataset.location || '',
            item_data: JSON.parse(button.dataset.itemData || '{}')
        };
    }

    updateWishlistButton(button, inWishlist) {
        if (inWishlist) {
            button.setAttribute('data-action', 'remove-from-wishlist');
            button.innerHTML = '<i class="bx bxs-heart"></i> In Wishlist';
            button.classList.add('btn-danger');
            button.classList.remove('btn-outline-primary');
        } else {
            button.setAttribute('data-action', 'add-to-wishlist');
            button.innerHTML = '<i class="bx bx-heart"></i> Add to Wishlist';
            button.classList.add('btn-outline-primary');
            button.classList.remove('btn-danger');
        }
    }

    resetButtonText(button) {
        const action = button.getAttribute('data-action');
        if (action === 'add-to-wishlist') {
            button.innerHTML = '<i class="bx bx-heart"></i> Add to Wishlist';
        } else {
            button.innerHTML = '<i class="bx bxs-heart"></i> In Wishlist';
        }
    }

    async updateWishlistCounts() {
        try {
            const response = await fetch('/wishlist/count');
            const data = await response.json();
            
            // Update wishlist count in navigation
            document.querySelectorAll('.wishlist-count').forEach(element => {
                element.textContent = data.count;
                element.style.display = data.count > 0 ? 'inline' : 'none';
            });
        } catch (error) {
            console.error('Error updating wishlist count:', error);
        }
    }

    showMessage(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.style.minWidth = '300px';
        
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    showLoginPrompt() {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Account to Save Wishlist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Your item has been temporarily saved! Create an account to permanently add it to your wishlist and access it from any device.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Later</button>
                        <a href="/register" class="btn btn-primary">Create Account</a>
                        <a href="/login" class="btn btn-outline-primary">Login</a>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        modal.addEventListener('hidden.bs.modal', () => {
            document.body.removeChild(modal);
        });
    }

    redirectToLogin() {
        window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
    }

    // Process guest wishlist items after login
    async processGuestWishlist() {
        try {
            const response = await fetch('/wishlist/guest/process', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success && data.items_added > 0) {
                this.showMessage(data.message, 'success');
                this.updateWishlistCounts();
            }
        } catch (error) {
            console.error('Error processing guest wishlist:', error);
        }
    }
}

// Initialize wishlist manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.wishlistManager = new WishlistManager();
    
    // Process guest wishlist items if user just logged in
    if (document.querySelector('meta[name="user-authenticated"]')) {
        window.wishlistManager.processGuestWishlist();
    }
});
