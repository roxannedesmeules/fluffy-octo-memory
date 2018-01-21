import { Component, EventEmitter, Input, Output } from "@angular/core";

@Component({
	selector    : "ngx-list-filter",
	templateUrl : "./filter.component.html",
	styleUrls   : [ "./filter.component.scss" ],
})
export class FilterComponent {

	@Output() onChange = new EventEmitter();

	@Input() size: string;
	@Input() title: string;
	@Input() type: string;
	@Input() active: number = -1;

	constructor () { }

	change () {
		this.active += 1;

		if (this.active === 2) {
			this.active = -1;
		}

		this.onChange.emit(this.active);
	}
}
