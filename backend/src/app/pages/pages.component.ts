import { Component } from "@angular/core";

import { MENU_ITEMS } from "./pages-menu";

@Component({
	selector    : "ngx-pages",
	templateUrl : "/pages.component.html",
})
export class PagesComponent {

	menu = MENU_ITEMS;
}
