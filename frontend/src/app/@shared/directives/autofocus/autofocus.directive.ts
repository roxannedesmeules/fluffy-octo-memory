import { Directive, ElementRef, Input } from "@angular/core";

@Directive({
	selector : "[autofocus]",
})
export class AutofocusDirective {

	private focus = true;

	constructor ( private el: ElementRef ) {
	}

	ngOnInit () {
		this.setFocus();
	}

	@Input() set autofocus ( condition: boolean ) {
		this.focus = condition !== false;

		this.setFocus();
	}

	setFocus () {
		if (this.focus) {
			window.setTimeout(() => {
				this.el.nativeElement.focus();
			});
		}
	}
}
