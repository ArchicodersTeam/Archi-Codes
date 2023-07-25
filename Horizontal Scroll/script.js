document.addEventListener('DOMContentLoaded', function () {
    gsap.registerPlugin(ScrollTrigger);
    let sections = gsap.utils.toArray(".scroll-trigger-item");
    let scrollTween = gsap.to(sections, {
        xPercent: -100 * (sections.length - 1),
        ease: "none", // <-- IMPORTANT!
        scrollTrigger: {
            trigger: "#scroll-trigger-wrap",
            pin: true,
            scrub: 1,
            end: `+=3000`,
        }
    });
})