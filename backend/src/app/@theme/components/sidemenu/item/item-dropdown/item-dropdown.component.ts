import { Component, Input, OnInit } from "@angular/core";
import { Router } from "@angular/router";
import { Item } from "../item.model";

@Component({
	selector    : "app-sidemenu-item-dropdown",
	templateUrl : "./item-dropdown.component.html",
	styleUrls   : [ "./item-dropdown.component.scss" ],
})
export class ItemDropdownComponent implements OnInit {

	@Input('item')
	public item: Item;

	public isCollapsed: boolean;

	constructor (private _router: Router) {
	}

	ngOnInit () {
		this.isCollapsed = !this.childIsActive();
	}

	childIsActive () {
		let result = false;

		this.item.children.forEach((child) => {
			if (this._router.isActive(child.link, true)) {
				result = true;
			}
		});

		return result;
	}
}
