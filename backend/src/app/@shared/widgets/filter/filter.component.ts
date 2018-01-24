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

	@Input() options: any[];

	constructor () { }

	/**
	 * select the item that was clicked.
	 * @param value
	 */
	select ( value ) {
		this.active = value;

		this.onChange.emit(value);
	}
}
