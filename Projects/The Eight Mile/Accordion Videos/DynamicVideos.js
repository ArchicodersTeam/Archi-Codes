function initDynamicVideos(){
    const dynamicAccordion = document.querySelectorAll('.dynamic-accordion') //parent section selector
    if(!dynamicAccordion) return 0
    
    dynamicAccordion.forEach(dynamic => {
        var toggles = [...dynamic.querySelectorAll('.dynamic-accordion-toggles .toggler')] //toggles selector
        var videos = [...dynamic.querySelectorAll('.dynamic-accordion-videos .appearing-video')] //videos wrap selector

        function openActiveVideo(index, paused = false){
            const wrap = index >= videos.length ? videos[0] : videos[index]
            const video = wrap.querySelector('video') //Video selector

            wrap.removeAttribute('style')
            if(!paused) video.play()
        }

        function closeAllVideo(){
            videos.forEach(video => {
                const player = video.querySelector('video')
                video.style.display = 'none'
                player.pause()
            })
        }

        window.addEventListener('hashchange', function(){
            const active = dynamic.querySelector('.toggler.activeTitle')
            const index = toggles.indexOf(active)

            if(index === -1) return 0
            closeAllVideo()
            openActiveVideo(index)
        })

        closeAllVideo()
        openActiveVideo(0, true)
    })
}
initDynamicVideos()