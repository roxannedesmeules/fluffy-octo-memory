import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";

@Component({
	selector    : "app-layout-header",
	templateUrl : "./header.component.html",
	styleUrls   : [ "./header.component.scss" ],
})
export class HeaderComponent implements OnInit {

	@Input() isShrinked:boolean = false;
	@Output() toggleSidemenu: EventEmitter<boolean> = new EventEmitter();

	constructor () {
	}

	ngOnInit () {
	}

}
