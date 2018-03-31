import { Component, Input, OnInit } from "@angular/core";
import { Item } from "../item.model";

@Component({
	selector    : "app-sidemenu-item-link",
	templateUrl : "./item-link.component.html",
	styleUrls   : [ "./item-link.component.scss" ],
})
export class ItemLinkComponent implements OnInit {

	@Input('item')
	public item: Item;

	constructor () {
	}

	ngOnInit () {
	}

}
