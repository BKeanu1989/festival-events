class MoveElement {
    // only if window.innerWidth > 1200 whatever ...
    constructor($movingElement, $endingElement) {
        this.movingElement = $movingElement;
        this.movingElementPos;
        this.endingElement = $endingElement;
        this.endingElementPos;
        this.active = false;
        this.pageYOffset;
        this.oldPageYOffset;
        this.unit = 'px';
        this.step = 20;
    }

    init() {
        this.pageYOffset = window.pageYOffset;
        this.oldPageYOffset = window.pageYOffset;
    }

    install() {
        this.init();
        this.moveHandler();
    }

    moveElement() {
        
        let topNow = parseFloat(this.movingElement.style.top) || 0;
        let newTop = this.pageYOffset - this.oldPageYOffset;
        debugger;
        let newTopString = newTop + this.unit;
        this.movingElement.style.top = newTopString;
    }

    moveHandler() {
        let that = this;
        window.addEventListener('scroll', function(event) {
            
            if (!that.active) {
                window.requestAnimationFrame(function() {
                    that.pageYOffset = window.pageYOffset;
                    that.moveElement();
                    that.active = false;
                })
                // this.active = true;
            }
        })
    }
}

let $movingElement = document.querySelector('.single-product-form-wrapper');
let $endingElement = document.querySelector('.wrapper.info');
let mover;

if ($movingElement && $endingElement) {
    mover = new MoveElement($movingElement, $endingElement);
    mover.install();
}