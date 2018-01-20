import { Component, Input } from "@angular/core";

@Component({
	selector    : "ngx-list-filter",
	templateUrl : "./filter.component.html",
	styleUrls   : [ "./filter.component.scss" ],
})
export class FilterComponent {

	@Input() size: string;
	@Input() title: string;
	@Input() type: string;
	@Input() active: boolean = true;

	constructor () { }
}
