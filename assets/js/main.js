document.addEventListener('DOMContentLoaded', function() {
    // Handle price refresh
    const refreshButtons = document.querySelectorAll('.refresh-price');
    refreshButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            try {
                const response = await fetch(`/api/refresh-price/${productId}`);
                const data = await response.json();
                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error refreshing price:', error);
            }
        });
    });

    // Handle category filtering
    const categorySelect = document.getElementById('category-filter');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const category = this.value;
            window.location.href = category ? `/?category=${category}` : '/';
        });
    }
});