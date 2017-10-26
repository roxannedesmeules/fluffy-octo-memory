import { Component, OnInit } from "@angular/core";
import { BreadcrumbItems } from "core/layout/breadcrumb/breadcrumb-items.model";
import { PageHeaderService } from "core/layout/page-header/page-header.service";
import { CategoriesService } from "services/categories/categories.service";
import { ActivatedRoute, ActivatedRouteSnapshot } from "@angular/router";
import { Category } from "models/categories/category.model";

@Component({
	selector    : "app-categories-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public list: Category[];

	header = {
		title      : "Categories",
		subTitle   : "List of categories",
		breadcrumb : [
			new BreadcrumbItems({ name : "Categories", isActive : true }),
		],
	};

	constructor (private _route: ActivatedRoute,
	             private headerService: PageHeaderService) { }

	ngOnInit () {
		this.headerService.setPageHeader(this.header);

		this.list = this._route.snapshot.data[ "list" ];
	}

}
