function getAllHeadingFontStyles() {
    // Set to store unique font styles with various properties for h1-h6 tags only
    const fontStyles = new Set();

    // Loop through all h1 to h6 elements on the page
    document.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(element => {
        // Get computed styles for the element
        const computedStyle = window.getComputedStyle(element);
        const fontSize = computedStyle.fontSize;
        const fontFamily = computedStyle.fontFamily;
        const lineHeight = computedStyle.lineHeight;
        const letterSpacing = computedStyle.letterSpacing;
        const fontWeight = computedStyle.fontWeight;
        const fontStyle = computedStyle.fontStyle;
        const tagName = element.tagName;

        // Create a unique style identifier
        const styleIdentifier = `${tagName} - ${fontSize} - ${fontFamily} - ${lineHeight} - ${letterSpacing} - ${fontWeight} - ${fontStyle}`;

        // Add the style identifier to the set if it exists
        if (fontSize && fontFamily) {
            fontStyles.add(styleIdentifier);
        }
    });

    // Convert the Set to an array and return it
    return Array.from(fontStyles).map(style => {
        // Split the identifier back into individual properties
        const [tagName, fontSize, fontFamily, lineHeight, letterSpacing, fontWeight, fontStyle] = style.split(' - ');
        return { tagName, fontSize, fontFamily, lineHeight, letterSpacing, fontWeight, fontStyle };
    });
}

// Example usage
console.log(getAllHeadingFontStyles());