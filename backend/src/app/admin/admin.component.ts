import { Component } from "@angular/core";

import { MENU_ITEMS } from "./admin-menu";

@Component({
	selector    : "ngx-admin",
	templateUrl : "/admin.component.html",
})
export class AdminComponent {

	menu = MENU_ITEMS;
}
