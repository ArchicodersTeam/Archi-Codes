function init_responsive_view(){
    const new_tab = window.open(window.location.href, '_blank')
    const screenSizes = [1600, 1440, 1200, 1025, 1024, 768, 767, 620, 420, 320].map(size => {
        const wrap = document.createElement('div')
        const title = document.createElement('h2')
        const iframe = document.createElement('iframe')
        iframe.width = size
        iframe.height = 800
        iframe.src = window.location.href

        title.style = `color: #000; font-size: 18px; margin: 2em 0 1em 0;`
        title.innerHTML = `${iframe.width} x ${iframe.height}`
        wrap.append(title, iframe)
        
        return wrap
    })
    new_tab.addEventListener('load', function(){
        let body = this.document.body
        body.style.margin = "20px"
        body.innerHTML = '<h1 style="color: #000; font-size: 25px; margin: 1em 0;">Archicoders Responsive Checker</h1><hr>'

        body.append(...screenSizes)

    })
}

init_responsive_view()