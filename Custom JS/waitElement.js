/**
 * Waits for an element matching the specified selector to appear in the document.
 * @param {string} selector - The CSS selector for the element to wait for.
 * @param {number} [timeout=10000] - The maximum time to wait for the element, in milliseconds. Default is 10 seconds.
 * @returns {Promise<Element>} - A promise that resolves with the first matching element.
 * @throws {string} - If the element is not found within the specified timeout, a rejection with an error message is thrown.
 */

function waitElement(selector, timeout = 10000) {
    // Returns a promise that resolves when the element is found or rejects if it times out.
    return new Promise(function (resolve, reject) {
        // Sets up an interval to check for the element at regular intervals.
        const interval = setInterval(function () {
            // Attempts to find the element in the document.
            const element = document.querySelector(selector);
            // If the element is found, resolve the promise with it.
            if (element) resolve(element);
        }, 500); // Interval time of 500 milliseconds (half a second).

        // Sets up a timeout to reject the promise if the element is not found within the specified timeout.
        setTimeout(function () {
            // Rejects the promise with an error message indicating timeout and the selector.
            reject(`Request Timeout ${timeout}ms (${selector} is not found)`)
        }, timeout); // Timeout value specified by the user or defaulting to 10 seconds.
    });
}

/**
 * Example usage of the waitElement function.
 */
async function sampleUsage() {
    try {
        // Waits for an element with the ID "myElement" to appear in the document.
        const element = await waitElement("#myElement");
        // Logs a message indicating that the element has been found.
        console.log("Element found:", element);
    } catch (error) {
        // Logs an error message if there was a problem waiting for the element.
        console.error("Error:", error);
    }
}