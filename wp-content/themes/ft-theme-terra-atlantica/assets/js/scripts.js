window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  var element = document.getElementById("header");
  if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
    element.classList.add("nav-brown");

  } else {
    element.classList.remove("nav-brown");
  }
}

let items = document.querySelectorAll('#carouselAtracoes .carousel, #carouselAtracoes .carousel-item')

items.forEach((el) => {
    const minPerSlide = 3
    let next = el.nextElementSibling
    for (var i=1; i<minPerSlide; i++) {
        if (!next) {
            // wrap carousel by using first child
        	next = items[0]
      	}
        let cloneChild = next.cloneNode(true)
        el.appendChild(cloneChild.children[0])
        next = next.nextElementSibling
    }
})