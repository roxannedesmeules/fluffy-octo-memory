import { Component, OnInit } from "@angular/core";
import { Item } from "./item/item.model";

@Component({
	selector    : "app-layout-sidemenu",
	templateUrl : "./sidemenu.component.html",
	styleUrls   : [ "./sidemenu.component.scss" ],
})
export class SidemenuComponent implements OnInit {

	public items = [];

	constructor () {
	}

	ngOnInit () {
		this._initMenu();
	}

	private _initMenu () {
		const categories = new Item({
			id : "category", title : "Categories", icon : "far fa-folder-open", children : [
				{ id : "category-create", title : "Create", link : "/admin/categories/create" },
				{ id : "category-list", title : "View all", link : "/admin/categories/list" },
			],
		});

		const posts = new Item({
			id : "post", title : "Posts", icon : "far fa-file-alt", children : [
				{ id : "post-create", title : "Create", link : "/admin/posts/create" },
				{ id : "post-list", title : "View all", link : "/admin/posts/list" },
			],
		});

		const tags = new Item({
			id : "tag", title : "Tags", icon : "fas fa-tags", children : [
				{ id : "tag-create", title : "Create", link : "/admin/tags/create" },
				{ id : "tag-list", title : "View all", link : "/admin/tags/list" },
			],
		});

		this.items.push(new Item({ id: "home", title : "Home", icon : "icon-home", link : "/admin" }));
		this.items.push(categories);
		this.items.push(posts);
		this.items.push(tags);
	}
}
