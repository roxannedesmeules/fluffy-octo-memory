import { Component, Input, OnInit } from "@angular/core";
import { Item } from "../item.model";

@Component({
	selector    : "app-sidemenu-item-dropdown",
	templateUrl : "./item-dropdown.component.html",
	styleUrls   : [ "./item-dropdown.component.scss" ],
})
export class ItemDropdownComponent implements OnInit {

	@Input('item')
	public item: Item;

	public isCollapsed = true;

	constructor () {
	}

	ngOnInit () {
	}

}
