import { AfterViewChecked, Directive, ElementRef, HostListener, Input } from "@angular/core";

@Directive({
    selector : "[imgMatchHeight]",
})
export class MatchHeightDirective implements AfterViewChecked {

    @Input()
    imgMatchHeight: string;

    constructor(private el: ElementRef) {
    }

    @HostListener("window:resize")
    onResize() {
        // call our matchHeight function here
        this.matchHeight(this.el.nativeElement, this.imgMatchHeight);
    }

    ngAfterViewChecked() {
        this.matchHeight(this.el.nativeElement, this.imgMatchHeight);
    }

    matchHeight(parent: HTMLElement, className: string) {
        if (!parent) {
            return;
        }

        //  find all elements by class name
        const children = parent.getElementsByClassName(className);

        if (!children) {
            return;
        }

        //  reset all children height
        Array.from(children).forEach((x: HTMLElement) => {
            x.style.height = "initial";
        });

        //  get all children elements
        const itemHeights = Array.from(children).map(x => x.getBoundingClientRect().height);

        //  find the tallest element from all items
        const maxHeight = itemHeights.reduce((prev, curr) => {
            return (curr > prev ? curr : prev);
        }, 0);

        //  update closest image height, to match the tallest element
        Array.from(parent.getElementsByClassName("image"))
             .forEach((x: HTMLElement) => {
                 x.style.minHeight = `${maxHeight}px`;
             });
    }
}
