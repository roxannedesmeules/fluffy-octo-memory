import { Component, Input, OnInit } from "@angular/core";
import { Item } from "./item.model";

@Component({
	selector    : "app-sidemenu-item",
	templateUrl : "./item.component.html",
	styleUrls   : [ "./item.component.scss" ],
})
export class ItemComponent implements OnInit {

	@Input('item')
	public item: Item;

	constructor () {
	}

	ngOnInit () {
	}

}
