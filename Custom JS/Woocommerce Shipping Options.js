// Wait for the DOM to be fully loaded before executing the code
document.addEventListener('DOMContentLoaded', function () {
    // Get the 'shipping_method' element
    const shipping_option = document.getElementById('shipping_method')

    // If 'shipping_option' does not exist, exit the function
    if (!shipping_option)
        return false

    // Attach an event listener to listen for AJAX complete events
    jQuery(document).ajaxComplete(handle_shipping_options)

    // Call 'handle_shipping_options' function immediately
    handle_shipping_options()

    // Function to handle shipping options
    function handle_shipping_options() {
        // Get all elements with the class 'shipping_method' and convert them to an array
        const shipping_options = [...document.querySelectorAll('.shipping_method')].map((e) => {
            return {
                value: e.value,
                form: e.form,
                select: () => e.click(),
                is_checked: e.checked,
                label: e.labels ? e.labels[0].textContent : 'no label',
                hide: hidden => {
                    if (hidden) e.parentElement.remove()
                }
            }
        })

        // Find the 'free_shipping' and 'express_shipping' options
        const free_shipping = shipping_options.find(obj => obj.value.indexOf('free_shipping') >= 0)
        const regular_shipping = shipping_options.find(obj => obj.label.toLowerCase().indexOf('regular') == 0)
        const express_shipping = shipping_options.find(obj => obj.label.toLowerCase().indexOf('express') == 0)
        const shipping_destination = document.querySelector('.woocommerce-shipping-destination')

        // If 'free_shipping' option exists
        if (free_shipping) {
            // Show free and express shipping options
            shipping_options.forEach(option => {
                option.hide(false)
                if (option !== free_shipping && option !== express_shipping) {
                    option.hide(true)
                }
            })
            // If 'express_shipping' is not checked, select 'free_shipping'
            if (!express_shipping.is_checked)
                free_shipping.select()

        } else if (!regular_shipping.is_checked && !express_shipping.is_checked && shipping_destination) {
            shipping_destination.remove()
        }
    }
});